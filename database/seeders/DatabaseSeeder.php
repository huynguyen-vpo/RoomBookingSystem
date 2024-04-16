<?php

namespace Database\Seeders;

use App\Models\RoomType;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use RoomConstant;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AvailableQuantitySeeder::class);
        $this->call(RoomTypeSeeder::class);
        $this->call(RoomSeeder::class);
    }
}
