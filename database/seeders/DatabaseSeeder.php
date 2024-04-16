<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            $this->call(UserSeeder::class);
            $this->call(GroupSeeder::class);
            $this->call(UserGroupSeeder::class);
            $this->call(BookingSeeder::class);
            $this->call(BookedRoomDaySeeder::class);
    }
}
