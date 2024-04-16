<?php

namespace Database\Seeders;

use App\Enums\RoomStatus;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use RoomConstant;

class RoomSeeder extends Seeder
{
    const TOTALROOMS = 100;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $roomTypes = RoomType::all();
        if ($roomTypes) {
            for ($i = 1; $i <= $this::TOTALROOMS; $i++) {
                Room::factory()->create([
                    'room_number' => $i,
                    'status' => RoomStatus::AVAILABLE,
                    'room_typeid' => $roomTypes->random(1)->first()->id,
                ]);
            }
        }
    }
}
