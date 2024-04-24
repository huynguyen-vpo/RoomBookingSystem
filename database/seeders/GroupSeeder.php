<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GroupSeeder extends Seeder
{
    const TOTAL_GROUPS = 20;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!Group::all()->count()){
            for ($i = 1; $i <= $this::TOTAL_GROUPS; $i++) {
                Group::factory()->create([
                    'name' => Str::random(5),        
                ]);
            }
        }
    }
}
