<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AthleteReportExport implements FromCollection, WithHeadings
{
    protected $athletes;

    public function __construct(Collection $athletes)
    {
        $this->athletes = $athletes;
    }

    public function collection()
    {
        return $this->athletes->map(function ($athlete) {
            return [
                $athlete->school_id,
                $athlete->full_name,
                $athlete->year_level,
                $athlete->sport->sport_name ?? '-',
                $athlete->conditions,
                $athlete->awards->pluck('title')->join(', '),
                $athlete->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Course/Year', 'Sport', 'Status', 'Awards', 'Created At'];
    }
}
