<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class FormFExport implements WithEvents, ShouldAutoSize
{
    protected array $nonVarsityClubs;

    public function __construct(array $nonVarsityClubs)
    {
        $this->nonVarsityClubs = $nonVarsityClubs;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // ----------------------------
                // Logos
                // ----------------------------
                $this->insertLogo($sheet, public_path('images/ctu-logo.png'), 'A1');
                $this->insertLogo($sheet, public_path('images/ched-logo.png'), 'H1');

                // ----------------------------
                // Main header and subtitle
                // ----------------------------
                $sheet->setCellValue('C1', 'Cebu Technological University - Main Campus');
                $sheet->mergeCells('C1:F1');
                $sheet->getStyle('C1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('C1')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $sheet->setCellValue('C2', 'Form F – Non-Varsity School-Based Sports Organizations');
                $sheet->mergeCells('C2:F2');
                $sheet->getStyle('C2')->getFont()->setItalic(true)->setSize(12);
                $sheet->getStyle('C2')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ----------------------------
                // Row heights
                // ----------------------------
                $sheet->getRowDimension(1)->setRowHeight(65);
                $sheet->getRowDimension(4)->setRowHeight(25);
                $sheet->getRowDimension(5)->setRowHeight(20);
                $sheet->getRowDimension(6)->setRowHeight(20);

                // ----------------------------
                // Table headers
                // ----------------------------
                $sheet->setCellValue('A4', 'FORM F: NON-VARSITY SCHOOL-BASED SPORTS ORGANIZATIONS');
                $sheet->mergeCells('A4:G4');
                $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A4')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('e0e7ff'); // Light indigo

                // Column headers - First row
                $headersRow1 = [
                    'ITEM CODE',
                    'F1-CLUB-1',
                    'F1-CLUB-2',
                    'F1-CLUB-3',
                    'F2-MOD-1',
                    'F3-ACTIV-1',
                    'F3-ACTIV-2'
                ];

                $col = 'A';
                foreach ($headersRow1 as $header) {
                    $sheet->setCellValue($col.'5', $header);
                    $col++;
                }

                // Column headers - Second row (descriptions)
                $headersRow2 = [
                    'Description',
                    'Sports',
                    'Sports (Sex)',
                    'Sports Club Name',
                    'Name of Designated Club Moderator',
                    'Main Program/Activity',
                    'Secondary Program/Activity'
                ];

                $col = 'A';
                foreach ($headersRow2 as $header) {
                    $sheet->setCellValue($col.'6', $header);
                    $col++;
                }

                // Style headers
                $sheet->getStyle('A5:G6')->getFont()->setBold(true);
                $sheet->getStyle('A5:G6')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ----------------------------
                // Insert data starting row 7
                // ----------------------------
                $this->insertClubsData($sheet);

                // Freeze header rows
                $sheet->freezePane('A7');

                // Add total count
                $totalRow = count($this->nonVarsityClubs) + 7;
                $sheet->setCellValue('A'.$totalRow, 'Total Clubs: ' . count($this->nonVarsityClubs));
                $sheet->mergeCells('A'.$totalRow.':G'.$totalRow);
                $sheet->getStyle('A'.$totalRow)->getFont()->setBold(true);
                $sheet->getStyle('A'.$totalRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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

    protected function insertClubsData($sheet)
    {
        $row = 7;
        
        foreach ($this->nonVarsityClubs as $index => $club) {
            $data = [
                $index + 1, // ITEM CODE
                $club['sport_name'] ?? '',
                $club['sports_sex'] ?? '',
                $club['sports_club_name'] ?? '',
                $club['club_moderator'] ?? '',
                $club['main_program_activity'] ?? '',
                $club['secondary_program_activity'] ?? ''
            ];

            $col = 'A';
            foreach ($data as $value) {
                $cell = $col . $row;
                $sheet->setCellValue($cell, $value);
                
                // Center align the first column (ITEM CODE)
                if ($col === 'A') {
                    $sheet->getStyle($cell)->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
                
                $col++;
            }
            $row++;
        }
    }
}