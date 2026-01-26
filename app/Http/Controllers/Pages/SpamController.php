<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Customer;
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

        $customers = Customer::with(['router', 'type', 'hometown', 'rt', 'rw', 'village', 'district', 'regencie', 'vlan', 'odc', 'odp', 'olt'])
            ->whereIn('regencies_id', $authUserRegencies)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhereHas('router', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('type', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('hometown', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('rt', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('rw', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('village', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('district', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('regencie', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('vlan', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('odc', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('odp', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('olt', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            })
            ->where('status', 'spam')
            ->paginate($sort);

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


        return view("pages.spam.index", compact("customers", "switchs"));
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

        $customer->delete();

        return response()->json(200);
    }

    public function outSwitch($id)
    {
        $switch = SwitchDevice::find($id);

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
