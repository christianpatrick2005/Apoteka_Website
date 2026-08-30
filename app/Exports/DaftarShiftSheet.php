<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class DaftarShiftSheet implements FromView, WithTitle
{
    protected Collection $shifts;
    protected Carbon $startDate;
    protected Carbon $endDate;
    protected int $personnelCount;

    public function __construct(Collection $shifts, Carbon $startDate, Carbon $endDate, int $personnelCount)
    {
        $this->shifts = $shifts;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->personnelCount = $personnelCount;
    }

    public function view(): \Illuminate\Contracts\View\View
    {
        return view('exports.daftar_shift', [
            'shifts' => $this->shifts,
            'periodLabel' => $this->startDate->translatedFormat('j F Y') . ' - ' . $this->endDate->translatedFormat('j F Y'),
            'personnelCount' => $this->personnelCount,
        ]);
    }

    public function title(): string
    {
        return 'Daftar Shift';
    }
}
