<?php

namespace App\Exports\MultipleSheet;

use App\Exports\BookedRoomByDayGroupsExport;
use App\Exports\BookedRoomByDayUsersExport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BookedRoomByDayMultiSheetExport implements WithMultipleSheets
{
    public function sheets(): array 
    {   
        return [
            new BookedRoomByDayUsersExport(),
            new BookedRoomByDayGroupsExport(),
        ];
    }
}
