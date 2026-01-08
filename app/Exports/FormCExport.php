<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class FormCExport implements WithEvents, ShouldAutoSize
{
    protected array $athletes;

    public function __construct(array $athletes)
    {
        $this->athletes = $athletes;
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
                $this->insertLogo($sheet, public_path('images/ched-logo.png'), 'X1');

                // ----------------------------
                // Main header and subtitle
                // ----------------------------
                $sheet->setCellValue('G1', 'Cebu Technological University - Main Campus');
                $sheet->mergeCells('G1:U1');
                $sheet->getStyle('G1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('G1')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $sheet->setCellValue('G2', 'Form C – Profile and Benefits of Student-Athletes');
                $sheet->mergeCells('G2:U2');
                $sheet->getStyle('G2')->getFont()->setItalic(true)->setSize(12);
                $sheet->getStyle('G2')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ----------------------------
                // Row heights
                // ----------------------------
                $sheet->getRowDimension(1)->setRowHeight(65);
                $sheet->getRowDimension(3)->setRowHeight(20);

                // ----------------------------
                // Table headers
                // ----------------------------
                $tableHeaders = [
                    '#', 'Name of Student', 'Age', 'Sports Program', 'Sex', 'Academic Course',
                    'Highest Level of Competition', 'Highest Accomplishment', 'International Competition',
                    'Regional Training', 'National Training', 'International Training',
                    'Training Frequency (Days/Week)', 'Training Hours/Day',
                    'Scholarship Status', 'Monthly Allowance', 'Board & Lodging', 'Medical Insurance',
                    'Training Uniforms', 'Average Tournament Allowance', 'Playing Uniforms Sponsorship',
                    'Playing Gears Sponsorship', 'Excused from Academic Obligations', 'Flexible Academic Schedule',
                    'Academic Tutorials Support'
                ];

                $col = 'A';
                foreach ($tableHeaders as $header) {
                    $sheet->setCellValue($col.'4', $header);
                    $col++;
                }

                $sheet->getStyle('A4:Y4')->getFont()->setBold(true);
                $sheet->getStyle('A4:Y4')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ----------------------------
                // Insert data starting row 5
                // ----------------------------
                $sheet->fromArray($this->formatDataArray(), null, 'A5');

                // Freeze header row
                $sheet->freezePane('A5');
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

        foreach ($this->athletes as $index => $athlete) {
            $rows[] = [
                $index + 1,
                $athlete['full_name'] ?? '',
                $athlete['age'] ?? '',
                $athlete['sport_name'] ?? '',
                $athlete['gender'] ?? '',
                $athlete['academic_course'] ?? '',
                $athlete['highest_competition_level'] ?? '',
                $athlete['highest_accomplishment'] ?? '',
                $athlete['international_competition_name'] ?? '',
                $athlete['training_seminars_regional'] ?? '',
                $athlete['training_seminars_national'] ?? '',
                $athlete['training_seminars_international'] ?? '',
                $athlete['training_frequency_days'] ?? '',
                $athlete['training_hours_per_day'] ?? '',
                $athlete['scholarship_status'] ?? '',
                $athlete['monthly_living_allowance'] ?? '',
                $athlete['board_lodging_support'] ?? '',
                $athlete['medical_insurance_support'] ?? '',
                $athlete['training_uniforms_support'] ?? '',
                $athlete['average_tournament_allowance'] ?? '',
                $athlete['playing_uniforms_sponsorship'] ?? '',
                $athlete['playing_gears_sponsorship'] ?? '',
                $athlete['excused_from_academic_obligations'] ?? '',
                $athlete['flexible_academic_schedule'] ?? '',
                $athlete['academic_tutorials_support'] ?? '',
            ];
        }

        return $rows;
    }
}
