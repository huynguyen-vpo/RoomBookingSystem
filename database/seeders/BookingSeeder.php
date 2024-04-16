<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        $group = Group::all();
        $now = now();
        $checkInDate  = $now->clone()->subDays(7)->startOfDay();
        $checkOutDate =  $checkInDate->addDays(3)->endOfDay();

        if($user->count()){
            for ($i = 1; $i <= $this::TOTALBOOKINGSBYUSER; $i++) {
                $randomUser = $user->random(1)->first();
                Booking::factory()->create([
                    'target_type' => "App\Models\User",   
                    'target_id' => $randomUser->id,       
                    'check_in_date' => $checkInDate,       
                    'check_out_date' => $checkOutDate,   
                    'total_price' => 0,             
                ]);
            }
        }
        

        if($group->count()){
            for ($i = 1; $i <= $this::TOTALBOOKINGSBYGROUP; $i++) {
                $randomGroup = $group->random(1)->first();
                Booking::factory()->create([
                    'target_type' => "App\Models\Group",   
                    'target_id' => $randomGroup->id,       
                    'check_in_date' => $checkInDate,       
                    'check_out_date' => $checkOutDate,   
                    'total_price' => 0,             
                ]);
            }
        }
    }
}
