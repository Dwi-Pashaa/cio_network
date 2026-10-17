<?php

namespace App\Http\Controllers\Pages\BarangOperasional;

use App\DataTables\BarangOperasional\BarangOperasionalDataTable;
use App\DataTables\BarangOperasional\TransferBarangOperasionalDataTable;
use App\Http\Controllers\Controller;
use App\Models\BarangOperasional;
use App\Models\Organization;
use App\Models\TipeBarangOperasional;
use App\Models\TransferBarangOperasional;
use App\Models\User;
use App\Models\UserBarangOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Services\TransferBarangNotificationService;

class BarangOperasionalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new BarangOperasionalDataTable)->get();
        }

        $user = Auth::user();
        $orgId = $user->organization_id;

        $tipeQuery = TipeBarangOperasional::query();
        if ($user->organization?->type === 'mitra') {
            $tipeQuery->where('organization_id', $orgId);
        }
        $tipeList = $tipeQuery->orderBy('nama_tipe')->get();

        // Users list for Admin distribution
        $usersQuery = User::query();
        if ($user->organization?->type === 'mitra') {
            $usersQuery->where('organization_id', $orgId);
        }
        $usersList = $usersQuery->orderBy('name')->get(['id', 'name', 'username']);

        // Technicians list for user-to-technician transfer
        $teknisiQuery = User::whereHas('roles', function ($q) {
            $q->where('name', 'Teknisi');
        })->where('id', '!=', $user->id);

        if ($user->organization?->type === 'mitra') {
            $teknisiQuery->where('organization_id', $orgId);
        }
        $teknisiList = $teknisiQuery->orderBy('name')->get(['id', 'name', 'username']);

        // If no technicians found via role, fallback to all users except self
        if ($teknisiList->isEmpty()) {
            $fallbackQuery = User::where('id', '!=', $user->id);
            if ($user->organization?->type === 'mitra') {
                $fallbackQuery->where('organization_id', $orgId);
            }
            $teknisiList = $fallbackQuery->orderBy('name')->get(['id', 'name', 'username']);
        }

        $organizations = Organization::all();

        $pendingTransferCount = TransferBarangOperasional::where('penerima_id', $user->id)
            ->where('status', 'menunggu_konfirmasi')
            ->count();

        return view('pages.barang-operasional.barang.index', compact('tipeList', 'usersList', 'teknisiList', 'organizations', 'pendingTransferCount'));
    }

    public function getTipeInfo(string $id)
    {
        $tipe = TipeBarangOperasional::find($id);
        if (!$tipe) {
            return response()->json(['code' => 404, 'message' => 'Tipe barang tidak ditemukan.']);
        }

        return response()->json([
            'code'              => 200,
            'status'            => 'success',
            'has_mac_address'   => (bool) $tipe->has_mac_address,
            'has_serial_number' => (bool) $tipe->has_serial_number,
            'nama_tipe'         => $tipe->nama_tipe,
        ]);
    }

    public function store(Request $request)
    {
        $tipe = TipeBarangOperasional::find($request->tipe_barang_id);
        if (!$tipe) {
            return response()->json(['code' => 400, 'errors' => ['tipe_barang_id' => ['Tipe barang wajib dipilih.']]]);
        }

        $rules = [
            'tipe_barang_id' => 'required|exists:tipe_barang_operasionals,id',
            'nama_barang'    => 'required|string|max:255',
            'merk'           => 'nullable|string|max:100',
            'satuan'         => 'required|string|max:50',
            'total_stok'     => 'required|integer|min:0',
            'spesifikasi'    => 'nullable|string',
        ];

        if ($tipe->has_mac_address) {
            $rules['mac_address'] = 'required|string|max:100';
        } else {
            $rules['mac_address'] = 'nullable|string|max:100';
        }

        if ($tipe->has_serial_number) {
            $rules['serial_number'] = 'required|string|max:100';
        } else {
            $rules['serial_number'] = 'nullable|string|max:100';
        }

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $post = [
            'organization_id' => Auth::user()->organization_id,
            'tipe_barang_id'  => $request->tipe_barang_id,
            'nama_barang'     => $request->nama_barang,
            'merk'            => $request->merk,
            'satuan'          => $request->satuan,
            'total_stok'      => $request->total_stok,
            'spesifikasi'     => $request->spesifikasi,
            'mac_address'     => $tipe->has_mac_address ? $request->mac_address : null,
            'serial_number'   => $tipe->has_serial_number ? $request->serial_number : null,
        ];

        BarangOperasional::create($post);

        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Berhasil menambahkan data barang operasional.',
        ]);
    }

    public function show(string $id)
    {
        $barang = BarangOperasional::with('tipeBarang')->find($id);

        if (!$barang) {
            return response()->json(['code' => 404, 'status' => 'errors', 'message' => 'Data tidak ditemukan.']);
        }

        return response()->json(['code' => 200, 'status' => 'success', 'data' => $barang]);
    }

    public function update(Request $request, string $id)
    {
        $barang = BarangOperasional::find($id);
        if (!$barang) {
            return response()->json(['code' => 404, 'status' => 'errors', 'message' => 'Data tidak ditemukan.']);
        }

        $tipe = TipeBarangOperasional::find($request->tipe_barang_id ?? $barang->tipe_barang_id);
        if (!$tipe) {
            return response()->json(['code' => 400, 'errors' => ['tipe_barang_id' => ['Tipe barang wajib dipilih.']]]);
        }

        $rules = [
            'tipe_barang_id' => 'required|exists:tipe_barang_operasionals,id',
            'nama_barang'    => 'required|string|max:255',
            'merk'           => 'nullable|string|max:100',
            'satuan'         => 'required|string|max:50',
            'total_stok'     => 'required|integer|min:0',
            'spesifikasi'    => 'nullable|string',
        ];

        if ($tipe->has_mac_address) {
            $rules['mac_address'] = 'required|string|max:100';
        } else {
            $rules['mac_address'] = 'nullable|string|max:100';
        }

        if ($tipe->has_serial_number) {
            $rules['serial_number'] = 'required|string|max:100';
        } else {
            $rules['serial_number'] = 'nullable|string|max:100';
        }

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $barang->update([
            'tipe_barang_id' => $request->tipe_barang_id,
            'nama_barang'    => $request->nama_barang,
            'merk'           => $request->merk,
            'satuan'         => $request->satuan,
            'total_stok'     => $request->total_stok,
            'spesifikasi'    => $request->spesifikasi,
            'mac_address'    => $tipe->has_mac_address ? $request->mac_address : null,
            'serial_number'  => $tipe->has_serial_number ? $request->serial_number : null,
        ]);

        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Berhasil memperbarui data barang operasional.',
        ]);
    }

    public function destroy(string $id)
    {
        $barang = BarangOperasional::find($id);
        if (!$barang) {
            return response()->json(['code' => 404, 'status' => 'errors', 'message' => 'Data tidak ditemukan.']);
        }

        $barang->delete();

        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Berhasil menghapus data barang operasional.',
        ]);
    }

    /**
     * Admin mendistribusikan barang ke User (stok admin tidak dipotong)
     * Status awal: menunggu_konfirmasi (perlu di-ACC oleh penerima)
     */
    public function distribusi(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'barang_id' => 'required|exists:barang_operasionals,id',
            'user_id'   => 'required|exists:users,id',
            'jumlah'    => 'required|integer|min:1',
            'catatan'   => 'nullable|string|max:500',
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $barang = BarangOperasional::findOrFail($request->barang_id);
        $targetUser = User::findOrFail($request->user_id);

        DB::beginTransaction();
        try {
            // Catat ke riwayat transfer dengan status 'menunggu_konfirmasi'
            // Stok targetUser BELUM ditambahkan sebelum di-ACC oleh penerima
            $transfer = TransferBarangOperasional::create([
                'organization_id'       => Auth::user()->organization_id,
                'barang_operasional_id' => $barang->id,
                'pengirim_id'           => Auth::id(),
                'penerima_id'           => $targetUser->id,
                'jumlah'                => (int) $request->jumlah,
                'catatan'               => $request->catatan ?: 'Distribusi barang dari Admin',
                'tanggal_transfer'      => now(),
                'status'                => 'menunggu_konfirmasi',
            ]);

            DB::commit();

            // Kirim notifikasi WA ke penerima
            try {
                app(TransferBarangNotificationService::class)->notifyPendingTransfer($transfer);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim notifikasi WA transfer: ' . $e->getMessage());
            }

            return response()->json([
                'code'    => 200,
                'status'  => 'success',
                'message' => "Pengiriman {$request->jumlah} {$barang->satuan} ke {$targetUser->name} berhasil dibuat! Menunggu konfirmasi (ACC) dari penerima. Notifikasi WhatsApp telah dikirimkan.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code'    => 500,
                'status'  => 'errors',
                'message' => 'Gagal mendistribusikan barang: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * User yang login mengirim barang ke Teknisi (memotong/reserve stok akun user pengirim)
     * Status awal: menunggu_konfirmasi (perlu di-ACC oleh teknisi)
     */
    public function transferTeknisi(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'barang_id'  => 'required|exists:barang_operasionals,id',
            'teknisi_id' => 'required|exists:users,id|different:' . Auth::id(),
            'jumlah'     => 'required|integer|min:1',
            'catatan'    => 'nullable|string|max:500',
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $barang = BarangOperasional::findOrFail($request->barang_id);
        $teknisi = User::findOrFail($request->teknisi_id);
        $currentUser = Auth::user();

        // Cek stok akun si pengirim
        $senderStock = UserBarangOperasional::where('user_id', $currentUser->id)
            ->where('barang_operasional_id', $barang->id)
            ->first();

        if (!$senderStock || $senderStock->stok < (int) $request->jumlah) {
            $currentTotal = $senderStock ? $senderStock->stok : 0;
            return response()->json([
                'code'    => 400,
                'status'  => 'errors',
                'message' => "Stok barang Anda tidak mencukupi! Sisa stok Anda saat ini: {$currentTotal} {$barang->satuan}.",
            ]);
        }

        DB::beginTransaction();
        try {
            // Potong/reserve stok user pengirim
            $senderStock->stok -= (int) $request->jumlah;
            $senderStock->save();

            // Catat riwayat transfer dengan status 'menunggu_konfirmasi'
            // Stok teknisi BELUM bertambah sebelum di-ACC oleh teknisi
            $transfer = TransferBarangOperasional::create([
                'organization_id'       => $currentUser->organization_id,
                'barang_operasional_id' => $barang->id,
                'pengirim_id'           => $currentUser->id,
                'penerima_id'           => $teknisi->id,
                'jumlah'                => (int) $request->jumlah,
                'catatan'               => $request->catatan ?: 'Transfer barang ke teknisi',
                'tanggal_transfer'      => now(),
                'status'                => 'menunggu_konfirmasi',
            ]);

            DB::commit();

            // Kirim notifikasi WA ke teknisi penerima
            try {
                app(TransferBarangNotificationService::class)->notifyPendingTransfer($transfer);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim notifikasi WA transfer ke teknisi: ' . $e->getMessage());
            }

            return response()->json([
                'code'    => 200,
                'status'  => 'success',
                'message' => "Barang ({$request->jumlah} {$barang->satuan}) berhasil dikirim ke teknisi {$teknisi->name}! Status saat ini menunggu konfirmasi (ACC) dari teknisi. Notifikasi WhatsApp telah dikirim.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code'    => 500,
                'status'  => 'errors',
                'message' => 'Gagal mentransfer barang ke teknisi: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Konfirmasi Penerimaan Barang (ACC) oleh Penerima
     */
    public function accTransfer(Request $request, string $id)
    {
        $transfer = TransferBarangOperasional::with(['barangOperasional', 'penerima', 'pengirim'])->find($id);
        if (!$transfer) {
            return response()->json(['code' => 404, 'status' => 'errors', 'message' => 'Data transfer tidak ditemukan.']);
        }

        $authUser = Auth::user();

        // Hanya penerima atau Admin yang berhak meng-ACC
        if ($transfer->penerima_id !== $authUser->id && !$authUser->hasRole('Admin')) {
            return response()->json([
                'code'    => 403,
                'status'  => 'errors',
                'message' => 'Anda tidak memiliki hak akses untuk meng-ACC transfer ini.',
            ]);
        }

        if ($transfer->status !== 'menunggu_konfirmasi') {
            return response()->json([
                'code'    => 400,
                'status'  => 'errors',
                'message' => "Transfer ini sudah berstatus " . strtoupper($transfer->status) . " dan tidak dapat di-ACC lagi.",
            ]);
        }

        DB::beginTransaction();
        try {
            // Tambahkan stok ke akun penerima
            $receiverStock = UserBarangOperasional::firstOrCreate(
                [
                    'user_id'               => $transfer->penerima_id,
                    'barang_operasional_id' => $transfer->barang_operasional_id,
                ],
                [
                    'organization_id'       => $transfer->organization_id ?? $authUser->organization_id,
                    'stok'                  => 0,
                ]
            );

            $receiverStock->stok += (int) $transfer->jumlah;
            $receiverStock->save();

            // Update status transfer
            $transfer->update([
                'status'              => 'diterima',
                'tanggal_konfirmasi'  => now(),
                'catatan_konfirmasi'  => $request->catatan ?: 'Diterima oleh penerima',
            ]);

            DB::commit();

            // Kirim notifikasi WA balik ke pengirim
            try {
                app(TransferBarangNotificationService::class)->notifyTransferApproved($transfer);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim notifikasi WA transfer approved: ' . $e->getMessage());
            }

            return response()->json([
                'code'    => 200,
                'status'  => 'success',
                'message' => 'Penerimaan barang berhasil di-ACC! Stok telah resmi masuk ke akun Anda.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code'    => 500,
                'status'  => 'errors',
                'message' => 'Gagal meng-ACC penerimaan barang: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Tolak Penerimaan Barang oleh Penerima
     */
    public function rejectTransfer(Request $request, string $id)
    {
        $validation = Validator::make($request->all(), [
            'alasan' => 'required|string|max:500',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        if ($validation->fails()) {
            return response()->json(['code' => 400, 'errors' => $validation->errors()]);
        }

        $transfer = TransferBarangOperasional::with(['barangOperasional', 'penerima', 'pengirim'])->find($id);
        if (!$transfer) {
            return response()->json(['code' => 404, 'status' => 'errors', 'message' => 'Data transfer tidak ditemukan.']);
        }

        $authUser = Auth::user();

        // Hanya penerima atau Admin yang berhak menolak
        if ($transfer->penerima_id !== $authUser->id && !$authUser->hasRole('Admin')) {
            return response()->json([
                'code'    => 403,
                'status'  => 'errors',
                'message' => 'Anda tidak memiliki hak akses untuk menolak transfer ini.',
            ]);
        }

        if ($transfer->status !== 'menunggu_konfirmasi') {
            return response()->json([
                'code'    => 400,
                'status'  => 'errors',
                'message' => "Transfer ini sudah berstatus " . strtoupper($transfer->status) . ".",
            ]);
        }

        DB::beginTransaction();
        try {
            // Jika pengiriman bukan distribusi master admin (misal transfer antar user/teknisi yang memotong stok pengirim)
            $isFromUserStock = !str_starts_with($transfer->catatan ?? '', 'Distribusi barang dari Admin');
            if ($isFromUserStock && $transfer->pengirim_id) {
                $senderStock = UserBarangOperasional::firstOrCreate(
                    [
                        'user_id'               => $transfer->pengirim_id,
                        'barang_operasional_id' => $transfer->barang_operasional_id,
                    ],
                    [
                        'organization_id'       => $transfer->organization_id,
                        'stok'                  => 0,
                    ]
                );
                $senderStock->stok += (int) $transfer->jumlah;
                $senderStock->save();
            }

            // Update status transfer menjadi ditolak
            $transfer->update([
                'status'              => 'ditolak',
                'tanggal_konfirmasi'  => now(),
                'catatan_konfirmasi'  => $request->alasan,
            ]);

            DB::commit();

            // Kirim notifikasi WA balik ke pengirim
            try {
                app(TransferBarangNotificationService::class)->notifyTransferRejected($transfer);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim notifikasi WA transfer rejected: ' . $e->getMessage());
            }

            return response()->json([
                'code'    => 200,
                'status'  => 'success',
                'message' => 'Penerimaan barang berhasil ditolak. ' . ($isFromUserStock ? 'Stok telah dikembalikan ke pengirim.' : ''),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code'    => 500,
                'status'  => 'errors',
                'message' => 'Gagal menolak transfer: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Halaman Riwayat Transfer / Distribusi
     */
    public function riwayat(Request $request)
    {
        if ($request->ajax()) {
            return (new TransferBarangOperasionalDataTable)->get();
        }

        return view('pages.barang-operasional.riwayat.index');
    }
}
