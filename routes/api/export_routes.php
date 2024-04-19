<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('export/rooms', [ExportController::class, 'exportRooms']);
Route::get('export/bookingByDay', [ExportController::class, 'exportBookingByDay']);
