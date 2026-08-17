<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_submit_site_review_and_it_is_visible_on_homepage(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/customer/reviews', [
                'rating' => 5,
                'title' => 'Excellent service',
                'comment' => 'The experience was amazing and very professional.',
            ])
            ->assertRedirect(route('customer.dashboard'));

        $this->assertDatabaseHas('site_reviews', [
            'user_id' => $user->id,
            'rating' => 5,
            'title' => 'Excellent service',
            'status' => 'approved',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Excellent service')
            ->assertSee('The experience was amazing and very professional.');
    }
}
