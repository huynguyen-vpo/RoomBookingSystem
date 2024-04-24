<?php

namespace App\Exports;

use App\Models\BookedRoomDay;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class BookedRoomByDayUsersSheet implements FromView, ShouldAutoSize, WithStyles, WithEvents, WithTitle
{
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class    => function (BeforeSheet $event) {
                $event->sheet->mergeCells("A1:E1");
                $cellRange = 'A2:G2'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Calibri')->setSize(14)->setBold($cellRange);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->getColor()
                    ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FF17a2b8');
                $event->sheet->setAutoFilter($cellRange);
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            },
        ];
    }
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
