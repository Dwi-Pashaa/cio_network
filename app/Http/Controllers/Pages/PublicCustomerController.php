<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicCustomerController extends Controller
{
    /**
     * Render the public customer search page.
     */
    public function searchPage()
    {
        return view('pages.public-customer.search');
    }

    /**
     * Handle AJAX search requests from the public page.
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $customerId = trim($request->input('customer_id'));

        // Retrieve customer matching either numeric ID or UUID
        $customer = Customer::where('uuid', $customerId)
            ->orWhere('id', $customerId)
            ->first();

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Pelanggan tidak ditemukan.'
            ], 404);
        }

        // Prepare safe customer response payload
        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $customer->uuid ?? $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'telp' => $customer->telp,
            ]
        ]);
    }

    /**
     * Proxy the client area login page and inject autofill values.
     */
    public function proxyLogin(Request $request)
    {
        $username = $request->query('username');
        $password = $request->query('password');

        try {
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->get('https://client.cionetwork.id/login');

            if (!$response->successful()) {
                return response("Gagal memuat halaman login Client Area: " . $response->status(), 500);
            }

            $html = $response->body();

            // 1. Replace relative assets with absolute URLs
            $html = str_replace('href="theme/', 'href="https://client.cionetwork.id/theme/', $html);
            $html = str_replace('src="theme/', 'src="https://client.cionetwork.id/theme/', $html);

            // 2. Inject username value (supporting both name="username" and name="customer_id")
            $html = str_replace(
                'name="username"',
                'name="username" value="' . e($username) . '"',
                $html
            );
            $html = str_replace(
                'name="customer_id"',
                'name="customer_id" value="' . e($username) . '"',
                $html
            );

            // 3. Inject password value
            $html = str_replace(
                'name="password"',
                'name="password" value="' . e($password) . '"',
                $html
            );

            return response($html);
        } catch (\Exception $e) {
            return response("Gagal memuat halaman login Client Area: " . $e->getMessage(), 500);
        }
    }
}
