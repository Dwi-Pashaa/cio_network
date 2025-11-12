<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user_id ?? null;
        $start   = $request->start ?? null;
        $end     = $request->end ?? null;

        $history = Customer::query()
            ->when($user_id, function ($query, $user_id) {
                $query->where('user_id', $user_id);
            })
            ->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->when($start && !$end, function ($query) use ($start) {
                $query->whereDate('created_at', '>=', $start);
            })
            ->when(!$start && $end, function ($query) use ($end) {
                $query->whereDate('created_at', '<=', $end);
            })
            ->when(!$user_id && !$start && !$end, function ($query) {
                $query->whereRaw('1 = 0');
            })
            ->get();

        $user = User::all();

        return view('pages.history.index', compact('history', 'user'));
    }
}
