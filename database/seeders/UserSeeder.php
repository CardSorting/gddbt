<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Domain\Models\User;
use App\Domain\Models\Streak;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'xp_points' => 500,
            'level' => 5,
            'email_verified_at' => now(),
        ]);

        // Create a default test user
        $user1 = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'xp_points' => 150,
            'level' => 2,
            'email_verified_at' => now(),
        ]);

        // Create several demo users with various progress levels
        $user2 = User::create([
            'name' => 'Beginner User',
            'email' => 'beginner@example.com',
            'password' => Hash::make('password'),
            'xp_points' => 50,
            'level' => 1,
            'email_verified_at' => now(),
        ]);

        $user3 = User::create([
            'name' => 'Intermediate User',
            'email' => 'intermediate@example.com',
            'password' => Hash::make('password'),
            'xp_points' => 250,
            'level' => 3,
            'email_verified_at' => now(),
        ]);

        $user4 = User::create([
            'name' => 'Advanced User',
            'email' => 'advanced@example.com',
            'password' => Hash::make('password'),
            'xp_points' => 750,
            'level' => 8,
            'email_verified_at' => now(),
        ]);

        // Create streaks for some users
        Streak::create([
            'user_id' => $user1->id,
            'current_count' => 5,
            'longest_count' => 5,
            'last_activity_date' => now(),
            'freeze_count' => 0,
        ]);

        Streak::create([
            'user_id' => $user3->id,
            'current_count' => 12,
            'longest_count' => 15,
            'last_activity_date' => now()->subDay(),
            'freeze_count' => 1,
        ]);

        Streak::create([
            'user_id' => $user4->id,
            'current_count' => 30,
            'longest_count' => 30,
            'last_activity_date' => now(),
            'freeze_count' => 2,
        ]);
    }
}
