<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Organization;
use App\Models\ProsedurSpam;
use App\Models\User;
use App\Models\Type;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ResetWifiTest extends TestCase
{
    use DatabaseTransactions;

    public function test_complete_reset_wifi_flow()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // 1. Mock Fonnte WhatsApp API
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200),
        ]);

        // 2. Fetch existing PPPoE customer from database
        $customer = Customer::whereHas('type', function($q) {
            $q->where('name', 'PPPOE');
        })->first();

        $this->assertNotNull($customer, 'Seeded PPPoE customer must exist to run tests.');

        // Backup existing values to assert later
        $oldMacAddress = $customer->mac_address;
        $orgId = $customer->organization_id;

        // Set testable old values
        $customer->update([
            'name_wifi' => 'MyOldWifi',
            'password_wifi' => 'oldpassword123',
            'pppoe_password' => 'oldpppoepass',
            'telp' => '081234567890',
        ]);

        // Find or create a user with Level 4 permission
        $validatorUser = User::whereHas('permissions', function($q) {
            $q->where('name', 'validasi prosedur level 4');
        })->first();

        if (!$validatorUser) {
            $validatorUser = User::first();
            $this->assertNotNull($validatorUser, 'At least one user must exist in database to run tests.');
            $validatorUser->givePermissionTo('validasi prosedur level 4');
        }

        // 3. Search for customer by MAC Address
        // Normalizes to original MAC format
        $macForSearch = str_replace(':', '-', $oldMacAddress);
        $searchResponse = $this->postJson(route('public.customer.reset_wifi.search'), [
            'mac_address' => $macForSearch,
        ]);

        $searchResponse->assertStatus(200);
        $searchResponse->assertJsonPath('data.name', $customer->name);
        $searchResponse->assertJsonPath('data.pppoe_username', $customer->pppoe_username);

        // 4. Submit password reset request
        $submitResponse = $this->postJson(route('public.customer.reset_wifi.submit'), [
            'mac_address' => $oldMacAddress,
            'name_wifi' => 'MyNewWifi', // changing WiFi SSID
            'password_wifi' => 'newwifipassword123',
        ]);

        $submitResponse->assertStatus(200);
        $submitResponse->assertJsonPath('status', 'success');

        // Verify ProsedurSpam record exists in database
        $spam = ProsedurSpam::where('customer_id', $customer->id)
            ->where('prosedur_type', 'pergantian-password')
            ->first();

        $this->assertNotNull($spam);
        $this->assertEquals('pending', $spam->status);
        $this->assertEquals('MyNewWifi', $spam->payload['name_wifi']);
        $this->assertEquals('newwifipassword123', $spam->payload['password_wifi']);
        $this->assertArrayNotHasKey('pppoe_password', $spam->payload);

        // Verify validation checkpoints
        $validations = $spam->validations;
        $this->assertCount(1, $validations); // Only Level 4 checkpoint should exist
        $this->assertEquals(4, $validations->first()->level);
        $this->assertEquals('pending', $validations->first()->status);

        // 5. Approve checkpoint (Tim ONC)
        $this->actingAs($validatorUser);

        $approveResponse = $this->putJson(route('validasi.prosedur.approve', $spam->id), [
            'notes' => 'Persetujuan password reset oleh tim ONC',
        ]);

        $approveResponse->assertStatus(200);
        $approveResponse->assertJsonPath('status', 'success');

        // 6. Verify changes applied to database
        $customer->refresh();
        $this->assertEquals('MyNewWifi', $customer->name_wifi);
        $this->assertEquals('newwifipassword123', $customer->password_wifi);
        $this->assertEquals('oldpppoepass', $customer->pppoe_password); // should remain unchanged

        // Verify ProsedurSpam status is approved
        $spam->refresh();
        $this->assertEquals('approved', $spam->status);
        $this->assertEquals($validatorUser->id, $spam->executed_by);
        $this->assertNotNull($spam->executed_at);

        // Verify Level 4 validation status is approved
        $validationCheckpoint = $spam->validations()->first();
        $this->assertEquals('approved', $validationCheckpoint->status);
        $this->assertEquals($validatorUser->id, $validationCheckpoint->validated_by);
    }

    public function test_complete_reset_wifi_flow_optional_ssid()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // 1. Mock Fonnte WhatsApp API
        Http::fake([
            'https://api.fonnte.com/send' => Http::response(['status' => true], 200),
        ]);

        // 2. Fetch existing PPPoE customer from database
        $customer = Customer::whereHas('type', function($q) {
            $q->where('name', 'PPPOE');
        })->first();

        $this->assertNotNull($customer, 'Seeded PPPoE customer must exist to run tests.');

        $oldMacAddress = $customer->mac_address;
        $orgId = $customer->organization_id;

        // Set testable old values
        $customer->update([
            'name_wifi' => 'KeepOldWifiName',
            'password_wifi' => 'oldpassword123',
            'pppoe_password' => 'oldpppoepass',
            'telp' => '081234567891',
        ]);

        $validatorUser = User::whereHas('permissions', function($q) {
            $q->where('name', 'validasi prosedur level 4');
        })->first();

        if (!$validatorUser) {
            $validatorUser = User::first();
            $this->assertNotNull($validatorUser, 'At least one user must exist in database to run tests.');
            $validatorUser->givePermissionTo('validasi prosedur level 4');
        }

        // 3. Submit password reset request leaving name_wifi empty (optional)
        $submitResponse = $this->postJson(route('public.customer.reset_wifi.submit'), [
            'mac_address' => $oldMacAddress,
            'name_wifi' => '', // blank = do not change
            'password_wifi' => 'newwifipassword123',
        ]);

        $submitResponse->assertStatus(200);
        $submitResponse->assertJsonPath('status', 'success');

        $spam = ProsedurSpam::where('customer_id', $customer->id)
            ->where('prosedur_type', 'pergantian-password')
            ->first();

        $this->assertNotNull($spam);
        $this->assertEquals('', $spam->payload['name_wifi']);

        // 4. Approve checkpoint
        $this->actingAs($validatorUser);
        $approveResponse = $this->putJson(route('validasi.prosedur.approve', $spam->id), [
            'notes' => 'Approve without SSID change',
        ]);

        $approveResponse->assertStatus(200);

        // 5. Verify customer updated, but WiFi Name (SSID) remains unchanged
        $customer->refresh();
        $this->assertEquals('KeepOldWifiName', $customer->name_wifi); // kept original SSID
        $this->assertEquals('newwifipassword123', $customer->password_wifi); // updated
        $this->assertEquals('oldpppoepass', $customer->pppoe_password); // should remain unchanged
    }
}
