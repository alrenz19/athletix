<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class FormBExport implements WithEvents, ShouldAutoSize
{
    protected array $sportsPrograms;

    public function __construct(array $sportsPrograms)
    {
        $this->sportsPrograms = $sportsPrograms;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ----------------------------
                // Logos
                // ----------------------------
                $this->insertLogo($sheet, public_path('images/ctu-logo.png'), 'G1');
                $this->insertLogo($sheet, public_path('images/ched-logo.png'), 'P1');

                // ----------------------------
                // Main header and subtitle
                // ----------------------------
                $sheet->setCellValue('H1', 'Cebu Technological University - Main Campus');
                $sheet->mergeCells('H1:O1');
                $sheet->getStyle('H1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('H1')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $sheet->setCellValue('H2', 'Form B – Sports Programs Report');
                $sheet->mergeCells('H2:O2');
                $sheet->getStyle('H2')->getFont()->setItalic(true)->setSize(12);
                $sheet->getStyle('H2')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ----------------------------
                // Row heights
                // ----------------------------
                $sheet->getRowDimension(1)->setRowHeight(65);
                $sheet->getRowDimension(3)->setRowHeight(25);
                $sheet->getRowDimension(4)->setRowHeight(20);

                // ----------------------------
                // Table headers
                // ----------------------------
                $tableHeaders = [
                    '#','ITEM CODE','B1-SPORTS-1','B1-SPORTS-2',
                    'B2-ASSOC-1','B2-ASSOC-2','B2-ASSOC-3a','B2-ASSOC-3b','B2-ASSOC-4',
                    'B3-LEAGUE-1','B3-LEAGUE-2','B3-LEAGUE-3','B3-LEAGUE-4','B3-LEAGUE-5',
                    'B3-LEAGUE-6','B3-LEAGUE-7','B3-LEAGUE-8','B3-LEAGUE-9','B3-LEAGUE-10',
                    'B4-WELL-1','B4-WELL-2','B4-WELL-3',
                    'B5-CPD-1','B5-CPD-2','B5-CPD-3','B5-CPD-4','B5-CPD-5','B5-CPD-6','B5-CPD-7'
                ];

                $col = 'A';
                foreach ($tableHeaders as $header) {
                    $sheet->setCellValue($col.'6', $header);
                    $col++;
                }

                // Style header
                $sheet->getStyle('A6:AE6')->getFont()->setBold(true);
                $sheet->getStyle('A6:AE6')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ----------------------------
                // Insert data starting row 7
                // ----------------------------
                $sheet->fromArray($this->formatDataArray(), null, 'A7');

                // Freeze header row
                $sheet->freezePane('A7');
            },
        ];
    }

    protected function insertLogo($sheet, string $path, string $cell)
    {
        if (file_exists($path)) {
            $drawing = new Drawing();
            $drawing->setPath($path);
            $drawing->setHeight(60);
            $drawing->setCoordinates($cell);
            $drawing->setOffsetX(10);
            $drawing->setWorksheet($sheet);
        }
    }

    protected function formatDataArray(): array
    {
        $rows = [];

        foreach ($this->sportsPrograms as $index => $sport) {
            $rows[] = [
                $index + 1, // #
                $sport['sport_code'] ?? 'B1-SPORT-' . ($index + 1),
                $sport['sport_name'] ?? '',
                $sport['category'] ?? '',
                $sport['assoc_1'] ?? '',
                $sport['assoc_2'] ?? '',
                $sport['assoc_3a'] ?? '',
                $sport['assoc_3b'] ?? '',
                $sport['assoc_other'] ?? '',
                $sport['league_active_1'] ?? '',
                $sport['league_count_1'] ?? '',
                $sport['league_active_2'] ?? '',
                $sport['league_count_2'] ?? '',
                $sport['league_active_3'] ?? '',
                $sport['league_count_3'] ?? '',
                $sport['league_active_4'] ?? '',
                $sport['league_count_4'] ?? '',
                $sport['league_active_5'] ?? '',
                $sport['league_count_5'] ?? '',
                $sport['well_1'] ?? '',
                $sport['well_2'] ?? '',
                $sport['well_3'] ?? '',
                $sport['cpd_1'] ?? '',
                $sport['cpd_2'] ?? '',
                $sport['cpd_3'] ?? '',
                $sport['cpd_4'] ?? '',
                $sport['cpd_5'] ?? '',
                $sport['cpd_6'] ?? '',
                $sport['cpd_7'] ?? '',
            ];
        }

        return $rows;
    }
}
