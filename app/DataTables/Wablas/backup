<?php

namespace App\DataTables\Wablas;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ReportDataTable
{
    protected $baseUrl = 'https://kudus.wablas.com/api/report/message';

    protected $token;
    protected $secretKey;

    public function __construct()
    {
        $this->token     = config('wablas.token');
        $this->secretKey = config('wablas.secret_key');
    }

    /*
    |--------------------------------------------------------------------------
    | HTTP CLIENT (ANTI TIMEOUT)
    |--------------------------------------------------------------------------
    */
    protected function getClient()
    {
        return Http::withHeaders([
            'Authorization' => $this->token . '.' . $this->secretKey,
        ])
            ->withOptions([
                'verify' => false,
                'timeout' => 60,
                'connect_timeout' => 20,
            ])
            ->retry(3, 1000);
    }

    /*
    |--------------------------------------------------------------------------
    | EXTRACT NAMA (UNTUK BROADCAST)
    |--------------------------------------------------------------------------
    */
    private function extractName($text)
    {
        if (!$text) return null;

        if (preg_match('/Halo\s+(.*?)[,|\n]/i', $text, $matches)) {
            return trim($matches[1]);
        }

        if (preg_match('/Yth\.?\s+(.*?)[,|\n]/i', $text, $matches)) {
            return trim($matches[1]);
        }

        if (preg_match('/Kepada\s+(.*?)[,|\n]/i', $text, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE PESAN TRANSAKSI Cio_Network
    |--------------------------------------------------------------------------
    */
    private function parseVoucherMessage($text)
    {
        if (!$text) return [];

        $data = [];

        if (preg_match('/\*\s(\d{8,})/', $text, $match)) {
            $data['voucher_number'] = $match[1];
        }

        if (preg_match('/Paket\s:\s(.+)/', $text, $match)) {
            $data['paket'] = trim($match[1]);
        }

        if (preg_match('/Harga.*:\s(.+)/', $text, $match)) {
            $data['harga'] = trim($match[1]);
        }

        if (preg_match('/ID Transaksi\s:\s(.+)/', $text, $match)) {
            $data['id_transaksi'] = trim($match[1]);
        }

        if (preg_match('/ID Pesanan\s:\s(.+)/', $text, $match)) {
            $data['id_pesanan'] = trim($match[1]);
        }

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | SMART PARSER (AUTO DETECT TIPE PESAN)
    |--------------------------------------------------------------------------
    */
    private function smartParser($text)
    {
        if (!$text) return [];

        // Jika mengandung kata voucher → transaksi
        if (str_contains(strtolower($text), 'e-voucher')) {

            $voucher = $this->parseVoucherMessage($text);

            return array_merge([
                'tipe_pesan' => 'transaksi',
                'nama'       => null,
            ], $voucher);
        }

        // Selain itu anggap broadcast
        return [
            'tipe_pesan' => 'broadcast',
            'nama'       => $this->extractName($text),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | GET DATA
    |--------------------------------------------------------------------------
    */
    public function get()
    {
        $request = request();

        $start  = intval($request->start ?? 0);
        $length = intval($request->length ?? 10);
        $search = $request->input('search.value');

        $date  = $request->date_from ?? Carbon::today()->format('Y-m-d');
        $phone = $request->phone ?? null;

        $page = intval($start / $length) + 1;

        try {

            $response = $this->getClient()->get($this->baseUrl, [
                'date'    => $date,
                'perPage' => $length,
                'page'    => $page,
                'phone'   => $phone,
            ]);

            if (!$response->successful()) {
                throw new \Exception('API Error');
            }

            $result = $response->json();
        } catch (\Exception $e) {

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'API Wablas timeout / error'
            ]);
        }

        $rows  = collect($result['message'] ?? []);
        $total = $result['totalData'] ?? 0;

        $rows = $rows->map(function ($row) {

            $text = $row['text'] ?? null;

            $parsed = $this->smartParser($text);

            return [
                'id'             => $row['id'] ?? null,
                'tipe_pesan'     => $parsed['tipe_pesan'] ?? null,
                'nama'           => $parsed['nama'] ?? null,
                'from'           => $row['phone']['from'] ?? null,
                'to'             => $row['phone']['to'] ?? null,
                'voucher'        => $parsed['voucher_number'] ?? null,
                'paket'          => $parsed['paket'] ?? null,
                'harga'          => $parsed['harga'] ?? null,
                'id_transaksi'   => $parsed['id_transaksi'] ?? null,
                'id_pesanan'     => $parsed['id_pesanan'] ?? null,
                'message'        => $text,
                'status'         => $row['status'] ?? null,
                'type'           => $row['type'] ?? null,
                'ref_id'         => $row['ref_id'] ?? null,
                'date'           => $row['date']['created_at'] ?? null,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | SEARCH FILTER
        |--------------------------------------------------------------------------
        */
        if (!empty($search)) {

            $search = strtolower($search);

            $rows = $rows->filter(function ($row) use ($search) {

                return str_contains(strtolower($row['nama'] ?? ''), $search)
                    || str_contains(strtolower($row['from'] ?? ''), $search)
                    || str_contains(strtolower($row['to'] ?? ''), $search)
                    || str_contains(strtolower($row['message'] ?? ''), $search)
                    || str_contains(strtolower($row['status'] ?? ''), $search)
                    || str_contains(strtolower($row['voucher'] ?? ''), $search);
            })->values();
        }

        return response()->json([
            'draw'            => intval($request->draw),
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $rows->values(),
        ]);
    }
}
