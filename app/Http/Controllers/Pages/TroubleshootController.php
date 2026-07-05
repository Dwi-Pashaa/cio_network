<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\Troubleshoot\TroubleshootDataTable;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Troubleshoot;
use App\Models\TroubleshootProgress;
use App\Models\User;
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

        Troubleshoot::create([
            'customer_id'   => $request->customer_id,
            'technician_id' => $request->technician_id,
            'description'   => $request->description,
            'status'        => 'open',
            'created_by'    => Auth::id(),
        ]);

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

    public function uploadProgress(Request $request, $id, $step)
    {
        $request->validate([
            'photo'     => 'required|image|max:20480',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'address'   => 'nullable|string|max:500',
        ]);

        try {
            $troubleshoot = Troubleshoot::findOrFail($id);

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

            $stepStatuses = [
                1 => 'menuju_lokasi',
                2 => 'tiba_lokasi',
                3 => 'perbaikan',
                4 => 'done',
            ];
            $troubleshoot->update(['status' => $stepStatuses[(int) $step] ?? 'on_progress']);

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
