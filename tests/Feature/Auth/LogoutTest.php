<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get('/');
        $response = $this->post('/logout', [
            '_token' => csrf_token(),
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
        echo "Logout works\n";
    }

    /** @test */
    public function it_does_not_throw_419_on_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get('/');
        $response = $this->post('/logout', [
            '_token' => csrf_token(),
        ]);

        $this->assertNotEquals(419, $response->status(), 'Logout returned 419');
        echo "Logout does not cause 419\n";
    }
}