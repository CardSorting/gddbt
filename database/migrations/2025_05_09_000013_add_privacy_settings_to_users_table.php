<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('private_profile')->default(false)->after('remember_token');
            $table->boolean('share_streaks')->default(true)->after('private_profile');
            $table->boolean('share_progress')->default(true)->after('share_streaks');
            $table->boolean('share_daily_goals')->default(true)->after('share_progress');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'private_profile',
                'share_streaks', 
                'share_progress', 
                'share_daily_goals'
            ]);
        });
    }
};
