<?php

namespace App\Exports\MultipleSheet;

use App\Exports\BookedRoomByDayGroupsSheet;
use App\Exports\BookedRoomByDayUsersSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BookedRoomByDayMultipleSheet implements WithMultipleSheets
{
    public function sheets(): array 
    {   
        return [
            new BookedRoomByDayUsersSheet(),
            new BookedRoomByDayGroupsSheet(),
        ];
    }
}
