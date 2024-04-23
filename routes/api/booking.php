<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;


Route::prefix('bookings')->group(function () {
    Route::post('/user', [BookingController::class , 'createBookingByUser']);
    Route::post('/group', [BookingController::class , 'createBookingByGroup']);
});