<?php

namespace App\DataTables\Wablas;

use App\Models\WablasReport;
use Carbon\Carbon;

class ReportDataTable
{
    public function get()
    {
        $request = request();

        $start  = intval($request->start ?? 0);
        $length = intval($request->length ?? 10);
        $search = $request->input('search.value');

        $dateFrom = $request->date_from ?? null;
        $dateTo   = $request->date_to ?? null;
        $phone    = $request->phone ?? null;
        $status   = $request->status ?? null;

        $query = WablasReport::query();

        if ($dateFrom && $dateTo) {
            $query->whereBetween('date', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay()
            ]);
        }

        if ($phone) {
            $query->where(function ($q) use ($phone) {
                $q->where('from', 'like', "%$phone%")
                    ->orWhere('to', 'like', "%$phone%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $recordsTotal = WablasReport::count();
        $recordsFiltered = $query->count();

        if (!empty($search)) {

            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('from', 'like', "%$search%")
                    ->orWhere('to', 'like', "%$search%");
            });
        }

        $query->orderBy('date', 'desc');

        $rows = $query
            ->skip($start)
            ->take($length)
            ->get();

        $rows = $rows->map(function ($row) {
            return [
                'id'           => $row->id,
                'to'           => $row->to,
                'message'      => $row->message,
                'status'       => $row->status,
                'date'         => $row->date,
            ];
        });

        return response()->json([
            'draw'            => intval($request->draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $rows,
        ]);
    }
}
