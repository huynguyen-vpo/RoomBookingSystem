<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AvailableQuantitySeeder::class);
                $this->call(RoomTypeSeeder::class);
                $this->call(RoomSeeder::class);
        DB::beginTransaction();
            try{
                $this->call(AvailableQuantitySeeder::class);
                $this->call(RoomTypeSeeder::class);
                $this->call(RoomSeeder::class);
                // $this->call(UserSeeder::class);
                // $this->call(GroupSeeder::class);
                // $this->call(UserGroupSeeder::class);
                // $this->call(BookingSeeder::class);
                // $this->call(BookedRoomDaySeeder::class);

                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
                logger()->error($e->getMessage());
            }
    }
}
