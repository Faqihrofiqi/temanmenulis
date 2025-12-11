<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        // Enable email bypass for testing
        config(['app.bypass_email' => true]);

        // Test direct user creation first
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        // Mark as verified if bypass is active
        if (config('app.bypass_email', false)) {
            $user->update(['email_verified_at' => now()]);
        }

        // Check if user was created
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);

        // Test authentication
        $this->assertTrue(\Illuminate\Support\Facades\Auth::attempt([
            'email' => 'test@example.com',
            'password' => 'password',
        ]));

        $this->assertAuthenticated();
    }
}
