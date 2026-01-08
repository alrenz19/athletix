<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class FormDExport implements WithEvents, ShouldAutoSize
{
    protected array $coaches;

    public function __construct(array $coaches)
    {
        $this->coaches = $coaches;
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
                $this->insertLogo($sheet, public_path('images/ched-logo.png'), 'Y1');

                // ----------------------------
                // Main header
                // ----------------------------
                $sheet->setCellValue('H1', 'Cebu Technological University - Main Campus');
                $sheet->mergeCells('H1:O1');
                $sheet->getStyle('H1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('H1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // Subtitle
                $sheet->setCellValue('H2', 'Form D – Sports Personnel Report');
                $sheet->mergeCells('H2:O2');
                $sheet->getStyle('H2')->getFont()->setItalic(true)->setSize(12);
                $sheet->getStyle('H2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('H2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ----------------------------
                // Row heights
                // ----------------------------
                $sheet->getRowDimension(1)->setRowHeight(65); // logos
                $sheet->getRowDimension(3)->setRowHeight(25);
                $sheet->getRowDimension(4)->setRowHeight(20);
                $sheet->getRowDimension(6)->setRowHeight(25);

                // ----------------------------
                // Table headers
                // ----------------------------
                $headers = [
                    '#','ITEM CODE','D1-PROFILE-1','D1-PROFILE-2','D1-PROFILE-3','D1-PROFILE-4','D1-PROFILE-5','D1-PROFILE-6','D1-PROFILE-7','D1-PROFILE-8','D1-PROFILE-9',
                    'D2-EXP-1','D2-EXP-2','D2-EXP-3','D2-EXP-4','D2-EXP-5','D2-EXP-6',
                    'D3-LIC-1','D3-LIC-2','D3-LIC-3','D3-LIC-4',
                    'D4-EDUC-1','D4-EDUC-2','D4-EDUC-3','D4-EDUC-4'
                ];

                $col = 'A';
                foreach ($headers as $header) {
                    $sheet->setCellValue($col.'6', $header);
                    $col++;
                }

                $sheet->getStyle('A6:X6')->getFont()->setBold(true);
                $sheet->getStyle('A6:X6')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ----------------------------
                // Insert data starting row 7
                // ----------------------------
                $sheet->fromArray($this->formatDataArray(), null, 'A7');

                // ----------------------------
                // Freeze header row
                // ----------------------------
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

        foreach ($this->coaches as $index => $coach) {
            $rows[] = [
                $index + 1,
                'D-PERS-' . ($index + 1),
                $coach['full_name'] ?? '-',
                $coach['age'] ?? '-',
                $coach['gender'] ?? '-',
                $coach['sport']['sport_name'] ?? '-',
                $coach['current_position_title'] ?? '-',
                $coach['sports_program_position'] ?? '-',
                $coach['employment_status'] ?? '-',
                $coach['monthly_salary'] ?? '-',
                $coach['years_experience'] ?? '-',
                $coach['was_previous_athlete'] ? 'Yes' : 'No',
                $coach['highest_competition_level'] ?? '-',
                $coach['highest_accomplishment_athlete'] ?? '-',
                $coach['international_competition_athlete'] ?? '-',
                $coach['highest_accomplishment_coach'] ?? '-',
                $coach['international_competition_coach'] ?? '-',
                $coach['regional_membership'] ? 'Yes' : 'No',
                $coach['national_membership'] ? 'Yes' : 'No',
                $coach['international_membership'] ? 'Yes' : 'No',
                $coach['international_membership_name'] ?? '-',
                $coach['highest_degree'] ?? '-',
                $coach['bachelor_degree'] ?? '-',
                $coach['master_degree'] ?? '-',
                $coach['doctorate_degree'] ?? '-',
            ];
        }

        return $rows;
    }
}
