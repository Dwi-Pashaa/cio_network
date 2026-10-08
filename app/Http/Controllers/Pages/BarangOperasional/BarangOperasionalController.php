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
use Illuminate\Support\Facades\Validator;

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

        return view('pages.barang-operasional.barang.index', compact('tipeList', 'usersList', 'teknisiList', 'organizations'));
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
            // Tambahkan stok ke akun user target
            $userStock = UserBarangOperasional::firstOrCreate(
                [
                    'user_id'               => $targetUser->id,
                    'barang_operasional_id' => $barang->id,
                ],
                [
                    'organization_id'       => $targetUser->organization_id ?? Auth::user()->organization_id,
                    'stok'                  => 0,
                ]
            );

            $userStock->stok += (int) $request->jumlah;
            $userStock->save();

            // Catat ke riwayat transfer (pengirim: Admin / Auth user)
            TransferBarangOperasional::create([
                'organization_id'       => Auth::user()->organization_id,
                'barang_operasional_id' => $barang->id,
                'pengirim_id'           => Auth::id(),
                'penerima_id'           => $targetUser->id,
                'jumlah'                => (int) $request->jumlah,
                'catatan'               => $request->catatan ?: 'Distribusi barang dari Admin',
                'tanggal_transfer'      => now(),
            ]);

            DB::commit();

            return response()->json([
                'code'    => 200,
                'status'  => 'success',
                'message' => "Berhasil memberikan {$request->jumlah} {$barang->satuan} kepada {$targetUser->name}.",
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
     * User yang login mengirim barang ke Teknisi (memotong stok akun user pengirim)
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
            // Potong stok user pengirim
            $senderStock->stok -= (int) $request->jumlah;
            $senderStock->save();

            // Tambah stok ke Teknisi penerima
            $receiverStock = UserBarangOperasional::firstOrCreate(
                [
                    'user_id'               => $teknisi->id,
                    'barang_operasional_id' => $barang->id,
                ],
                [
                    'organization_id'       => $teknisi->organization_id ?? $currentUser->organization_id,
                    'stok'                  => 0,
                ]
            );

            $receiverStock->stok += (int) $request->jumlah;
            $receiverStock->save();

            // Catat riwayat transfer
            TransferBarangOperasional::create([
                'organization_id'       => $currentUser->organization_id,
                'barang_operasional_id' => $barang->id,
                'pengirim_id'           => $currentUser->id,
                'penerima_id'           => $teknisi->id,
                'jumlah'                => (int) $request->jumlah,
                'catatan'               => $request->catatan ?: 'Transfer barang ke teknisi',
                'tanggal_transfer'      => now(),
            ]);

            DB::commit();

            return response()->json([
                'code'    => 200,
                'status'  => 'success',
                'message' => "Berhasil mengirim {$request->jumlah} {$barang->satuan} ke teknisi {$teknisi->name}. Sisa stok Anda: {$senderStock->stok} {$barang->satuan}.",
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
