<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PerformanceExport implements WithEvents, ShouldAutoSize
{
    protected array $performances;

    public function __construct(array $performances)
    {
        $this->performances = $performances;
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
                $sheet->setCellValue('H2', 'Performance Report');
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
                    'P1-ATHLETE-1',
                    'P1-ATHLETE-2', 
                    'P1-ATHLETE-3',
                    'P1-ATHLETE-4',
                    'P1-ATHLETE-5'
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

        foreach ($this->performances as $index => $performance) {
            $data[] = [
                'P-PERF-' . ($index + 1), // ITEM CODE
                $performance['athlete']['full_name'] ?? '', // P1-ATHLETE-1: Athlete Name
                $performance['event']['event_name'] ?? '', // P1-ATHLETE-2: Event Name
                $performance['score'] ?? '', // P1-ATHLETE-3: Score
                $performance['recognition'] ?? '-', // P1-ATHLETE-4: Recognition
                $performance['created_at'] ? date('Y-m-d', strtotime($performance['created_at'])) : '' // P1-ATHLETE-5: Date
            ];
        }

        return $data;
    }
}