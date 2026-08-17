<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->text('moderation_comment')->nullable()->after('hidden_reason');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->text('moderation_comment')->nullable()->after('hidden_reason');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('moderation_comment');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('moderation_comment');
        });
    }
};
