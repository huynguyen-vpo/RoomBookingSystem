<?php

namespace App\Http\Controllers;

use App\Imports\MulitpleSheet\RoomImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    //
    public function importRooms(Request $request){
        logger($request->file("booking_by_day")->getPathname());
        Excel::import(new RoomImport, $request->file("booking_by_day")->getClientOriginalName());
    }
}
