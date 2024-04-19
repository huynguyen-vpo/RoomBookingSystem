<?php

namespace App\Exports;

use App\Models\Room;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RoomExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return Room::all();
    }
    public function columnFormats(): array{
        return [];
    }

    public function view(): View
    {
        return view('exports/room-excel-template', [
            'rooms' => Room::all()
        ]);
    }
}
