<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\Pages\SpamDataTable;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\MacAddress;
use App\Models\Organization;
use App\Models\SwitchDevice;
use App\Models\UserPatchCore;
use App\Models\UserRouter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpamController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->sort ?? 10;
        $search = $request->search ?? null;

        $authUserRegencies = Auth::user()->regencie->pluck('id')->toArray();

        if ($request->ajax()) {
            return (new SpamDataTable)->get();
        }

        $switchs = SwitchDevice::with(['customer', 'typeOld', 'routerOld', 'typeNew', 'routerNew'])
            ->when($request->search, function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q) use ($search) {
                    $q->whereHas('customer', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                        ->orWhereHas('typeOld', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('routerOld', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('typeNew', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('routerNew', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('id', 'DESC')
            ->paginate($sort);


        $organizations = Organization::all();

        return view("pages.spam.index", compact("switchs", "organizations"));
    }

    public function outSpam($id)
    {
        $customers = Customer::find($id);

        $customers->update(['status' => 'active']);

        return response()->json(200);
    }

    public function reject($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return redirect()->back()->with('error', 'Data pelanggan tidak ditemukan.');
        }

        if ($customer->routers_id) {
            $userRouter = UserRouter::where('user_id', $customer->user_id)
                ->where('router_id', $customer->routers_id)
                ->first();

            if ($userRouter) {
                $userRouter->increment('total');
            }
        }

        if ($customer->patch_core_id) {
            $userPatchCore = UserPatchCore::where('user_id', $customer->user_id)
                ->where('patch_core_id', $customer->patch_core_id)
                ->first();

            if ($userPatchCore) {
                $userPatchCore->increment('total');
            }
        }

        $macAddress = $customer->mac_address;
        $organizationId = $customer->organization_id;

        $customer->delete();

        $this->syncMacAddressStatus($macAddress, $organizationId);

        return response()->json(200);
    }

    private function syncMacAddressStatus(?string $macAddress, ?int $organizationId): void
    {
        $normalizedMac = strtoupper(trim((string) $macAddress));

        if ($normalizedMac === '' || !$organizationId) {
            return;
        }

        $isStillUsed = Customer::where('organization_id', $organizationId)
            ->whereRaw('UPPER(TRIM(mac_address)) = ?', [$normalizedMac])
            ->exists();

        MacAddress::where('organization_id', $organizationId)
            ->whereRaw('UPPER(TRIM(mac_address)) = ?', [$normalizedMac])
            ->where('status', '<>', 'blocked')
            ->update(['status' => $isStillUsed ? 'used' : 'available']);
    }

    public function outSwitch($id)
    {
        $switch = SwitchDevice::find($id);
        $customer = Customer::find($switch->customer_id);

        if ($customer && $customer->routers_id && $customer->user_id) {
            UserRouter::where('user_id', $customer->user_id)
                ->where('router_id', $customer->routers_id)
                ->first()
                ?->increment('total');
        }

        if ($customer && $switch->router_new_id && $customer->user_id) {
            UserRouter::where('user_id', $customer->user_id)
                ->where('router_id', $switch->router_new_id)
                ->first()
                ?->decrement('total');
        }

        Customer::where('id', $switch->customer_id)
            ->update([
                'types_id' => $switch->type_new_id,
                'routers_id' => $switch->router_new_id,
                'mac_address' => $switch->mac_address_new,
            ]);

        $switch->update(['status' => 'active']);

        return response()->json(200);
    }
}
