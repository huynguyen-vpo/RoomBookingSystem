<?php

namespace App\Http\Controllers;

use App\Exports\BookedRoomByDayUsersSheet;
use App\Exports\MultipleSheet\BookedRoomByDayMultipleSheet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class ExportController extends Controller
{
    //
    public function exportBookingByDay(Request $request)
    {
        $now = now()->utc()->format('d-m-Y');
        return Excel::download(new BookedRoomByDayMultipleSheet, "[$now]_booking_by_day.xlsx");
    }
}
