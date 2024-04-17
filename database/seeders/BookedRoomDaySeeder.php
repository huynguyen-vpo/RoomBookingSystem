<?php

namespace Database\Seeders;

use App\Models\BookedRoomDay;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
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
        $randBookedRooms = rand(1, 3); //random booking rooms
        $bookings = Booking::all();

        if($bookings->count()){
            foreach($bookings as $booking){
                $rooms = Room::all()->random($randBookedRooms);
                if($rooms->count()){
                    $diffDays = Carbon::parse($booking->check_in_date->toDateString())
                                            ->diffInDays(Carbon::parse($booking->check_out_date->toDateString()));
                    for ($i = 1; $i <= $diffDays; $i++) {
                        $bookingDate = $booking->check_in_date->addDay();
                        for ($j = 0; $j <= $randBookedRooms - 1; $j++) {
                            $valid = BookedRoomDay::bookedRoom($rooms[$j], $bookingDate);
                            if($valid->count() == 0){
                                BookedRoomDay::create([
                                    'room_id' => $rooms[$j]->id,
                                    'booking_id' => $booking->id,
                                    'booking_date' => $bookingDate,
                                    'price_per_day' => rand(1000, 100000),
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
