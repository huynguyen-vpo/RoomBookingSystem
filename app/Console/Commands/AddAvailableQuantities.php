<?php

namespace App\Console\Commands;

use App\Models\AvailableQuantity;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class AddAvailableQuantities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-available-quantities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = now();
        $lastest = AvailableQuantity::lastestDate();
        if($lastest->count()){
            $now = $lastest->date->clone()->addDays(1);
        }
        
        $startDate = $now->clone()->startOfDay();
        $endDate = $now->clone()->addDays(30)->endOfDay();
        $datePeriod =  collect(CarbonPeriod::create($startDate, $endDate)->toArray())
              ->map(function($eachCarbonDate){
                return $eachCarbonDate;
              });

        foreach ($datePeriod as $date){
            AvailableQuantity::factory()->create([
                'date' => $date,
                'single_remaining_quantity' => 20,
                'double_remaining_quantity' => 40,
                'triple_remaining_quantity' => 30,
                'quarter_remaining_quantity' => 10,
            ]);
        }
    }
}
