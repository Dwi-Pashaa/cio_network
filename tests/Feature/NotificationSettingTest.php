<?php

namespace Tests\Feature;

use App\Models\NotificationSetting;
use App\Models\User;
use App\Services\FonteMessagingService;
use App\Services\NotificationDispatcher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotificationSettingTest extends TestCase
{
    use DatabaseTransactions;

    public function test_authenticated_user_can_view_notification_settings_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('notification.setting.index'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Saluran Notifikasi');
    }

    public function test_user_can_update_notification_channel_preference()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $user = User::factory()->create(['telp' => '08123456789']);

        $response = $this->actingAs($user)->put(route('notification.setting.update'), [
            'channel'             => 'both',
            'notify_prosedur'     => 1,
            'notify_troubleshoot' => 1,
            'notify_pendaftaran'  => 1,
            'notify_complain'     => 1,
            'telp'                => '081234567890',
            'email'               => $user->email,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('notification_settings', [
            'user_id' => $user->id,
            'channel' => 'both',
        ]);
        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'telp' => '081234567890',
        ]);
    }

    public function test_notification_dispatcher_routes_whatsapp_and_email_correctly()
    {
        Mail::fake();

        $mockFonte = \Mockery::mock(FonteMessagingService::class);
        $mockFonte->shouldReceive('sendMessage')->andReturn(true);

        $dispatcher = new NotificationDispatcher($mockFonte);

        $user = User::factory()->create([
            'email' => 'tech_' . uniqid() . '@example.com',
            'telp'  => '08123456789',
        ]);

        NotificationSetting::create([
            'user_id' => $user->id,
            'channel' => 'both',
            'notify_prosedur' => true,
        ]);

        $res = $dispatcher->sendToUser(
            user: $user,
            subject: 'Test Subject',
            title: 'TEST TITLE',
            message: 'Test Message',
            category: 'prosedur'
        );

        $this->assertTrue($res['whatsapp']);
        $this->assertTrue($res['email']);

        Mail::assertSent(\App\Mail\GenericNotificationMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_notification_dispatcher_respects_email_only_preference()
    {
        Mail::fake();

        $mockFonte = \Mockery::mock(FonteMessagingService::class);
        $mockFonte->shouldNotReceive('sendMessage');

        $dispatcher = new NotificationDispatcher($mockFonte);

        $user = User::factory()->create([
            'email' => 'admin_' . uniqid() . '@example.com',
            'telp'  => '08123456789',
        ]);

        NotificationSetting::create([
            'user_id' => $user->id,
            'channel' => 'email',
            'notify_prosedur' => true,
        ]);

        $res = $dispatcher->sendToUser(
            user: $user,
            subject: 'Test Email Only',
            title: 'TEST',
            message: 'Hello Email',
            category: 'prosedur'
        );

        $this->assertFalse($res['whatsapp']);
        $this->assertTrue($res['email']);

        Mail::assertSent(\App\Mail\GenericNotificationMail::class);
    }
}
