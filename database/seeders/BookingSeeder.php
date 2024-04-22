<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    const TOTALBOOKINGSBYUSER = 20;
    const TOTALBOOKINGSBYGROUP = 20;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if(Booking::all()->count()) return;

        $user = User::all();
        $group = DB::table('user_group')->get();

        $start = now()->copy()->subDays(7);  
        
        if($user->count()){
            for ($i = 1; $i <= $this::TOTALBOOKINGSBYUSER; $i++) {
                $checkInDate = $start->copy()->addDays(rand(1, 14));
                $randBookingDays = rand(1, 4); //random booking days
                $checkOutDate =  $checkInDate->copy()->addDays($randBookingDays);
                $randomUser = $user->random(1)->first();
                Booking::factory()->create([
                    'target_type' => "App\Models\User",   
                    'target_id' => $randomUser->id,  
                    'user_id' => $randomUser->id,       
                    'check_in_date' => $checkInDate,       
                    'check_out_date' => $checkOutDate,   
                    'total_price' => 0,             
                ]);
            }
        }
        

        if($group->count()){
            for ($i = 1; $i <= $this::TOTALBOOKINGSBYGROUP; $i++) {
                $checkInDate = $start->copy()->addDays(rand(1, 14));
                $randBookingDays = rand(1, 4); //random booking days
                $checkOutDate =  $checkInDate->copy()->addDays($randBookingDays);
                $randomGroup = $group->random(1)->first();
                Booking::factory()->create([
                    'target_type' => "App\Models\Group",   
                    'target_id' => $randomGroup->group_id, 
                    'user_id' => $randomGroup->user_id,        
                    'check_in_date' => $checkInDate,       
                    'check_out_date' => $checkOutDate,   
                    'total_price' => 0,             
                ]);
            }
        }
    }
}

