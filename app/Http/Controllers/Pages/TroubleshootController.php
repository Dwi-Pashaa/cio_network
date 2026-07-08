<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\Troubleshoot\TroubleshootDataTable;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Troubleshoot;
use App\Models\TroubleshootProgress;
use App\Models\User;
use App\Services\FonteMessagingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TroubleshootController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new TroubleshootDataTable)->get();
        }

        return view('pages.troubleshoot.index');
    }

    public function create()
    {
        $technicians = User::where('organization_id', Auth::user()->organization_id)->get();

        return view('pages.troubleshoot.create', compact('technicians'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'technician_id' => 'required|exists:users,id',
            'description'   => 'required|string',
        ]);

        $troubleshoot = Troubleshoot::create([
            'customer_id'   => $request->customer_id,
            'technician_id' => $request->technician_id,
            'description'   => $request->description,
            'status'        => 'open',
            'created_by'    => Auth::id(),
        ]);

        $troubleshoot->load(['customer', 'technician', 'creator']);

        $this->sendNewTicketNotification($troubleshoot);

        return redirect()->route('troubleshoot.index')->with('success', 'Ticket troubleshoot berhasil dibuat.');
    }

    public function show($id)
    {
        $troubleshoot = Troubleshoot::with(['customer', 'technician', 'creator'])->findOrFail($id);

        return view('pages.troubleshoot.show', compact('troubleshoot'));
    }

    public function edit($id)
    {
        if (!request()->ajax()) {
            return redirect()->route('troubleshoot.index');
        }

        $troubleshoot = Troubleshoot::with(['customer', 'technician'])->findOrFail($id);
        $technicians = User::where('organization_id', Auth::user()->organization_id)->get();

        return response()->json([
            'troubleshoot' => $troubleshoot,
            'technicians'  => $technicians,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'technician_id' => 'required|exists:users,id',
            'description'   => 'required|string',
            'status'        => 'required|in:open,on_progress,done,cancelled',
            'notes'         => 'nullable|string',
        ]);

        $troubleshoot = Troubleshoot::findOrFail($id);
        $troubleshoot->update([
            'technician_id' => $request->technician_id,
            'description'   => $request->description,
            'status'        => $request->status,
            'notes'         => $request->notes,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Ticket berhasil diupdate.']);
    }

    public function destroy($id)
    {
        if (!request()->ajax()) {
            return redirect()->route('troubleshoot.index');
        }

        $troubleshoot = Troubleshoot::find($id);

        if (!$troubleshoot) {
            return response()->json(['message' => 'Ticket tidak ditemukan.'], 404);
        }

        $troubleshoot->delete();

        return response()->json(['status' => 'success', 'message' => 'Ticket berhasil dihapus.']);
    }

    public function searchCustomer(Request $request)
    {
        $mac = trim($request->query('mac'));

        if (!$mac) {
            return response()->json(['status' => 'error', 'message' => 'MAC Address tidak boleh kosong.'], 400);
        }

        $normalizedMac = strtoupper(str_replace('-', ':', $mac));

        $customer = Customer::with(['paket', 'type', 'tipePelanggan', 'price', 'vlan', 'hometown', 'rt', 'rw', 'village', 'district', 'regencie'])
            ->where('organization_id', Auth::user()->organization_id)
            ->where('mac_address', $normalizedMac)
            ->first();

        if (!$customer) {
            return response()->json(['status' => 'error', 'message' => 'Pelanggan dengan MAC Address tersebut tidak ditemukan.'], 404);
        }

        $addressParts = [];
        if (!empty($customer->hometown->name)) $addressParts[] = 'Kampung ' . $customer->hometown->name;
        if (!empty($customer->rt->name)) $addressParts[] = 'RT ' . $customer->rt->name;
        if (!empty($customer->rw->name)) $addressParts[] = 'RW ' . $customer->rw->name;
        if (!empty($customer->village->name)) $addressParts[] = 'Desa ' . $customer->village->name;
        if (!empty($customer->district->name)) $addressParts[] = 'Kec. ' . $customer->district->name;
        if (!empty($customer->regencie->name)) $addressParts[] = 'Kab. ' . $customer->regencie->name;
        $fullAddress = count($addressParts) > 0 ? implode(', ', $addressParts) : '-';

        return response()->json([
            'status' => 'success',
            'data' => [
                'id'              => $customer->id,
                'uuid'            => $customer->uuid ?? $customer->id,
                'name'            => $customer->name,
                'mac_address'     => $customer->mac_address,
                'tipe_layanan'    => $customer->type->name ?? $customer->tipePelanggan->name ?? '-',
                'tipe_pembayaran' => $customer->price->name ?? '-',
                'paket'           => $customer->paket->name ?? '-',
                'alamat'          => $fullAddress,
                'status'          => $customer->status ?? 'unknown',
                'latitude'        => $customer->latitude,
                'longitude'       => $customer->longitude,
            ],
        ]);
    }

    public function tracking($id)
    {
        $troubleshoot = Troubleshoot::with(['customer', 'technician', 'progress'])->findOrFail($id);

        if ($troubleshoot->status === 'done') {
            return redirect()->route('troubleshoot.show', $id)
                ->with('error', 'Ticket sudah selesai, tidak bisa diakses tracking.');
        }

        return view('pages.troubleshoot.tracking', compact('troubleshoot'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,on_progress,done,cancelled',
            'notes'  => 'nullable|string',
        ]);

        $troubleshoot = Troubleshoot::findOrFail($id);
        $troubleshoot->update([
            'status' => $request->status,
            'notes'  => $request->notes,
        ]);

        if ($request->status === 'done') {
            return redirect()->route('troubleshoot.index')->with('success', 'Ticket berhasil diselesaikan.');
        }

        return redirect()->back()->with('success', 'Status ticket berhasil diupdate.');
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();
        $user->update([
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json(['status' => 'success']);
    }

    public function getTrackingData($id)
    {
        $troubleshoot = Troubleshoot::with(['customer', 'technician', 'progress'])->findOrFail($id);

        return response()->json([
            'customer' => [
                'latitude'  => $troubleshoot->customer->latitude,
                'longitude' => $troubleshoot->customer->longitude,
                'name'      => $troubleshoot->customer->name,
            ],
            'technician' => [
                'latitude'  => $troubleshoot->technician->latitude,
                'longitude' => $troubleshoot->technician->longitude,
                'name'      => $troubleshoot->technician->name,
            ],
            'troubleshoot' => [
                'id'     => $troubleshoot->id,
                'status' => $troubleshoot->status,
            ],
        ]);
    }

    public function detail($id)
    {
        if (!request()->ajax()) {
            return redirect()->route('troubleshoot.index');
        }

        $troubleshoot = Troubleshoot::with(['customer', 'technician', 'progress'])->find($id);

        if (!$troubleshoot) {
            return response()->json(['message' => 'Ticket tidak ditemukan.'], 404);
        }

        $steps = [
            1 => 'Menuju Lokasi',
            2 => 'Tiba di Lokasi',
            3 => 'Perbaikan',
            4 => 'Selesai',
        ];

        $progressData = [];
        foreach ($steps as $step => $label) {
            $progress = $troubleshoot->progress->firstWhere('step', $step);
            $progressData[] = [
                'step'       => $step,
                'label'      => $label,
                'status'     => $progress ? $progress->status : 'pending',
                'photo'      => $progress && $progress->photo ? asset($progress->photo) : null,
                'latitude'   => $progress ? $progress->latitude : null,
                'longitude'  => $progress ? $progress->longitude : null,
                'address'    => $progress ? $progress->address : null,
                'updated_at' => $progress ? $progress->updated_at->format('d/m/Y H:i:s') : null,
            ];
        }

        return response()->json([
            'troubleshoot' => $troubleshoot,
            'progress'     => $progressData,
        ]);
    }

    private function sendNewTicketNotification(Troubleshoot $troubleshoot): void
    {
        $customer = $troubleshoot->customer;
        $technician = $troubleshoot->technician;
        $creator = $troubleshoot->creator;

        if (!$technician || empty($technician->telp)) {
            return;
        }

        $customerName = $customer ? $customer->name : '-';
        $customerMac = $customer ? $customer->mac_address : '-';
        $customerLayanan = $customer ? ($customer->type->name ?? $customer->tipePelanggan->name ?? '-') : '-';
        $customerPaket = $customer && $customer->paket ? $customer->paket->name : '-';

        $addressParts = [];
        if ($customer) {
            $customer->loadMissing(['hometown', 'rt', 'rw', 'village', 'district', 'regencie']);
            if (!empty($customer->hometown->name)) $addressParts[] = 'Kampung ' . $customer->hometown->name;
            if (!empty($customer->rt->name)) $addressParts[] = 'RT ' . $customer->rt->name;
            if (!empty($customer->rw->name)) $addressParts[] = 'RW ' . $customer->rw->name;
            if (!empty($customer->village->name)) $addressParts[] = 'Desa ' . $customer->village->name;
            if (!empty($customer->district->name)) $addressParts[] = 'Kec. ' . $customer->district->name;
            if (!empty($customer->regencie->name)) $addressParts[] = 'Kab. ' . $customer->regencie->name;
        }
        $fullAddress = count($addressParts) > 0 ? implode(', ', $addressParts) : '-';

        $trackingUrl = $customer && $customer->latitude && $customer->longitude
            ? route('troubleshoot.tracking', $troubleshoot->id)
            : route('troubleshoot.index');

        $creatorName = $creator ? $creator->name : '-';
        $createdAt = $troubleshoot->created_at ? $troubleshoot->created_at->format('d/m/Y H:i:s') : '-';

        $message = "*🔧 TICKET TROUBLESHOOT BARU*\n\n"
            . "*ID Ticket:* #{$troubleshoot->id}\n"
            . "*Status:* Open\n\n"
            . "*📋 Data Pelanggan:*\n"
            . "- Nama: {$customerName}\n"
            . "- MAC Address: {$customerMac}\n"
            . "- Layanan: {$customerLayanan}\n"
            . "- Paket: {$customerPaket}\n"
            . "- Alamat: {$fullAddress}\n\n"
            . "*📝 Deskripsi:*\n{$troubleshoot->description}\n\n"
            . "*👤 Dibuat Oleh:* {$creatorName}\n"
            . "*📅 Tanggal:* {$createdAt}\n\n"
            . "🔗 *Link Tracking:* {$trackingUrl}\n\n"
            . "Silakan menuju lokasi pelanggan untuk melakukan pengecekan dan perbaikan.";

        app(FonteMessagingService::class)->sendMessage($technician->telp, $message);
    }

    private function sendProgressNotification(Troubleshoot $troubleshoot, int $step, string $stepLabel, string $photoUrl, ?string $address): void
    {
        $customer = $troubleshoot->customer;
        $technician = $troubleshoot->technician;

        try {
            $kelolaUsers = User::permission('kelola troubleshoot')->get();
        } catch (\Throwable $e) {
            return;
        }
        if ($kelolaUsers->isEmpty()) {
            return;
        }

        $customerName = $customer ? $customer->name : '-';
        $customerMac = $customer ? $customer->mac_address : '-';
        $customerLayanan = $customer ? ($customer->type->name ?? $customer->tipePelanggan->name ?? '-') : '-';
        $customerPaket = $customer && $customer->paket ? $customer->paket->name : '-';
        $technicianName = $technician ? $technician->name : '-';
        $location = $address ?: '-';

        $addressParts = [];
        if ($customer) {
            $customer->loadMissing(['hometown', 'rt', 'rw', 'village', 'district', 'regencie']);
            if (!empty($customer->hometown->name)) $addressParts[] = 'Kampung ' . $customer->hometown->name;
            if (!empty($customer->rt->name)) $addressParts[] = 'RT ' . $customer->rt->name;
            if (!empty($customer->rw->name)) $addressParts[] = 'RW ' . $customer->rw->name;
            if (!empty($customer->village->name)) $addressParts[] = 'Desa ' . $customer->village->name;
            if (!empty($customer->district->name)) $addressParts[] = 'Kec. ' . $customer->district->name;
            if (!empty($customer->regencie->name)) $addressParts[] = 'Kab. ' . $customer->regencie->name;
        }
        $fullAddress = count($addressParts) > 0 ? implode(', ', $addressParts) : '-';

        $statusLabel = match ($troubleshoot->status) {
            'menuju_lokasi' => 'Menuju Lokasi',
            'tiba_lokasi' => 'Tiba di Lokasi',
            'perbaikan' => 'Perbaikan',
            'done' => 'Selesai',
            default => str_replace('_', ' ', ucfirst($troubleshoot->status)),
        };

        $detailUrl = route('troubleshoot.index');

        $message = "*📸 UPDATE PROGRESS TROUBLESHOOT*\n\n"
            . "*ID Ticket:* #{$troubleshoot->id}\n"
            . "*Status:* {$statusLabel}\n\n"
            . "*✅ Langkah {$step}: {$stepLabel}* — Selesai\n\n"
            . "*📋 Data Pelanggan:*\n"
            . "- Nama: {$customerName}\n"
            . "- MAC Address: {$customerMac}\n"
            . "- Layanan: {$customerLayanan}\n"
            . "- Paket: {$customerPaket}\n"
            . "- Alamat: {$fullAddress}\n\n"
            . "*👤 Teknisi:* {$technicianName}\n"
            . "*📍 Lokasi:* {$location}\n"
            . "*📸 Foto:* {$photoUrl}\n\n"
            . "🔗 *Link Detail:* {$detailUrl}";

        $messagingService = app(FonteMessagingService::class);
        foreach ($kelolaUsers as $user) {
            if (!empty($user->telp)) {
                $messagingService->sendMessage($user->telp, $message);
            }
        }
    }

    public function uploadProgress(Request $request, $id, $step)
    {
        $request->validate([
            'photo'     => 'required|image|max:20480',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'address'   => 'nullable|string|max:500',
        ]);

        try {
            $troubleshoot = Troubleshoot::find($id);

            if (!$troubleshoot) {
                return response()->json(['status' => 'error', 'message' => 'Ticket tidak ditemukan.'], 404);
            }

            $dir = public_path("troubleshoot/progress/{$id}");
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }

            if (!is_dir($dir) || !is_writable($dir)) {
                throw new \RuntimeException('Direktori upload tidak dapat ditulis');
            }

            $filename = "step_{$step}_" . time() . ".jpg";
            $request->file('photo')->move($dir, $filename);
            $photoPath = "troubleshoot/progress/{$id}/{$filename}";

            TroubleshootProgress::updateOrCreate(
                ['troubleshoot_id' => $id, 'step' => $step],
                [
                    'photo'     => $photoPath,
                    'latitude'  => $request->latitude,
                    'longitude' => $request->longitude,
                    'address'   => $request->address,
                    'status'    => 'completed',
                ]
            );

            $stepLabels = [
                1 => 'Menuju Lokasi',
                2 => 'Tiba di Lokasi',
                3 => 'Perbaikan',
                4 => 'Selesai',
            ];
            $stepStatuses = [
                1 => 'menuju_lokasi',
                2 => 'tiba_lokasi',
                3 => 'perbaikan',
                4 => 'done',
            ];
            $troubleshoot->update(['status' => $stepStatuses[(int) $step] ?? 'on_progress']);

            $troubleshoot->load(['customer', 'technician']);
            $this->sendProgressNotification($troubleshoot, (int) $step, $stepLabels[(int) $step] ?? "Langkah {$step}", asset($photoPath), $request->address);

            return response()->json([
                'status'    => 'success',
                'photo_url' => asset($photoPath),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
