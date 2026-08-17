<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        config(['mail.default' => 'array']);

        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);
        Role::firstOrCreate([
            'name' => 'business_owner',
            'guard_name' => 'web',
        ]);
        Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'web',
        ]);
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->withSession([
            '_token' => 'test-token',
        ])->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Str0ng!Passw0rd-2026',
            'password_confirmation' => 'Str0ng!Passw0rd-2026',
            'role' => 'customer',
            '_token' => 'test-token',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_registration_continues_when_email_sending_fails(): void
    {
        Mail::shouldReceive('send')->andThrow(new \Exception('Failed to authenticate on SMTP server'));

        $response = $this->withSession([
            '_token' => 'test-token',
        ])->post('/register', [
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'password' => 'Str0ng!Passw0rd-2026',
            'password_confirmation' => 'Str0ng!Passw0rd-2026',
            'role' => 'customer',
            '_token' => 'test-token',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('dashboard', absolute: false));
        $response->assertSessionHas('error', trans('messages.email.send_failed'));

        $this->assertAuthenticated();
    }
}
