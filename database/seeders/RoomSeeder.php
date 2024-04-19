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
        $view_templates = [
            'sea',
            'flower street',
            'mountain',
            'dusk',
            'valley'
        ];
        $total = Room::all();
        $count = 1;
        if($total->count()) {
            $count = $total->count() + 1;
        }
        $roomTypes = RoomType::all();
        if ($roomTypes->count()) {
            for ($i = $count; $i <= $this::TOTALROOMS + $count - 1; $i++) {
                Room::factory()->create([
                    'room_number' => $i,
                    'view' => $view_templates[rand(0, count($view_templates) -1)],
                    'price' => rand(1000, 10000),
                    'status' => RoomStatus::AVAILABLE,
                    'room_typeid' => $roomTypes->random(1)->first()->id,
                ]);
            }
        }
    }
}
