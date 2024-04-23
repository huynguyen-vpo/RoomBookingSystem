<?php

use App\Models\BookedRoomDay;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    //return [Carbon::parse("2024-04-17 01:55:20")->addDay(), Carbon::parse("2024-04-20 01:55:20"), now(), now()->addDay()];
    // return BookedRoomDay::with(['room', 'booking.target'])->whereRelation('booking.target', 'name', 'Mrs. Evangeline Grant')->get();
    return BookedRoomDay::with(['room', 'booking.target'])->whereRelation('booking', 'target_type', '=', "App\\Models\\Group")->get();
});
