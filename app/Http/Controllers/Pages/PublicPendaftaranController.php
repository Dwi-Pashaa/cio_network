<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\HomeTown;
use App\Models\Organization;
use App\Models\Pages;
use App\Models\Pendaftaran;
use App\Models\Persetujuan;
use App\Models\Price;
use App\Models\RegistrationSequence;
use App\Models\Type;
use App\Models\Village;
use App\Models\User;
use App\Services\FonteMessagingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PublicPendaftaranController extends Controller
{
    public function search(Request $request)
    {
        $kode = $request->query('cstmrid');

        if (!$kode) {
            if ($request->query('format') === 'json') {
                return response()->json(['status' => 'error', 'message' => 'Kode pendaftaran tidak ditemukan']);
            }
            return view('pages.public-pendaftaran.search');
        }

        $pendaftaran = Pendaftaran::with(['tipeLayanan', 'hometown', 'village', 'pages', 'assignedTo'])
            ->where('kode', $kode)
            ->first();

        if ($pendaftaran) {
            if ($request->query('format') === 'json') {
                return response()->json([
                    'status' => 'success',
                    'type'   => 'pendaftaran',
                    'data'   => [
                        'kode'           => $pendaftaran->kode,
                        'nama'           => $pendaftaran->nama,
                        'no_telepon'     => $pendaftaran->no_telepon,
                        'layanan'        => $pendaftaran->tipeLayanan?->name ?? '-',
                        'lokasi'         => ($pendaftaran->village?->name ?? '-') . ($pendaftaran->hometown ? ' - ' . $pendaftaran->hometown->name : ''),
                        'tanggal_daftar' => $pendaftaran->created_at->format('d/m/Y H:i') . ' WIB',
                        'status'         => $pendaftaran->status,
                        'assigned_to'    => $pendaftaran->assignedTo?->name,
                        'assigned_at'    => $pendaftaran->assigned_at ? $pendaftaran->assigned_at->format('d/m/Y H:i') : null,
                    ],
                ]);
            }
            return view('pages.public-pendaftaran.search', compact('pendaftaran'));
        }

        $customer = Customer::where('uuid', $kode)->first();

        if ($request->query('format') === 'json') {
            if ($customer) {
                return response()->json([
                    'status' => 'success',
                    'type'   => 'customer',
                    'data'   => [
                        'uuid' => $customer->uuid,
                        'name' => $customer->name,
                        'telp' => $customer->telp,
                    ],
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Kode pendaftaran tidak ditemukan',
                'kode'   => $kode,
            ]);
        }

        return view('pages.public-pendaftaran.search', compact('kode', 'customer'));
    }
    public function index()
    {
        $submissionToken = session()->get('pendaftaran_token');
        if (!$submissionToken) {
            $submissionToken = Str::random(32);
            session()->put('pendaftaran_token', $submissionToken);
        }

        $persetujuan = Persetujuan::where('is_active', true)->first();
        $tipeLayanan = Type::where('status', '0')->get();
        $kampung = HomeTown::select(['id', 'name'])->get();
        $desa = Village::select(['id', 'name'])->get();
        $tipePembayaran = Price::where('is_public', true)
            ->select(['id', 'name', 'description', 'use_bukti_bayar'])
            ->get();

        return view('pages.public-pendaftaran.wizard', compact(
            'persetujuan',
            'tipeLayanan',
            'kampung',
            'desa',
            'tipePembayaran',
            'submissionToken'
        ));
    }

    public function getPages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'villages_id'  => 'required|exists:villages,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $query = Pages::with([
            'village:id,name', 
            'hometown:id,name',
            'pakets' => function($q) {
                $q->where('is_public', true);
            },
        ])->where('villages_id', $request->villages_id);

        if ($request->filled('hometowns_id')) {
            $query->where('hometowns_id', $request->hometowns_id);
        }

        $pages = $query->get()->map(function ($page) {
            $desaName = $page->village ? $page->village->name : '';
            $kampungName = $page->hometown ? $page->hometown->name : '';
            $pageName = $page->name;

            if ($desaName && $kampungName) {
                $displayName = "{$pageName} (Desa {$desaName} - Kampung {$kampungName})";
            } elseif ($desaName) {
                $displayName = "{$pageName} (Desa {$desaName})";
            } elseif ($kampungName) {
                $displayName = "{$pageName} (Kampung {$kampungName})";
            } else {
                $displayName = $pageName;
            }

            return [
                'id'     => $page->id,
                'name'   => $displayName,
                'pakets' => $page->pakets->map(function($p) {
                    return ['id' => $p->id, 'name' => $p->name];
                }),
            ];
        });

        return response()->json(['status' => 'success', 'data' => $pages]);
    }

    public function store(Request $request)
    {
        $sessionToken = session()->pull('pendaftaran_token');
        if (!$sessionToken || $request->submission_token !== $sessionToken) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Token pendaftaran tidak valid. Silakan refresh halaman dan coba lagi.',
            ], 422);
        }

        $tipeLayananObj = Type::find($request->tipe_layanan_id);
        $isPppoe = $tipeLayananObj && (stripos($tipeLayananObj->name, 'pppoe') !== false);

        $rules = [
            'nama'             => 'required|string|max:255',
            'email'            => 'required|email|max:255|unique:pendaftaran,email',
            'no_telepon'       => 'required|string|max:20',
            'tipe_layanan_id'  => 'required|exists:customer_types,id',
            'hometowns_id'     => 'required|exists:home_towns,id',
            'villages_id'      => 'required|exists:villages,id',
            'pages_id'         => 'required|exists:pages,id',
            'paket_id'         => 'nullable|exists:paket,id',
            'price_id'         => 'nullable|exists:price,id',
            'persetujuan_id'   => 'required|exists:persetujuan,id',
            'tanda_tangan_customer' => 'required|string',
            'name_wifi'        => $isPppoe ? 'required|string|max:255' : 'nullable|string|max:255',
            'password_wifi'    => $isPppoe ? 'required|string|min:4|max:255' : 'nullable|string|max:255',
        ];

        $messages = [
            'name_wifi.required'     => 'Nama WiFi (SSID) wajib diisi untuk layanan PPPoE.',
            'password_wifi.required' => 'Password WiFi wajib diisi untuk layanan PPPoE.',
            'password_wifi.min'      => 'Password WiFi minimal 4 karakter.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $kode = DB::transaction(function () {
            $seq = RegistrationSequence::where('prefix', 'CSTMR')
                ->lockForUpdate()
                ->firstOrFail();

            $seq->increment('last_number');
            $nextNumber = $seq->last_number;

            return 'CSTMR' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });

        $organization = Organization::first();
        $organizationId = $organization ? $organization->id : 1;

        $ttdBase64 = $request->tanda_tangan_customer;
        $ttdImage = $this->saveSignatureImage($ttdBase64, $request->nama, $kode);

        $buktiBayar = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $fileName = $kode . '_bukti.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('uploads/bukti_bayar', $fileName, 'public');
            $buktiBayar = $filePath;
        }

        $pendaftaran = Pendaftaran::create([
            'kode'                   => $kode,
            'nama'                   => $request->nama,
            'email'                  => $request->email,
            'no_telepon'             => $request->no_telepon,
            'tipe_layanan_id'        => $request->tipe_layanan_id,
            'name_wifi'              => $isPppoe ? $request->name_wifi : null,
            'password_wifi'          => $isPppoe ? $request->password_wifi : null,
            'hometowns_id'           => $request->hometowns_id,
            'villages_id'            => $request->villages_id,
            'pages_id'               => $request->pages_id,
            'paket_id'               => $request->paket_id,
            'price_id'               => $request->price_id,
            'bukti_pembayaran'       => $buktiBayar,
            'organization_id'        => $organizationId,
            'tanda_tangan_customer'  => $ttdImage,
            'persetujuan_id'         => $request->persetujuan_id,
            'status'                 => 'pending',
        ]);

        $this->notifyDataEntry($pendaftaran, $tipeLayananObj);

        $newToken = Str::random(32);
        session()->put('pendaftaran_token', $newToken);

        return response()->json([
            'status' => 'success',
            'data'   => [
                'kode'           => $pendaftaran->kode,
                'new_token'      => $newToken,
            ],
            'message' => 'Pendaftaran berhasil!',
        ]);
    }

    public function download($kode)
    {
        $pendaftaran = Pendaftaran::with(['tipeLayanan', 'hometown', 'village', 'pages', 'persetujuan', 'paket', 'price'])
            ->where('kode', $kode)
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.persetujuan-pendaftaran', compact('pendaftaran'));

        return $pdf->stream("PERJANJIAN-BERLANGGANAN-JASA-TELEKOMUNIKASI-{$kode}.pdf");
    }

    private function notifyDataEntry($pendaftaran, $tipeLayanan)
    {
        $users = User::role('Data Entry')->get();
        if ($users->isEmpty()) return;

        $village = $pendaftaran->village?->name ?? '-';
        $kampung = $pendaftaran->hometown?->name ?? '-';

        $message = "📋 *Pendaftaran Baru*\n\n"
            . "Kode: {$pendaftaran->kode}\n"
            . "Nama: {$pendaftaran->nama}\n"
            . "No. Telepon: {$pendaftaran->no_telepon}\n"
            . "Layanan: {$tipeLayanan->name}\n"
            . "Lokasi: {$village} - {$kampung}\n"
            . "Status: Pending\n\n"
            . "Link Surat Perjanjian:\n"
            . route('public.pendaftaran.download', $pendaftaran->kode);

        $fonte = app(FonteMessagingService::class);

        foreach ($users as $user) {
            if ($user->telp) {
                $fonte->sendMessage($user->telp, $message);
            }
        }
    }

    private function saveSignatureImage($base64Data, $nama, $kode)
    {
        $folder = public_path('signatures/customers');
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $slugNama = Str::slug($nama);
        $base64Data = preg_replace('/^data:image\/\w+;base64,/', '', $base64Data);
        $imageData = base64_decode($base64Data);
        $filename = "{$slugNama}.png";
        $filePath = "{$folder}/{$filename}";
        file_put_contents($filePath, $imageData);

        return "signatures/customers/{$filename}";
    }
}
