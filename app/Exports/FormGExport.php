<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class FormGExport implements WithEvents, ShouldAutoSize
{
    protected array $feedbackData;

    public function __construct(array $feedbackData)
    {
        $this->feedbackData = $feedbackData;
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
                $this->insertLogo($sheet, public_path('images/ched-logo.png'), 'I1');

                // ----------------------------
                // Main header and subtitle
                // ----------------------------
                $sheet->setCellValue('B1', 'Cebu Technological University - Main Campus');
                $sheet->mergeCells('B1:H1');
                $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('B1')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $sheet->setCellValue('B2', 'CHED Form G: Feedback');
                $sheet->mergeCells('B2:H2');
                $sheet->getStyle('B2')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('B2')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ----------------------------
                // Row heights
                // ----------------------------
                $sheet->getRowDimension(1)->setRowHeight(65);
                $sheet->getRowDimension(4)->setRowHeight(25);
                $sheet->getRowDimension(5)->setRowHeight(20);

                // ----------------------------
                // Table headers
                // ----------------------------
                $headersRow1 = [
                    'ITEM CODE',
                    'G1-FEEDBACK-1',
                    'G1-FEEDBACK-2',
                    'G1-FEEDBACK-3',
                    'G1-FEEDBACK-4',
                    'G1-FEEDBACK-5',
                    'G2-RESPONDENT-1',
                    'G2-RESPONDENT-2',
                    'G2-RESPONDENT-3'
                ];

                $headersRow2 = [
                    'Description',
                    'Template Improvements',
                    'Additional Data Suggestions',
                    'Difficult Data Items',
                    'Easy Data Items',
                    'Additional Comments',
                    'Respondent Name',
                    'Respondent Email',
                    'Submission Date'
                ];

                // First header row
                $col = 'A';
                foreach ($headersRow1 as $header) {
                    $sheet->setCellValue($col.'4', $header);
                    $col++;
                }

                // Second header row (descriptions)
                $col = 'A';
                foreach ($headersRow2 as $header) {
                    $sheet->setCellValue($col.'5', $header);
                    $col++;
                }

                // Style headers
                $sheet->getStyle('A4:I5')->getFont()->setBold(true);
                $sheet->getStyle('A4:I5')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ----------------------------
                // Insert data
                // ----------------------------
                $this->insertFeedbackData($sheet);

                // Adjust row height for text content
                $sheet->getRowDimension(6)->setRowHeight(80);

                // Wrap text for feedback columns
                $sheet->getStyle('B6:F6')->getAlignment()->setWrapText(true);
                
                // Freeze header rows
                $sheet->freezePane('A6');
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

    protected function insertFeedbackData($sheet)
    {
        $data = [
            'G-FEED-1', // ITEM CODE
            $this->feedbackData['template_improvements'] ?? 'No response provided',
            $this->feedbackData['additional_data'] ?? 'No response provided',
            $this->feedbackData['difficult_data'] ?? 'No response provided',
            $this->feedbackData['easy_data'] ?? 'No response provided',
            $this->feedbackData['additional_comments'] ?? 'No response provided',
            $this->feedbackData['respondent_name'] ?? '',
            $this->feedbackData['respondent_email'] ?? '',
            $this->getSubmissionDate()
        ];

        $row = 6;
        $col = 'A';
        foreach ($data as $value) {
            $cell = $col . $row;
            $sheet->setCellValue($cell, $value);
            
            // Left align text for feedback columns (B-F)
            if (in_array($col, ['B', 'C', 'D', 'E', 'F'])) {
                $sheet->getStyle($cell)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
            } else {
                $sheet->getStyle($cell)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            
            $col++;
        }
    }

    protected function getSubmissionDate()
    {
        if (isset($this->feedbackData['submitted_at'])) {
            return date('Y-m-d', strtotime($this->feedbackData['submitted_at']));
        }
        
        if (isset($this->feedbackData['created_at'])) {
            return date('Y-m-d', strtotime($this->feedbackData['created_at']));
        }
        
        return date('Y-m-d');
    }
}