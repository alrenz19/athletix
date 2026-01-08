<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class FormEExport implements WithEvents, ShouldAutoSize
{
    protected array $budgetData;

    public function __construct(array $budgetData)
    {
        $this->budgetData = $budgetData;
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

                $sheet->setCellValue('C2', 'Form E – Sports Budget and Expenditure');
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

                // ----------------------------
                // SECTION E1: Fund Sources Header
                // ----------------------------
                $sheet->setCellValue('A4', 'SECTION E1: FUND SOURCES');
                $sheet->mergeCells('A4:B4');
                $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A4')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('dbeafe'); // Light blue

                // Fund Sources Headers
                $fundSourceHeaders = [
                    'ITEM CODE',
                    'Athletic Fee Per Student Per Semester',
                    'Collection from Athletic Fees/Miscellaneous',
                    'Collection from Donors',
                    'Fundraising and/or Income Generating Programs',
                    'Funding Support from Local Government funds/allocations',
                    'Funding Support from National Government funds/allocations',
                    'Other Sources Not Included Above',
                    'ESTIMATED AMOUNT'
                ];

                $col = 'A';
                foreach ($fundSourceHeaders as $header) {
                    $sheet->setCellValue($col.'5', $header);
                    $col++;
                }

                // ----------------------------
                // SECTION E2: Expenditures Header
                // ----------------------------
                $sheet->setCellValue('J4', 'SECTION E2: EXPENDITURES');
                $sheet->mergeCells('J4:K4');
                $sheet->getStyle('J4')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('J4')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('dcfce7'); // Light green

                // Expenditure Headers
                $expenditureHeaders = $this->getExpenditureHeaders();
                $col = 'J';
                foreach ($expenditureHeaders as $header) {
                    $sheet->setCellValue($col.'5', $header);
                    $col++;
                }

                // Style all headers
                $lastCol = --$col;
                $sheet->getStyle('A5:'.$lastCol.'5')->getFont()->setBold(true);
                $sheet->getStyle('A5:'.$lastCol.'5')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ----------------------------
                // Insert data
                // ----------------------------
                $this->insertFundSourcesData($sheet);
                $this->insertExpendituresData($sheet);

                // Add summary section
                $this->addSummarySection($sheet);

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

    protected function getExpenditureHeaders(): array
    {
        return [
            'ITEM CODE',
            // Student Athletes
            'Scholarships & fees for MALE ATHLETES',
            'Scholarships & fees for FEMALE ATHLETES',
            'Monthly Living Allowances for all athletes',
            'Training Allowances (excluding living allowances)',
            'Board and Lodging for Student Athletes',
            'Training Fees for Sports Camps/Clinics',
            'Medical expenses & insurance for athletes',
            'Vitamins and Medicines for Student Athletes',
            'Other expenses for athletes',
            // Personnel
            'Salaries for Athletic Director',
            'Salaries for Head Coaches',
            'Salaries for Assistant Coaches',
            'Salaries for Body Conditioning Trainers',
            'Salaries for Maintenance Staff',
            'Salaries of Other Athletic Personnel',
            'Personnel Uniforms & Supplies',
            'Training and Seminar Fees for Personnel',
            'Other expenses for Personnel',
            // Competitions
            'Entry/Registration Fees for Competitions',
            'Game/Competition Allowances for Athletes',
            'Game/Competition Incentives for Athletes',
            'Game/Competition Incentives for Coaches',
            'Parade Uniforms for Athletes and Personnel',
            'Game Uniforms for Athletes and Personnel',
            'Honorarium for Coaches during Competition',
            'Honorarium for Technical Officials',
            'Honorarium for Other Staff during Competition',
            'Sports Equipment & Gadgets for Competitions',
            'Board and Lodging during Competition',
            'Transportation during Competition',
            'First Aid Expenses during Competition',
            'Athletic Association Membership Fees',
            'Other expenses related to games/competition',
            // Intramurals
            'Rental of Game Venues for Intrams',
            'Game Uniforms for Intrams',
            'Honorarium for Technical Officials for Intrams',
            'Other expenses related to Intrams',
            // Facilities
            'Renovation/Upgrading of Facilities',
            'Acquisition of Sports Equipment',
            'Supplies for maintenance of facilities',
            'Other expenses related to facilities',
            'ESTIMATED AMOUNT OF EXPENDITURE'
        ];
    }

    protected function insertFundSourcesData($sheet)
    {
        $fundSources = $this->budgetData['fund_sources'] ?? [];
        
        $data = [
            'E-BUDGET-1',
            $fundSources['athletic_fee_per_student'] ?? 0,
            $fundSources['collection_athletic_fees'] ?? 0,
            $fundSources['collection_donors'] ?? 0,
            $fundSources['fundraising_income'] ?? 0,
            $fundSources['local_govt_funding'] ?? 0,
            $fundSources['national_govt_funding'] ?? 0,
            $fundSources['other_sources'] ?? 0,
            array_sum($fundSources)
        ];

        $row = 6;
        $col = 'A';
        foreach ($data as $value) {
            $cell = $col . $row;
            if (is_numeric($value)) {
                $sheet->setCellValue($cell, $value);
                $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0.00');
            } else {
                $sheet->setCellValue($cell, $value);
            }
            $col++;
        }
    }

    protected function insertExpendituresData($sheet)
    {
        $expenditures = $this->budgetData['expenditures'] ?? [];
        
        $data = [
            'E-EXPEND-1',
            // Student Athletes
            $expenditures['scholarships_male'] ?? 0,
            $expenditures['scholarships_female'] ?? 0,
            $expenditures['monthly_allowances'] ?? 0,
            $expenditures['training_allowances'] ?? 0,
            $expenditures['board_lodging'] ?? 0,
            $expenditures['training_fees'] ?? 0,
            $expenditures['medical_expenses'] ?? 0,
            $expenditures['vitamins_medicines'] ?? 0,
            $expenditures['other_athlete_expenses'] ?? 0,
            // Personnel
            $expenditures['salary_athletic_director'] ?? 0,
            $expenditures['salary_head_coaches'] ?? 0,
            $expenditures['salary_assistant_coaches'] ?? 0,
            $expenditures['salary_trainers'] ?? 0,
            $expenditures['salary_maintenance_staff'] ?? 0,
            $expenditures['salary_other_personnel'] ?? 0,
            $expenditures['personnel_uniforms'] ?? 0,
            $expenditures['personnel_training'] ?? 0,
            $expenditures['other_personnel_expenses'] ?? 0,
            // Competitions
            $expenditures['competition_fees'] ?? 0,
            $expenditures['game_allowances_athletes'] ?? 0,
            $expenditures['game_incentives_athletes'] ?? 0,
            $expenditures['game_incentives_coaches'] ?? 0,
            $expenditures['parade_uniforms'] ?? 0,
            $expenditures['game_uniforms'] ?? 0,
            $expenditures['honorarium_coaches'] ?? 0,
            $expenditures['honorarium_officials'] ?? 0,
            $expenditures['honorarium_staff'] ?? 0,
            $expenditures['sports_equipment'] ?? 0,
            $expenditures['board_lodging_competition'] ?? 0,
            $expenditures['transportation_competition'] ?? 0,
            $expenditures['first_aid_competition'] ?? 0,
            $expenditures['association_membership'] ?? 0,
            $expenditures['other_competition_expenses'] ?? 0,
            // Intramurals
            $expenditures['venue_rental_intramurals'] ?? 0,
            $expenditures['uniforms_intramurals'] ?? 0,
            $expenditures['honorarium_officials_intramurals'] ?? 0,
            $expenditures['other_intramurals_expenses'] ?? 0,
            // Facilities
            $expenditures['facility_renovation'] ?? 0,
            $expenditures['sports_equipment_acquisition'] ?? 0,
            $expenditures['maintenance_supplies'] ?? 0,
            $expenditures['other_facility_expenses'] ?? 0,
            array_sum($expenditures)
        ];

        $row = 6;
        $col = 'J';
        foreach ($data as $value) {
            $cell = $col . $row;
            if (is_numeric($value)) {
                $sheet->setCellValue($cell, $value);
                $sheet->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0.00');
            } else {
                $sheet->setCellValue($cell, $value);
            }
            $col++;
        }
    }

    protected function addSummarySection($sheet)
    {
        $fundSources = $this->budgetData['fund_sources'] ?? [];
        $expenditures = $this->budgetData['expenditures'] ?? [];
        
        $totalBudget = array_sum($fundSources);
        $totalExpenditures = array_sum($expenditures);
        $balance = $totalBudget - $totalExpenditures;

        // Summary section starting row
        $startRow = 8;

        $sheet->setCellValue('A'.$startRow, 'SUMMARY');
        $sheet->mergeCells('A'.$startRow.':B'.$startRow);
        $sheet->getStyle('A'.$startRow)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A'.$startRow)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('fef3c7'); // Light amber

        $sheet->setCellValue('A'.($startRow+1), 'Estimated Budget:');
        $sheet->setCellValue('B'.($startRow+1), $totalBudget);
        $sheet->getStyle('B'.($startRow+1))->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->setCellValue('A'.($startRow+2), 'Estimated Expenditures:');
        $sheet->setCellValue('B'.($startRow+2), $totalExpenditures);
        $sheet->getStyle('B'.($startRow+2))->getNumberFormat()->setFormatCode('#,##0.00');

        $sheet->setCellValue('A'.($startRow+3), 'Balance:');
        $sheet->setCellValue('B'.($startRow+3), $balance);
        $sheet->getStyle('B'.($startRow+3))->getNumberFormat()->setFormatCode('#,##0.00');
        
        // Color balance based on value
        $balanceStyle = $sheet->getStyle('B'.($startRow+3));
        if ($balance < 0) {
            $balanceStyle->getFont()->getColor()->setRGB('FF0000'); // Red for negative
        } else {
            $balanceStyle->getFont()->getColor()->setRGB('008000'); // Green for positive
        }
    }
}

class FormECsvExport implements FromArray, WithHeadings, WithTitle
{
    protected $budgetData;

    public function __construct($budgetData)
    {
        $this->budgetData = $budgetData;
    }

    public function array(): array
    {
        $fundSources = $this->budgetData['fund_sources'] ?? [];
        $expenditures = $this->budgetData['expenditures'] ?? [];

        return [[
            'E-BUDGET-1',
            // Fund Sources
            $fundSources['athletic_fee_per_student'] ?? 0,
            $fundSources['collection_athletic_fees'] ?? 0,
            $fundSources['collection_donors'] ?? 0,
            $fundSources['fundraising_income'] ?? 0,
            $fundSources['local_govt_funding'] ?? 0,
            $fundSources['national_govt_funding'] ?? 0,
            $fundSources['other_sources'] ?? 0,
            array_sum($fundSources),
            // Expenditures
            'E-EXPEND-1',
            // Student Athletes
            $expenditures['scholarships_male'] ?? 0,
            $expenditures['scholarships_female'] ?? 0,
            $expenditures['monthly_allowances'] ?? 0,
            $expenditures['training_allowances'] ?? 0,
            $expenditures['board_lodging'] ?? 0,
            $expenditures['training_fees'] ?? 0,
            $expenditures['medical_expenses'] ?? 0,
            $expenditures['vitamins_medicines'] ?? 0,
            $expenditures['other_athlete_expenses'] ?? 0,
            // Personnel
            $expenditures['salary_athletic_director'] ?? 0,
            $expenditures['salary_head_coaches'] ?? 0,
            $expenditures['salary_assistant_coaches'] ?? 0,
            $expenditures['salary_trainers'] ?? 0,
            $expenditures['salary_maintenance_staff'] ?? 0,
            $expenditures['salary_other_personnel'] ?? 0,
            $expenditures['personnel_uniforms'] ?? 0,
            $expenditures['personnel_training'] ?? 0,
            $expenditures['other_personnel_expenses'] ?? 0,
            // Competitions
            $expenditures['competition_fees'] ?? 0,
            $expenditures['game_allowances_athletes'] ?? 0,
            $expenditures['game_incentives_athletes'] ?? 0,
            $expenditures['game_incentives_coaches'] ?? 0,
            $expenditures['parade_uniforms'] ?? 0,
            $expenditures['game_uniforms'] ?? 0,
            $expenditures['honorarium_coaches'] ?? 0,
            $expenditures['honorarium_officials'] ?? 0,
            $expenditures['honorarium_staff'] ?? 0,
            $expenditures['sports_equipment'] ?? 0,
            $expenditures['board_lodging_competition'] ?? 0,
            $expenditures['transportation_competition'] ?? 0,
            $expenditures['first_aid_competition'] ?? 0,
            $expenditures['association_membership'] ?? 0,
            $expenditures['other_competition_expenses'] ?? 0,
            // Intramurals
            $expenditures['venue_rental_intramurals'] ?? 0,
            $expenditures['uniforms_intramurals'] ?? 0,
            $expenditures['honorarium_officials_intramurals'] ?? 0,
            $expenditures['other_intramurals_expenses'] ?? 0,
            // Facilities
            $expenditures['facility_renovation'] ?? 0,
            $expenditures['sports_equipment_acquisition'] ?? 0,
            $expenditures['maintenance_supplies'] ?? 0,
            $expenditures['other_facility_expenses'] ?? 0,
            array_sum($expenditures)
        ]];
    }

    public function headings(): array
    {
        return [
            // Fund Sources
            'ITEM CODE',
            'Athletic Fee Per Student Per Semester',
            'Collection from Athletic Fees/Miscellaneous',
            'Collection from Donors',
            'Fundraising and/or Income Generating Programs',
            'Funding Support from Local Government funds/allocations',
            'Funding Support from National Government funds/allocations',
            'Other Sources Not Included Above',
            'ESTIMATED AMOUNT',
            // Expenditures
            'ITEM CODE',
            'Scholarships & fees for MALE ATHLETES',
            'Scholarships & fees for FEMALE ATHLETES',
            'Monthly Living Allowances for all athletes',
            'Training Allowances (excluding living allowances)',
            'Board and Lodging for Student Athletes',
            'Training Fees for Sports Camps/Clinics',
            'Medical expenses & insurance for athletes',
            'Vitamins and Medicines for Student Athletes',
            'Other expenses for athletes',
            'Salaries for Athletic Director',
            'Salaries for Head Coaches',
            'Salaries for Assistant Coaches',
            'Salaries for Body Conditioning Trainers',
            'Salaries for Maintenance Staff',
            'Salaries of Other Athletic Personnel',
            'Personnel Uniforms & Supplies',
            'Training and Seminar Fees for Personnel',
            'Other expenses for Personnel',
            'Entry/Registration Fees for Competitions',
            'Game/Competition Allowances for Athletes',
            'Game/Competition Incentives for Athletes',
            'Game/Competition Incentives for Coaches',
            'Parade Uniforms for Athletes and Personnel',
            'Game Uniforms for Athletes and Personnel',
            'Honorarium for Coaches during Competition',
            'Honorarium for Technical Officials',
            'Honorarium for Other Staff during Competition',
            'Sports Equipment & Gadgets for Competitions',
            'Board and Lodging during Competition',
            'Transportation during Competition',
            'First Aid Expenses during Competition',
            'Athletic Association Membership Fees',
            'Other expenses related to games/competition',
            'Rental of Game Venues for Intrams',
            'Game Uniforms for Intrams',
            'Honorarium for Technical Officials for Intrams',
            'Other expenses related to Intrams',
            'Renovation/Upgrading of Facilities',
            'Acquisition of Sports Equipment',
            'Supplies for maintenance of facilities',
            'Other expenses related to facilities',
            'ESTIMATED AMOUNT OF EXPENDITURE'
        ];
    }

    public function title(): string
    {
        return 'Budget and Expenditure';
    }
}