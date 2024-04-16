<?php

namespace Database\Seeders;

use App\Models\AvailableQuantity;
use Carbon\CarbonPeriod;
use Database\Factories\AvailableQuantityFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AvailableQuantitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $now = now();
        $lastest = AvailableQuantity::lastestDate();
        if($lastest){
            $now = $lastest->date->clone()->addDays(1);
        }
        
        $startDate = $now->clone()->startOfDay();
        $endDate = $now->clone()->addDays(10)->endOfDay();
        $datePeriod =  collect(CarbonPeriod::create($startDate, $endDate)->toArray())
              ->map(function($eachCarbonDate){
                return $eachCarbonDate;
              });

        foreach ($datePeriod as $date){
            AvailableQuantity::factory()->create([
                'date' => $date,
                'single_remaining_quantity' => 100,
                'double_remaining_quantity' => 100,
                'triple_remaining_quantity' => 100,
                'quarter_remaining_quantity' => 100,
            ]);
        }
    }
}
