<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class FormEExport implements WithEvents, ShouldAutoSize
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
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
                $sheet->setCellValue('H2', 'Form E – Sports Budget and Expenditure');
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
                $tableHeaders = array_merge(
                    ['ITEM CODE'],
                    array_keys($this->data['fund_sources'] ?? []),
                    array_keys($this->data['expenditures'] ?? [])
                );

                $col = 'A';
                foreach ($tableHeaders as $header) {
                    $sheet->setCellValue($col.'6', strtoupper(str_replace('_',' ', $header)));
                    $col++;
                }

                // Style table header
                $lastCol = chr(ord('A') + count($tableHeaders) - 1);
                $sheet->getStyle("A6:{$lastCol}6")->getFont()->setBold(true);
                $sheet->getStyle("A6:{$lastCol}6")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("A6:{$lastCol}6")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ----------------------------
                // Insert raw data starting at row 7
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
        $row = ['E-BUDGET-1'];

        // Fund sources
        foreach ($this->data['fund_sources'] ?? [] as $value) {
            $row[] = $value ?? 0;
        }

        // Expenditures
        foreach ($this->data['expenditures'] ?? [] as $value) {
            $row[] = $value ?? 0;
        }

        return [$row];
    }
}
