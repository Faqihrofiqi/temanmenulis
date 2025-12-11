<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        // Since we're using Mailtrap API, we'll mock the notification
        // to avoid requiring valid API credentials for tests
        Notification::fake();

        $user = User::factory()->create();

        $response = $this
            ->from('/forgot-password')
            ->post('/forgot-password', [
                'email' => $user->email,
            ]);

        // Assert that a notification was sent (will be faked)
        Notification::assertSentTo($user, ResetPassword::class);

        $response->assertRedirect('/forgot-password');
        $response->assertSessionHas('status');
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $token = 'valid-reset-token-for-testing';

        $response = $this->get('/reset-password/' . $token);

        $response->assertStatus(200);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        // Request password reset first
        $this->from('/forgot-password')
            ->post('/forgot-password', [
                'email' => $user->email,
            ]);

        // Get the notification that was sent
        $notification = Notification::sent($user, ResetPassword::class)->first();
        $token = $notification->token ?? 'test-token';

        // Test password reset with valid token
        $response = $this
            ->from('/reset-password/' . $token)
            ->post('/reset-password', [
                'token' => $token,
                'email' => $user->email,
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('status');
    }
}
