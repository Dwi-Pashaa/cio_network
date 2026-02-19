<?php

namespace App\DataTables\Wablas;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Cookie\CookieJar;
use Carbon\Carbon;

class ReportDataTable
{
    protected $baseUrl = 'https://mixcio.topsetting.com:973';

    protected function getClient()
    {
        $cookieJar = Cache::get('mix_cookie');

        if (!$cookieJar) {

            $cookieJar = new CookieJar();

            $client = Http::withOptions([
                'verify'  => false,
                'cookies' => $cookieJar,
                'timeout' => 60,
                'connect_timeout' => 20
            ]);

            $client->asForm()->post($this->baseUrl . '/rad-admin/post', [
                'username' => 'dwi12345',
                'password' => 'dwi12345',
            ]);

            Cache::put('mix_cookie', $cookieJar, now()->addMinutes(5));
        }

        return Http::withOptions([
            'verify'  => false,
            'cookies' => $cookieJar,
            'timeout' => 60,
            'connect_timeout' => 20,
        ])->retry(3, 1000);
    }

    public function get()
    {
        $request = request();

        $start    = $request->start  ?? 0;
        $length   = $request->length ?? 10;
        $search   = $request->input('search.value');
        $dateFrom = $request->date_from ?? Carbon::today()->format('Y-m-d');
        $dateTo   = $request->date_to   ?? Carbon::today()->format('Y-m-d');

        $client = $this->getClient();

        $response = $client->post($this->baseUrl . '/rad-get-data/wablastlog', [
            'page'    => 1,
            'perPage' => 1000,
        ]);

        $result = json_decode($response->body(), true);
        $data   = collect($result['data'] ?? []);

        if (!empty($dateFrom) || !empty($dateTo)) {
            $data = $data->filter(function ($row) use ($dateFrom, $dateTo) {

                if (empty($row['date'])) return false;

                $rowDate = Carbon::parse($row['date'])->startOfDay();

                if (!empty($dateFrom) && $rowDate->lt(Carbon::parse($dateFrom)->startOfDay())) {
                    return false;
                }

                if (!empty($dateTo) && $rowDate->gt(Carbon::parse($dateTo)->startOfDay())) {
                    return false;
                }

                return true;
            });
        }

        if (!empty($search)) {
            $data = $data->filter(function ($row) use ($search) {
                return str_contains(
                    strtolower(json_encode($row)),
                    strtolower($search)
                );
            });
        }

        $data = $data->sortByDesc(function ($row) {
            return $row['date'] ?? null;
        });

        $recordsFiltered = $data->count();
        $recordsTotal    = $recordsFiltered;

        $data = $data->slice($start, $length)->values();

        return response()->json([
            'draw'            => intval($request->draw),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }
}
