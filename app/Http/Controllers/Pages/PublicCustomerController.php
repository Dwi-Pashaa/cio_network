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
            'mac_address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $macAddress = trim($request->input('mac_address'));
        // Normalize MAC: strip all colons and hyphens and make uppercase to support format-insensitive search
        $cleanMac = strtoupper(str_replace([':', '-'], '', $macAddress));

        $customer = Customer::whereRaw("UPPER(TRIM(REPLACE(REPLACE(mac_address, ':', ''), '-', ''))) = ?", [$cleanMac])
            ->whereHas('type', function ($q) {
                $q->where('name', 'PPPOE');
            })
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

    /**
     * Render the public customer wifi reset page.
     */
    public function resetWifiPage()
    {
        return view('pages.public-customer.reset-wifi');
    }

    /**
     * Handle customer lookup by MAC address for WiFi/PPPoE password reset.
     */
    public function searchCustomerForReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mac_address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $macAddress = trim($request->input('mac_address'));
        // Normalize MAC: strip all colons and hyphens and make uppercase to support format-insensitive search
        $cleanMac = strtoupper(str_replace([':', '-'], '', $macAddress));

        $customer = Customer::whereRaw("UPPER(TRIM(REPLACE(REPLACE(mac_address, ':', ''), '-', ''))) = ?", [$cleanMac])
            ->whereHas('type', function ($q) {
                $q->where('name', 'PPPOE');
            })
            ->first();

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Pelanggan tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $customer->uuid ?? $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'telp' => $customer->telp,
                'pppoe_username' => $customer->pppoe_username,
                'name_wifi' => $customer->name_wifi,
                'password_wifi' => $customer->password_wifi,
            ]
        ]);
    }

    /**
     * Handle submission of WiFi and PPPoE password reset.
     */
    public function submitResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mac_address' => 'required|string',
            'name_wifi' => 'nullable|string|max:255',
            'password_wifi' => 'required|string|min:8|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $macAddress = trim($request->input('mac_address'));
        $macAddress = strtoupper(str_replace('-', ':', $macAddress));

        $customer = Customer::where('mac_address', $macAddress)
            ->whereHas('type', function ($q) {
                $q->where('name', 'PPPOE');
            })
            ->first();

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Pelanggan tidak ditemukan atau bukan tipe layanan PPPoE.'
            ], 404);
        }

        // Save to ProsedurSpam (data spam)
        $payload = [
            'name_wifi' => $request->input('name_wifi'),
            'password_wifi' => $request->input('password_wifi'),
            'pppoe_username' => $customer->pppoe_username,
        ];

        $spam = \App\Models\ProsedurSpam::create([
            'customer_id' => $customer->id,
            'submitted_by' => $customer->user_id ?? 1,
            'organization_id' => $customer->organization_id,
            'prosedur_type' => 'pergantian-password',
            'payload' => $payload,
            'status' => 'pending',
        ]);

        // Create checkpoint validations (level 4 / ONC only)
        $spam->createValidationCheckpoints();

        // Send WhatsApp notification immediately to ONC (Level 4)
        try {
            app(\App\Services\ProsedurNotificationService::class)->notifyLevels($spam, [4]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PublicCustomerController: Gagal mengirim notifikasi ke ONC.', [
                'error' => $e->getMessage()
            ]);
        }

        // Send WhatsApp confirmation notification to Customer
        if ($customer->telp) {
            try {
                $rawPassword = $request->input('password_wifi');
                $len = strlen($rawPassword);
                $visibleLen = min(4, max(1, floor($len / 2)));
                $maskedPassword = substr($rawPassword, 0, $visibleLen) . str_repeat('*', $len - $visibleLen);
                $wifiName = $request->input('name_wifi') ?: ($customer->name_wifi ?: 'Tidak diubah');

                $customerMessage = "*Halo, {$customer->name}!* 👋\n\n"
                    . "Permintaan perubahan password WiFi Anda telah kami terima dan sedang dalam antrean verifikasi. 🚀\n\n"
                    . "Demi menjaga keamanan jaringan Anda, proses ini memerlukan waktu validasi maksimal *1 x 24 jam*. Setelah disetujui oleh tim kami, sistem akan memperbarui password router WiFi Anda secara otomatis. Anda tidak perlu melakukan apa-apa lagi! ✨\n\n"
                    . "*Detail Permintaan:* \n"
                    . "- Nama WiFi: *{$wifiName}*\n"
                    . "- Password Baru: *{$maskedPassword}*\n\n"
                    . "Terima kasih atas kesabaran dan kerja sama Anda. Jika ada kendala atau pertanyaan, tim kami siap membantu!\n\n"
                    . "Salam hangat,\n"
                    . "*CIO Network*";

                app(\App\Services\FonteMessagingService::class)->sendMessage($customer->telp, $customerMessage);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('PublicCustomerController: Gagal mengirim notifikasi WhatsApp ke pelanggan.', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        $successMsg = 'Permintaan perubahan password WiFi Anda berhasil diajukan! Demi keamanan, proses verifikasi memerlukan waktu maksimal 1x24 jam. Setelah disetujui, sistem kami akan memperbarui password Anda secara otomatis.';
        if ($customer->telp) {
            $successMsg .= ' Konfirmasi detail permintaan juga telah kami kirimkan ke WhatsApp Anda.';
        }

        return response()->json([
            'status' => 'success',
            'message' => $successMsg
        ]);
    }
}
