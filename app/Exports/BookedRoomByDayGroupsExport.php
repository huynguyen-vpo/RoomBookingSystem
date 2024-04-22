<?php

namespace App\Exports;

use App\Models\BookedRoomDay;
use Maatwebsite\Excel\Concerns\FromCollection;

class BookedRoomByDayGroupsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return BookedRoomDay::with(['room', 'booking.target'])->whereRelation('booking.target', 'email', '=', null)->get();
    }
}
