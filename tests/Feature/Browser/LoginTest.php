<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\User;
use Spatie\Permission\Models\Role;

class LoginTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
    }

    /** @test */
    public function it_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'admin@trav.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('admin');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/admin/dashboard')
                    ->assertSee('Dashboard');
        });
    }

    /** @test */
    public function it_shows_error_with_wrong_credentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'wrong@email.com')
                    ->type('password', 'wrongpassword')
                    ->press('Login')
                    ->assertSee('These credentials do not match our records');
        });
    }

    /** @test */
    public function it_does_not_show_419_error_on_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertDontSee('419')
                    ->assertDontSee('Page Expired');
        });
    }
}