<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerExport implements FromView
{
    public function view(): View
    {
        return view('exports.customer', [
            'customers' => Customer::with(['router', 'type', 'hometown', 'rt', 'rw', 'village', 'district', 'regencie', 'vlan', 'odc', 'odp', 'olt', 'paket', 'price'])
                ->orderBy('id', 'DESC')
                ->get()
        ]);
    }
}
