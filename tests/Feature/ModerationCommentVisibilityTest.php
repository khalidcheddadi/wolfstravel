<?php

use App\Models\Listing\Listing;
use App\Models\Post;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('listings', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('business_id')->nullable();
        $table->unsignedBigInteger('listing_type_id')->nullable();
        $table->unsignedBigInteger('city_id')->nullable();
        $table->unsignedBigInteger('country_id')->nullable();
        $table->string('slug')->unique();
        $table->string('title');
        $table->string('short_description')->nullable();
        $table->text('description');
        $table->string('status')->default('draft');
        $table->timestamp('published_at')->nullable();
        $table->string('availability_status')->nullable();
        $table->boolean('is_hidden')->default(false);
        $table->text('hidden_reason')->nullable();
        $table->text('moderation_comment')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('excerpt')->nullable();
        $table->longText('content');
        $table->string('featured_image')->nullable();
        $table->unsignedBigInteger('category_id')->nullable();
        $table->unsignedBigInteger('user_id')->nullable();
        $table->boolean('is_published')->default(false);
        $table->timestamp('published_at')->nullable();
        $table->boolean('is_hidden')->default(false);
        $table->text('hidden_reason')->nullable();
        $table->text('moderation_comment')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
});

it('keeps hidden listings and posts out of public queries and stores the moderation comment', function () {
    $listing = Listing::create([
        'business_id' => 1,
        'listing_type_id' => 1,
        'city_id' => 1,
        'country_id' => 1,
        'slug' => 'hidden-listing',
        'title' => 'Hidden listing',
        'short_description' => 'Hidden listing short description',
        'description' => 'Hidden listing description',
        'status' => 'published',
        'is_hidden' => true,
        'hidden_reason' => 'Fake content',
        'moderation_comment' => 'This content is blocked by admin.',
    ]);

    $post = Post::create([
        'title' => 'Hidden article',
        'slug' => 'hidden-article',
        'excerpt' => 'Hidden excerpt',
        'content' => 'Hidden content',
        'category_id' => null,
        'user_id' => 1,
        'is_published' => true,
        'published_at' => now(),
        'is_hidden' => true,
        'hidden_reason' => 'Spam article',
        'moderation_comment' => 'This article is blocked by admin.',
    ]);

    expect($listing->moderation_comment)->toBe('This content is blocked by admin.')
        ->and($post->moderation_comment)->toBe('This article is blocked by admin.')
        ->and(Listing::visible()->whereKey($listing->id)->exists())->toBeFalse()
        ->and(Post::visible()->whereKey($post->id)->exists())->toBeFalse();
});

it('shows the availability badge only when the business confirms a real open or closed status', function () {
    $openListing = Listing::create([
        'business_id' => 1,
        'listing_type_id' => 1,
        'city_id' => 1,
        'country_id' => 1,
        'slug' => 'open-listing',
        'title' => 'Open listing',
        'short_description' => 'Open listing description',
        'description' => 'Open listing description body',
        'status' => 'published',
        'availability_status' => 'open',
    ]);

    $closedListing = Listing::create([
        'business_id' => 1,
        'listing_type_id' => 1,
        'city_id' => 1,
        'country_id' => 1,
        'slug' => 'closed-listing',
        'title' => 'Closed listing',
        'short_description' => 'Closed listing description',
        'description' => 'Closed listing description body',
        'status' => 'published',
        'availability_status' => 'closed',
    ]);

    $unknownListing = Listing::create([
        'business_id' => 1,
        'listing_type_id' => 1,
        'city_id' => 1,
        'country_id' => 1,
        'slug' => 'unknown-listing',
        'title' => 'Unknown listing',
        'short_description' => 'Unknown listing description',
        'description' => 'Unknown listing description body',
        'status' => 'published',
        'availability_status' => null,
    ]);

    expect($openListing->publicAvailabilityState())->toBe('open')
        ->and($closedListing->publicAvailabilityState())->toBe('closed')
        ->and($unknownListing->publicAvailabilityState())->toBeNull();
});
