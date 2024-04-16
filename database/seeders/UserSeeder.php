<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    const TOTALUSERS = 20;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = "Test@1234";

        if(!User::all()->count()){
            for ($i = 1; $i <= $this::TOTALUSERS; $i++) {
                User::factory()->create([
                    'name' => fake()->name(),
                    'email' => fake()->unique()->safeEmail(),
                    'email_verified_at' => now(),
                    'password' => $password ??= Hash::make('password'),
                    'remember_token' => Str::random(10),
                ]);
            }
        }

    }
}
