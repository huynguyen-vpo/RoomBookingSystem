<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        if(!RoomType::all()->count()){
            RoomType::factory()->create([
                'type' => 'single',
                'capacity' => 1,
            ]);
            RoomType::factory()->create([
                'type' => 'double',
                'capacity' => 2,
            ]);
            RoomType::factory()->create([
                'type' => 'triple',
                'capacity' => 3,
            ]);
            RoomType::factory()->create([
                'type' => 'quarter',
                'capacity' => 4,
            ]);
        }
    }
}
