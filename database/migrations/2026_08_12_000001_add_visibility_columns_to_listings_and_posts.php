<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('status');
            $table->text('hidden_reason')->nullable()->after('is_hidden');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('is_published');
            $table->text('hidden_reason')->nullable()->after('is_hidden');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['is_hidden', 'hidden_reason']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['is_hidden', 'hidden_reason']);
        });
    }
};
