<?php

namespace App\Http\Controllers\Pages\Wablass;

use App\DataTables\Wablas\ReportDataTable;
use App\Http\Controllers\Controller;
use App\Models\WablasReport;
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

    public function update($id)
    {
        $report = WablasReport::find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $report->status = 'sent';
        $report->save();

        return response()->json([
            'success' => true
        ]);
    }
}
