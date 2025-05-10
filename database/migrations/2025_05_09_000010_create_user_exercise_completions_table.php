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
        Schema::create('user_exercise_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained()->onDelete('cascade');
            $table->timestamp('completed_at');
            $table->json('answer')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->integer('points_earned')->default(0);
            $table->integer('attempts')->default(1);
            $table->timestamps();
            
            // Unlike lessons, we don't want a unique constraint here
            // because users can attempt exercises multiple times
            // We'll keep track of the latest attempt with the completed_at timestamp
            $table->index(['user_id', 'exercise_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_exercise_completions');
    }
};
