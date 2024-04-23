<?php

use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

Route::post('import/rooms', [ImportController::class, 'importRooms']);