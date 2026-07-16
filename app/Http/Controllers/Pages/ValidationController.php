<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Helpers\MacAddressHelper;
use App\Models\Customer;
use App\Models\MacAddress;
use App\Models\ProsedurSpam;
use App\Models\ProsedurSpamValidation;
use App\Models\UserPatchCore;
use App\Models\UserRouter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ValidationController extends Controller
{
    // ──────────────────────────────────────────────────────────────────
    // INDEX — halaman utama antrean + rekap
    // ──────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $user       = Auth::user();
        $levels     = config('prosedur_levels.levels', []);

        // Permission level mana yang dimiliki user login
        $myPermissions = collect($levels)
            ->filter(fn($cfg) => $user->hasPermissionTo($cfg['permission']))
            ->pluck('permission')
            ->toArray();

        if ($request->ajax()) {
            $tab  = $request->input('tab', 'queue');
            $type = $request->input('type'); // filter per prosedur_type jika diminta

            if ($tab === 'queue') {
                $query = ProsedurSpam::with([
                    'organization',
                    'customer.olt',
                    'customer.mic_radius',
                    'customer.router',
                    'customer.paket',
                    'customer.price',
                    'customer.type',
                    'customer.tipePelanggan',
                    'customer.hometown',
                    'customer.rt',
                    'customer.rw',
                    'customer.village',
                    'customer.district',
                    'customer.regencie',
                    'submittedBy',
                    'validations.validatedByUser',
                ])
                    ->forUser($user)
                    ->pending();

                // Filter per jenis prosedur jika diminta (dari tab Data Spam)
                if ($type) {
                    $query->where('prosedur_type', $type);
                }

                if (auth()->user()->hasPermissionTo('filter organization') && $request->input('organization_id')) {
                    $query->where('organization_id', $request->input('organization_id'));
                }

                $items = $query->orderBy('created_at', 'desc')
                    ->get()
                    ->filter(function ($spam) use ($myPermissions, $user) {
                        if (count($myPermissions) === 0 && !$user->can('lihat antrean prosedur')) {
                            return false;
                        }

                        // Cek apakah Level 1 (Admin) sudah disetujui
                        $level1Validation = $spam->validations->firstWhere('level', 1);
                        $isLevel1Approved = !$level1Validation || $level1Validation->status === 'approved';

                        if (!$isLevel1Approved) {
                            // Jika belum disetujui Admin, hanya user dengan permission Admin yang bisa melihat
                            $levelsConfig = config('prosedur_levels.levels', []);
                            $adminPermission = $levelsConfig[1]['permission'] ?? 'validasi prosedur level 1';
                            return $user->can($adminPermission);
                        }

                        return true;
                    });

                $items->each(function ($spam) use ($user) {
                    $spam->setAttribute('user_can_validate', !is_null($spam->getPendingCheckpointForUser($user)));
                    
                    $levelsConfig = config('prosedur_levels.levels', []);
                    $adminPermission = $levelsConfig[1]['permission'] ?? 'validasi prosedur level 1';
                    $spam->setAttribute('user_can_reject', $user->can($adminPermission));
                    
                    $payload = $spam->payload;
                    foreach (['foto_perangkat', 'foto_pembayaran'] as $fileKey) {
                        $pathKey = $fileKey . '_path';
                        if (!empty($payload[$pathKey])) {
                            $path = $payload[$pathKey];
                            if (file_exists(public_path($path))) {
                                $payload[$fileKey . '_url'] = asset($path);
                            } else {
                                $payload[$fileKey . '_url'] = asset('storage/' . $path);
                            }
                        } else {
                            $payload[$fileKey . '_url'] = null;
                        }
                    }
                    $spam->payload = $payload;
                });

                return response()->json(['data' => $items->values()]);
            }

            // Tab rekap historis (approved & rejected)
            $query = ProsedurSpam::with([
                'organization',
                'customer.olt',
                'customer.mic_radius',
                'customer.router',
                'customer.paket',
                'customer.price',
                'customer.type',
                'customer.tipePelanggan',
                'customer.hometown',
                'customer.rt',
                'customer.rw',
                'customer.village',
                'customer.district',
                'customer.regencie',
                'submittedBy',
                'rejectedBy',
                'executedBy',
                'validations.validatedByUser',
            ])
                ->forUser($user)
                ->whereIn('status', ['approved', 'rejected']);

            if ($type) {
                $query->where('prosedur_type', $type);
            }

            if (auth()->user()->hasPermissionTo('filter organization') && $request->input('organization_id')) {
                $query->where('organization_id', $request->input('organization_id'));
            }

            $perPage = $request->input('per_page', 10);
            $paginated = $query->orderBy('updated_at', 'desc')->paginate($perPage);

            $paginated->getCollection()->each(function ($spam) {
                $payload = $spam->payload;
                foreach (['foto_perangkat', 'foto_pembayaran'] as $fileKey) {
                    $pathKey = $fileKey . '_path';
                    if (!empty($payload[$pathKey])) {
                        $path = $payload[$pathKey];
                        if (file_exists(public_path($path))) {
                            $payload[$fileKey . '_url'] = asset($path);
                        } else {
                            $payload[$fileKey . '_url'] = asset('storage/' . $path);
                        }
                    } else {
                        $payload[$fileKey . '_url'] = null;
                    }
                }
                $spam->payload = $payload;
            });

            return response()->json([
                'data'         => $paginated->items(),
                'current_page' => $paginated->currentPage(),
                'last_page'    => $paginated->lastPage(),
                'total'        => $paginated->total(),
                'per_page'     => $paginated->perPage(),
                'from'         => $paginated->firstItem(),
                'to'         => $paginated->lastItem(),
            ]);
        }

        // Render view
        return view('pages.validasi.index', compact('levels', 'myPermissions'));
    }

    // ──────────────────────────────────────────────────────────────────
    // APPROVE — user memvalidasi checkpoint miliknya
    // ──────────────────────────────────────────────────────────────────

    public function approve(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $user  = Auth::user();
        $spam  = ProsedurSpam::with('validations')->findOrFail($id);

        if ($spam->status !== 'pending') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Request ini sudah tidak dalam status pending.',
            ], 422);
        }

        // Temukan checkpoint yang menjadi hak user ini
        $checkpoint = $spam->getPendingCheckpointForUser($user);

        if (!$checkpoint) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki hak validasi untuk request ini, atau checkpoint Anda sudah diselesaikan.',
            ], 403);
        }

        DB::transaction(function () use ($checkpoint, $spam, $user, $request) {
            // Tandai checkpoint ini sebagai approved
            $checkpoint->update([
                'status'       => 'approved',
                'validated_by' => $user->id,
                'validated_at' => now(),
                'notes'        => $request->input('notes'),
            ]);

            // Cek apakah semua level sudah approved
            $spam->refresh()->load('validations');

            if ($spam->isFullyApproved()) {
                // Eksekusi perubahan fisik ke database customer
                $this->executeProsedurAction($spam, $user);
            }
        });

        // Kirim notifikasi Wablass setelah transaksi sukses
        try {
            $spam->refresh()->load('validations');
            $notifier = app(\App\Services\ProsedurNotificationService::class);
            if ($spam->isFullyApproved()) {
                if ($spam->prosedur_type === 'pergantian-password') {
                    $notifier->notifyCustomerPasswordChanged($spam);
                } else {
                    $notifier->notifyTechnicianApproved($spam);
                }
            } elseif ($checkpoint->level === 1) {
                $notifier->notifyLevels($spam, [2, 3, 4]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('ValidationController (Approve): Gagal mengirim notifikasi Wablass.', [
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Validasi berhasil disimpan.',
        ]);
    }

    // ──────────────────────────────────────────────────────────────────
    // REJECT — tolak request, tidak ubah data customer
    // ──────────────────────────────────────────────────────────────────

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reject_reason' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $spam = ProsedurSpam::findOrFail($id);

        if ($spam->status !== 'pending') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Request ini sudah tidak dalam status pending.',
            ], 422);
        }

        // Pastikan user punya permission level 1 (Admin) untuk menolak
        $levelsConfig = config('prosedur_levels.levels', []);
        $adminPermission = $levelsConfig[1]['permission'] ?? 'validasi prosedur level 1';

        if (!$user->can($adminPermission)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Hanya pengguna level Admin yang dapat menolak request ini.',
            ], 403);
        }

        DB::transaction(function () use ($spam, $user, $request) {
            // Temukan checkpoint yang sedang pending saat ini (stage mana yang ditolak)
            $activeCheckpoint = $spam->validations()
                ->where('status', 'pending')
                ->orderBy('level')
                ->first();

            if ($activeCheckpoint) {
                $activeCheckpoint->update([
                    'status'       => 'rejected',
                    'validated_by' => $user->id,
                    'validated_at' => now(),
                    'notes'        => $request->input('reject_reason'),
                ]);
            }

            // Tandai seluruh prosedur sebagai rejected
            $spam->update([
                'status'        => 'rejected',
                'rejected_by'   => $user->id,
                'rejected_at'   => now(),
                'reject_reason' => $request->input('reject_reason'),
            ]);
        });

        // Kirim notifikasi Wablass penolakan setelah transaksi sukses
        try {
            app(\App\Services\ProsedurNotificationService::class)->notifyTechnicianRejected(
                $spam, 
                $user->name, 
                $request->input('reject_reason')
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('ValidationController (Reject): Gagal mengirim notifikasi Wablass.', [
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Request prosedur berhasil ditolak.',
        ]);
    }

    // ──────────────────────────────────────────────────────────────────
    // EXECUTE — dipanggil otomatis saat semua level sudah approved
    // ──────────────────────────────────────────────────────────────────

    private function executeProsedurAction(ProsedurSpam $spam, \App\Models\User $executor): void
    {
        $customer = Customer::findOrFail($spam->customer_id);
        $payload  = $spam->payload ?? [];
        $submittedBy = $spam->submitted_by;

        match ($spam->prosedur_type) {
            'pemutusan'          => $this->executePemutusan($customer, $payload, $submittedBy),
            'pergantian-layanan' => $this->executePergantianLayanan($customer, $payload, $submittedBy),
            'onu-router'         => $this->executeOnuRouter($customer, $payload, $submittedBy),
            'pergantian-password' => $this->executePergantianPassword($customer, $payload, $submittedBy),
            default              => null,
        };

        $spam->update([
            'status'      => 'approved',
            'executed_by' => $executor->id,
            'executed_at' => now(),
        ]);
    }

    private function executePemutusan(Customer $customer, array $payload, int $submittedBy): void
    {
        $technician = \App\Models\User::find($submittedBy);
        $orgId = $technician ? $technician->organization_id : null;

        // Kembalikan stok router ke user lapangan yang mengajukan (submittedBy)
        if ($customer->routers_id) {
            $userRouter = UserRouter::where('user_id', $submittedBy)
                ->where('router_id', $customer->routers_id)
                ->first();
            if ($userRouter) {
                $userRouter->increment('total');
            } else {
                UserRouter::create([
                    'user_id' => $submittedBy,
                    'router_id' => $customer->routers_id,
                    'total' => 1,
                    'organization_id' => $orgId,
                ]);
            }
        }

        // Kembalikan stok patch core ke user lapangan yang mengajukan (submittedBy)
        if ($customer->patch_core_id) {
            $userPatchCore = UserPatchCore::where('user_id', $submittedBy)
                ->where('patch_core_id', $customer->patch_core_id)
                ->first();
            if ($userPatchCore) {
                $userPatchCore->increment('total');
            } else {
                UserPatchCore::create([
                    'user_id' => $submittedBy,
                    'patch_core_id' => $customer->patch_core_id,
                    'total' => 1,
                    'organization_id' => $orgId,
                ]);
            }
        }

        $macAddress     = $customer->mac_address;
        $organizationId = $customer->organization_id;

        // Set who updated/triggered the deletion
        $customer->user_update_id = $submittedBy;
        $customer->save();

        // Soft delete — data tetap ada di DB, hanya di-hide via deleted_at
        $customer->delete();

        // Sinkronisasi status MAC Address setelah pemutusan
        $this->syncMacAddressStatus($macAddress, $organizationId);
    }

    private function executePergantianLayanan(Customer $customer, array $payload, int $submittedBy): void
    {
        $serviceType = $payload['service_type'] ?? null;

        if ($serviceType === 'voucher-ke-pppoe') {
            // Voucher → PPPoE: isi semua field dari payload, ubah tipe layanan ke PPPoE
            $updateData = ['types_id' => 1, 'user_update_id' => $submittedBy]; // PPPOE

            if (!empty($payload['paket_id']))        $updateData['paket_id']       = $payload['paket_id'];
            if (!empty($payload['price_id']))        $updateData['price_id']       = $payload['price_id'];
            if (!empty($payload['mic_radius_id']))   $updateData['mic_radius_id']  = $payload['mic_radius_id'];
            if (!empty($payload['pppoe_username']))  $updateData['pppoe_username'] = $payload['pppoe_username'];
            if (!empty($payload['pppoe_password']))  $updateData['pppoe_password'] = $payload['pppoe_password'];
            if (!empty($payload['name_wifi']))       $updateData['name_wifi']      = $payload['name_wifi'];
            if (!empty($payload['password_wifi']))   $updateData['password_wifi']  = $payload['password_wifi'];

            Customer::where('id', $customer->id)->update($updateData);

        } elseif ($serviceType === 'pppoe-ke-voucher') {
            // PPPoE → Voucher: ubah tipe layanan ke Voucher, kosongkan semua field terkait PPPoE & paket
            Customer::where('id', $customer->id)->update([
                'types_id'       => 2, // VOUCHER
                'pppoe_username' => null,
                'pppoe_password' => null,
                'name_wifi'      => null,
                'password_wifi'  => null,
                'paket_id'       => null,
                'price_id'       => null,
                'mic_radius_id'  => null,
                'user_update_id' => $submittedBy,
            ]);
        }

        if (!empty($payload['router_new_id'])) {
            if ($customer->routers_id && $customer->user_id) {
                UserRouter::where('user_id', $customer->user_id)
                    ->where('router_id', $customer->routers_id)
                    ->first()
                    ?->increment('total');
            }
            Customer::where('id', $customer->id)->update(['routers_id' => $payload['router_new_id']]);
            if ($customer->user_id) {
                UserRouter::where('user_id', $customer->user_id)
                    ->where('router_id', $payload['router_new_id'])
                    ->first()
                    ?->decrement('total');
            }
        }

        if (!empty($payload['patch_core_new_id'])) {
            if ($customer->patch_core_id && $customer->user_id) {
                UserPatchCore::where('user_id', $customer->user_id)
                    ->where('patch_core_id', $customer->patch_core_id)
                    ->first()
                    ?->increment('total');
            }
            Customer::where('id', $customer->id)->update(['patch_core_id' => $payload['patch_core_new_id']]);
            if ($customer->user_id) {
                UserPatchCore::where('user_id', $customer->user_id)
                    ->where('patch_core_id', $payload['patch_core_new_id'])
                    ->first()
                    ?->decrement('total');
            }
        }
    }

    private function executeOnuRouter(Customer $customer, array $payload, int $submittedBy): void
    {
        $oldMac     = $customer->mac_address;
        $updateData = [];

        if (!empty($payload['mac_address_new'])) {
            // Normalize MAC baru sebelum disimpan (bug fix: format konsisten)
            $updateData['mac_address'] = MacAddressHelper::normalize($payload['mac_address_new']);
        }

        if (!empty($payload['router_new_id'])) {
            // Kembalikan stok router lama ke user lapangan
            if ($customer->routers_id && $customer->user_id) {
                $userRouter = UserRouter::where('user_id', $customer->user_id)
                    ->where('router_id', $customer->routers_id)
                    ->first();
                $userRouter?->increment('total');
            }

            $updateData['routers_id'] = $payload['router_new_id'];

            // Kurangi stok router baru
            if ($customer->user_id) {
                $userRouter = UserRouter::where('user_id', $customer->user_id)
                    ->where('router_id', $payload['router_new_id'])
                    ->first();
                $userRouter?->decrement('total');
            }
        }

        if (!empty($payload['patch_core_new_id'])) {
            if ($customer->patch_core_id && $customer->user_id) {
                $userPatchCore = UserPatchCore::where('user_id', $customer->user_id)
                    ->where('patch_core_id', $customer->patch_core_id)
                    ->first();
                $userPatchCore?->increment('total');
            }

            $updateData['patch_core_id'] = $payload['patch_core_new_id'];

            if ($customer->user_id) {
                $userPatchCore = UserPatchCore::where('user_id', $customer->user_id)
                    ->where('patch_core_id', $payload['patch_core_new_id'])
                    ->first();
                $userPatchCore?->decrement('total');
            }
        }

        $updateData['user_update_id'] = $submittedBy;
        Customer::where('id', $customer->id)->update($updateData);

        // Sinkronisasi status MAC Address: bebaskan MAC lama, tandai MAC baru sebagai digunakan
        if (!empty($payload['mac_address_new'])) {
            $this->syncMacAddressStatus($oldMac, $customer->organization_id);
            $this->markMacAddressUsed($payload['mac_address_new'], $customer->organization_id);
        }
    }

    private function executePergantianPassword(Customer $customer, array $payload, int $submittedBy): void
    {
        $updateData = [
            'password_wifi'  => $payload['password_wifi'],
            'user_update_id' => $submittedBy,
        ];

        if (!empty($payload['name_wifi'])) {
            $updateData['name_wifi'] = $payload['name_wifi'];
        }

        Customer::where('id', $customer->id)->update($updateData);
    }

    private function syncMacAddressStatus(?string $macAddress, ?int $organizationId): void
    {
        $normalizedMac = strtoupper(trim((string) $macAddress));

        if ($normalizedMac === '' || !$organizationId) {
            return;
        }

        $isStillUsed = Customer::where('organization_id', $organizationId)
            ->whereRaw('UPPER(TRIM(mac_address)) = ?', [$normalizedMac])
            ->where('status', '!=', 'non-aktif')
            ->exists();

        MacAddress::where('organization_id', $organizationId)
            ->whereRaw('UPPER(TRIM(mac_address)) = ?', [$normalizedMac])
            ->where('status', '<>', 'blocked')
            ->update(['status' => $isStillUsed ? 'used' : 'available']);
    }

    private function markMacAddressUsed(?string $macAddress, ?int $organizationId): void
    {
        $normalizedMac = strtoupper(trim((string) $macAddress));

        if ($normalizedMac === '' || !$organizationId) {
            return;
        }

        MacAddress::where('organization_id', $organizationId)
            ->whereRaw('UPPER(TRIM(mac_address)) = ?', [$normalizedMac])
            ->where('status', '<>', 'blocked')
            ->update(['status' => 'used']);
    }
}
