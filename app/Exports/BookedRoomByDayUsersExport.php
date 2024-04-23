<?php

namespace App\Exports;

use App\Models\BookedRoomDay;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookedRoomByDayUsersExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        //
        return view('exports/booking-by-day-excel-template', [
            'date' => now(),
            'bookings' => BookedRoomDay::with(['room', 'booking.target'])->whereRelation('booking', 'target_type', '=', "App\\Models\\User")->get()
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true, 'size' => 18]],
        ];
    }
    public function title(): string
    {
        return 'Users';
    }
}
