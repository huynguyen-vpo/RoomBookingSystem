<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    const TOTAL_USERS = 20;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // if(!User::all()->count()){
        //     for ($i = 1; $i <= $this::TOTAL_USERS; $i++) {
        //         User::factory()->create([
        //             'name' => fake()->name(),
        //             'email' => fake()->unique()->safeEmail(),
        //             'email_verified_at' => now(),
        //             'role'=>'User',
        //             'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        //             'remember_token' => Str::random(10),
        //         ]);
        //     }
        // }
    }
}
