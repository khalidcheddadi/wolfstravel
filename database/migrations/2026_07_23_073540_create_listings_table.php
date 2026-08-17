<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->foreignId('listing_type_id')->constrained('listing_types');
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('title', 255);
            $table->string('short_description', 500)->nullable();
            $table->text('description');
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('average_rating', 2, 1)->default(0);
            $table->unsignedInteger('total_reviews')->default(0);
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedInteger('favorites_count')->default(0);
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'city_id', 'listing_type_id'], 'listings_status_city_type_idx');
            $table->index('average_rating');
            $table->index('published_at');
        });
    }
    public function down(): void { Schema::dropIfExists('listings'); }
};
