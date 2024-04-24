<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('export/bookingByDay', [ExportController::class, 'exportBookingByDay']);