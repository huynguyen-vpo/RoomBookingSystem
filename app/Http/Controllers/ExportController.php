<?php

namespace App\Http\Controllers;

use App\Exports\BookedRoomByDayExport;
use App\Exports\RoomExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    //
    public function exportRooms(Request $request)
    {
        $now = now()->utc()->format('d-m-Y');
        return Excel::download(new RoomExport, "[$now]_rooms.xlsx");
    }
    public function exportBookingByDay(Request $request)
    {
        $now = now()->utc()->format('d-m-Y');
        return Excel::download(new BookedRoomByDayExport, "[$now]_booking_by_day.xlsx");
    }
}
