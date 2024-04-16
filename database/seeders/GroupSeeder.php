<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GroupSeeder extends Seeder
{
    const TOTALGROUPS = 20;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!Group::all()->count()){
            for ($i = 1; $i <= $this::TOTALGROUPS; $i++) {
                Group::factory()->create([
                    'name' => Str::random(5),        
                ]);
            }
        }
    }
}
