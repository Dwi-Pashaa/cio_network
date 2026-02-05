<?php

namespace App\Http\Controllers\Pages;

use App\DataTables\Pages\HistoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new HistoryDataTable)->get();
        }

        $user = User::all();

        return view('pages.history.index', compact('user'));
    }
}
