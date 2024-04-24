<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    const TOTALROOMS = 100;
    public function run()
    {
        //
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
                    'status' => 'Available',
                    'room_typeid' => $roomTypes->random(1)->first()->id,
                ]);
            }
        }
    }
}
