<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserGroupSeeder extends Seeder
{
    const TOTAL_USER_GROUPS = 100;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::all();
        $group = Group::all();
 
        if($user->count() && $group->count()){
            for ($i = 1; $i <= $this::TOTAL_USER_GROUPS; $i++) {
                DB::table('user_group')->insert([
                    'user_id' => $user->random(1)->first()->id,
                    'group_id' => $group->random(1)->first()->id,
                ]);

            }
        }
    }
}
