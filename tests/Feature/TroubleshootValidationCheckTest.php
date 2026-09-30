<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\NotificationSetting;
use App\Models\Router;
use App\Models\Setting;
use App\Models\Troubleshoot;
use App\Models\TroubleshootProgress;
use App\Models\User;
use App\Services\FonteMessagingService;
use App\Services\NotificationDispatcher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TroubleshootValidationCheckTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    protected function createTestRouter(): Router
    {
        return Router::firstOrCreate(
            ['name' => 'Router Test Utama'],
            ['ip' => '192.168.1.1', 'username' => 'admin', 'password' => 'admin', 'port' => '8728']
        );
    }

    protected function createTestType(): \App\Models\Type
    {
        return \App\Models\Type::firstOrCreate(
            ['name' => 'PPPoE Test'],
            ['status' => '0']
        );
    }

    protected function createTestCustomer(string $name, string $mac, string $email, string $telp): Customer
    {
        $existing = Customer::first();
        if ($existing) {
            $data = $existing->toArray();
            unset($data['id'], $data['created_at'], $data['updated_at']);
            $data['name'] = $name;
            $data['uuid'] = 'CSTMR' . rand(10000, 99999);
            $data['mac_address'] = $mac;
            $data['email'] = $email;
            $data['telp'] = $telp;
            $data['status'] = 'active';
            return Customer::create($data);
        }

        $router = $this->createTestRouter();
        $type = $this->createTestType();

        return Customer::create([
            'name'        => $name,
            'uuid'        => 'CSTMR' . rand(10000, 99999),
            'telp'        => $telp,
            'email'       => $email,
            'mac_address' => $mac,
            'routers_id'  => $router->id,
            'types_id'    => $type->id,
            'status'      => 'active',
        ]);
    }

    public function test_admin_can_toggle_troubleshoot_check_validation()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $response = $this->actingAs($admin)->postJson(route('customer.toggle-troubleshoot-check'), [
            'value' => 'active',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status'    => 'success',
            'is_active' => true,
        ]);
        $this->assertEquals('active', Setting::where('key', 'troubleshoot_check_validation')->value('value'));

        $responseOff = $this->actingAs($admin)->postJson(route('customer.toggle-troubleshoot-check'), [
            'value' => 'inactive',
        ]);

        $responseOff->assertStatus(200);
        $responseOff->assertJson([
            'status'    => 'success',
            'is_active' => false,
        ]);
        $this->assertEquals('inactive', Setting::where('key', 'troubleshoot_check_validation')->value('value'));
    }

    public function test_non_admin_cannot_toggle_troubleshoot_check_validation()
    {
        $user = User::factory()->create();
        $user->syncRoles([]);

        $response = $this->actingAs($user)->postJson(route('customer.toggle-troubleshoot-check'), [
            'value' => 'active',
        ]);

        $response->assertStatus(403);
    }

    public function test_step_4_sets_status_to_waiting_check_and_notifies_creator_when_validation_is_active()
    {
        Mail::fake();
        Storage::fake('public');

        // Set toggle to active
        Setting::updateOrCreate(
            ['key' => 'troubleshoot_check_validation'],
            ['value' => 'active']
        );

        $creator = User::factory()->create(['email' => 'creator_' . uniqid() . '@example.com', 'telp' => '08123456781']);
        $technician = User::factory()->create(['email' => 'tech_' . uniqid() . '@example.com', 'telp' => '08123456782']);

        $customer = $this->createTestCustomer('Budi Test Customer', 'AA:BB:CC:DD:EE:FF', 'budi@example.com', '081299990001');

        $troubleshoot = Troubleshoot::create([
            'customer_id'   => $customer->id,
            'technician_id' => $technician->id,
            'created_by'    => $creator->id,
            'description'   => 'Koneksi lambat / waiting',
            'status'        => 'perbaikan',
        ]);

        // Complete steps 1 to 3 first
        for ($s = 1; $s <= 3; $s++) {
            TroubleshootProgress::create([
                'troubleshoot_id' => $troubleshoot->id,
                'step'            => $s,
                'status'          => 'completed',
                'photo'           => "dummy_step_{$s}.jpg",
                'latitude'        => -6.2,
                'longitude'       => 106.8,
            ]);
        }

        $file = UploadedFile::fake()->create('step_4.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($technician)->postJson(route('troubleshoot.progress.upload', [
            'id'   => $troubleshoot->id,
            'step' => 4,
        ]), [
            'photo'     => $file,
            'latitude'  => -6.2001,
            'longitude' => 106.8001,
            'address'   => 'Jl. Pahlawan No. 12',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status'        => 'success',
            'waiting_check' => true,
        ]);

        $troubleshoot->refresh();
        $this->assertEquals('waiting_check', $troubleshoot->status);

        // Verification email should be sent to creator
        Mail::assertSent(\App\Mail\GenericNotificationMail::class, function ($mail) use ($creator) {
            return $mail->hasTo($creator->email);
        });
    }

    public function test_step_4_sets_status_to_done_when_validation_is_inactive()
    {
        Mail::fake();

        // Set toggle to inactive (OFF)
        Setting::updateOrCreate(
            ['key' => 'troubleshoot_check_validation'],
            ['value' => 'inactive']
        );

        $creator = User::factory()->create(['email' => 'creator_' . uniqid() . '@example.com', 'telp' => '08123456783']);
        $technician = User::factory()->create(['email' => 'tech_' . uniqid() . '@example.com', 'telp' => '08123456784']);

        $customer = $this->createTestCustomer('Siti Test Customer', '11:22:33:44:55:66', 'siti@example.com', '081299990002');

        $troubleshoot = Troubleshoot::create([
            'customer_id'   => $customer->id,
            'technician_id' => $technician->id,
            'created_by'    => $creator->id,
            'description'   => 'Perbaikan kabel dropcore',
            'status'        => 'perbaikan',
        ]);

        // Complete steps 1 to 3
        for ($s = 1; $s <= 3; $s++) {
            TroubleshootProgress::create([
                'troubleshoot_id' => $troubleshoot->id,
                'step'            => $s,
                'status'          => 'completed',
                'photo'           => "dummy_step_{$s}.jpg",
                'latitude'        => -6.2,
                'longitude'       => 106.8,
            ]);
        }

        $file = UploadedFile::fake()->create('step_4.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($technician)->postJson(route('troubleshoot.progress.upload', [
            'id'   => $troubleshoot->id,
            'step' => 4,
        ]), [
            'photo'     => $file,
            'latitude'  => -6.2001,
            'longitude' => 106.8001,
            'address'   => 'Jl. Merdeka No. 45',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status'        => 'success',
            'waiting_check' => false,
        ]);

        $troubleshoot->refresh();
        $this->assertEquals('done', $troubleshoot->status);
    }

    public function test_creator_can_confirm_done_ticket_from_waiting_check()
    {
        Mail::fake();

        $creator = User::factory()->create(['email' => 'creator_' . uniqid() . '@example.com', 'telp' => '08123456785']);
        $technician = User::factory()->create(['email' => 'tech_' . uniqid() . '@example.com', 'telp' => '08123456786']);

        $customer = $this->createTestCustomer('Ahmad Test', '00:11:22:33:44:55', 'ahmad@example.com', '081299990003');

        $troubleshoot = Troubleshoot::create([
            'customer_id'   => $customer->id,
            'technician_id' => $technician->id,
            'created_by'    => $creator->id,
            'description'   => 'ONU Los Red',
            'status'        => 'waiting_check',
        ]);

        $response = $this->actingAs($creator)->postJson(route('troubleshoot.confirm-done', $troubleshoot->id), [
            'notes' => 'Status sudah dicek di MikroTik dan sudah Bound normal.',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);

        $troubleshoot->refresh();
        $this->assertEquals('done', $troubleshoot->status);
        $this->assertEquals('Status sudah dicek di MikroTik dan sudah Bound normal.', $troubleshoot->notes);

        // Verification email should be sent to technician confirming completion
        Mail::assertSent(\App\Mail\GenericNotificationMail::class, function ($mail) use ($technician) {
            return $mail->hasTo($technician->email);
        });
    }

    public function test_unauthorized_user_cannot_confirm_done_ticket()
    {
        $creator = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherUser->syncRoles([]);
        $technician = User::factory()->create();

        $customer = $this->createTestCustomer('Target User', '12:34:56:78:90:AB', 'target@example.com', '081299990004');

        $troubleshoot = Troubleshoot::create([
            'customer_id'   => $customer->id,
            'technician_id' => $technician->id,
            'created_by'    => $creator->id,
            'description'   => 'Kendala wifi',
            'status'        => 'waiting_check',
        ]);

        $response = $this->actingAs($otherUser)->postJson(route('troubleshoot.confirm-done', $troubleshoot->id));

        $response->assertStatus(403);
        $troubleshoot->refresh();
        $this->assertEquals('waiting_check', $troubleshoot->status);
    }
}
