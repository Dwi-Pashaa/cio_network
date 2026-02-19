<?php

namespace App\Http\Controllers\Pages\Wablass;

use App\DataTables\Wablas\ReportDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return (new ReportDataTable)->get();
        }

        return view('pages.wablass.report.index');
    }
}
