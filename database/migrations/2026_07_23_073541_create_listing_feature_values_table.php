<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_feature_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('listings')->cascadeOnDelete();
            $table->foreignId('feature_id')->constrained('listing_features')->cascadeOnDelete();
            $table->string('value', 50)->nullable();
            $table->timestamps();
            $table->unique(['listing_id', 'feature_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('listing_feature_values'); }
};
