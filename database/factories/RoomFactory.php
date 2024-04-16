<?php

namespace Database\Factories;

use App\Enums\RoomStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roomnumber = 1;
        return [
            //
            'room_number' => $roomnumber++,
            'status' => RoomStatus::AVAILABLE,
        ];
    }
}
