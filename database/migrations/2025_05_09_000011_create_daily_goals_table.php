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
        Schema::create('daily_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->json('skills_used')->nullable();
            $table->text('daily_goal');
            $table->text('tomorrow_goal')->nullable();
            $table->text('highlight')->nullable();
            $table->text('gratitude')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
            
            // Unique constraint to ensure one entry per user per day
            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_goals');
    }
};
