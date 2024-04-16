<?php

namespace Database\Seeders;

use App\Models\BookedRoomDay;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class BookedRoomDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $randBookingDays = rand(1, 4); //random booking days
        $randBookedRooms = rand(1, 3); //random booking rooms

        $start = now()->subDays(6); 
        $randBookingStartDate = $start->addDays(rand(1, 14));
        $rooms = Room::all()->random(3);
        if($rooms->count()){
            for ($i = 1; $i <= $randBookingDays; $i++) {
                $bookingDate = $randBookingStartDate->addDay();
                for ($j = 0; $j <= $randBookedRooms - 1; $j++) {
                    $valid = BookedRoomDay::bookedRoom($rooms[$j], $bookingDate);
                    if($valid->count() == 0){
                        BookedRoomDay::create([
                            'room_id' => $rooms[$j]->id,
                            'booking_id' => Uuid::uuid4(),
                            'booking_date' => $bookingDate,
                            'price_per_day' => rand(1000, 100000),
                        ]);
                    }
                }
            }
        }
    }
}
