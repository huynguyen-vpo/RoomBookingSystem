<?php

namespace App\Http\Controllers;

use App\Exports\BookedRoomByDayExport;
use App\Exports\RoomExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    private $now = now()->utc()->format('d-m-Y');
    //
    public function exportRooms(Request $request)
    {
        return Excel::download(new RoomExport, "[$this->now]_rooms.xlsx");
    }
    public function exportBookingByDay(Request $request)
    {
        // return BookedRoomDay::with(['room', 'booking.target'])->whereRelation('booking.target', 'name', 'Mrs. Evangeline Grant')->get();
        return Excel::download(new BookedRoomByDayExport, "[$this->now]_booking_by_day.xlsx");
    }
}
