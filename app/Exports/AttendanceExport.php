<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class AttendanceExport implements WithEvents, ShouldAutoSize
{
    protected array $attendances;

    public function __construct(array $attendances)
    {
        $this->attendances = $attendances;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ----------------------------
                // Logos
                // ----------------------------
                $ctuLogo = new Drawing();
                $ctuLogo->setName('CTU Logo');
                $ctuLogo->setPath(public_path('images/ctu-logo.png'));
                $ctuLogo->setHeight(60);
                $ctuLogo->setCoordinates('G1');
                $ctuLogo->setOffsetX(10);
                $ctuLogo->setWorksheet($sheet);

                $chedLogo = new Drawing();
                $chedLogo->setName('CHED Logo');
                $chedLogo->setPath(public_path('images/ched-logo.png'));
                $chedLogo->setHeight(60);
                $chedLogo->setCoordinates('P1');
                $chedLogo->setOffsetX(10);
                $chedLogo->setWorksheet($sheet);

                // ----------------------------
                // Main header
                // ----------------------------
                $sheet->setCellValue('H1', 'Cebu Technological University - Main Campus');
                $sheet->mergeCells('H1:O1');
                $sheet->getStyle('H1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('H1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // Subtitle
                $sheet->setCellValue('H2', 'Attendance Report');
                $sheet->mergeCells('H2:O2'); 
                $sheet->getStyle('H2')->getFont()->setItalic(true)->setSize(12);
                $sheet->getStyle('H2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('H2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ----------------------------
                // Row heights
                // ----------------------------
                $sheet->getRowDimension(1)->setRowHeight(65);
                $sheet->getRowDimension(3)->setRowHeight(25);
                $sheet->getRowDimension(4)->setRowHeight(20);
                $sheet->getRowDimension(6)->setRowHeight(25);

                // ----------------------------
                // Table headers
                // ----------------------------
                $tableHeaders = [
                    'ITEM CODE',
                    'A1-ATTEND-1',
                    'A1-ATTEND-2', 
                    'A1-ATTEND-3',
                    'A1-ATTEND-4'
                ];

                $col = 'A';
                foreach ($tableHeaders as $header) {
                    $sheet->setCellValue($col.'6', $header);
                    $col++;
                }

                // Style table header
                $lastCol = chr(ord('A') + count($tableHeaders) - 1);
                $sheet->getStyle("A6:{$lastCol}6")->getFont()->setBold(true);
                $sheet->getStyle("A6:{$lastCol}6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("A6:{$lastCol}6")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ----------------------------
                // Insert data starting at row 7
                // ----------------------------
                $sheet->fromArray($this->formatDataArray(), null, 'A7');

                // ----------------------------
                // Freeze the header row
                // ----------------------------
                $sheet->freezePane('A7');
            }
        ];
    }

    protected function formatDataArray(): array
    {
        $data = [];

        foreach ($this->attendances as $index => $attendance) {
            $data[] = [
                'A-ATTEND-' . ($index + 1), // ITEM CODE
                $attendance['athlete']['full_name'] ?? '', // A1-ATTEND-1: Athlete Name
                $attendance['event']['event_name'] ?? '', // A1-ATTEND-2: Event Name
                $attendance['status'] ?? '', // A1-ATTEND-3: Status
                $attendance['created_at'] ? date('Y-m-d', strtotime($attendance['created_at'])) : '' // A1-ATTEND-4: Date
            ];
        }

        return $data;
    }
}