<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

/**
 * Sheet tersembunyi yang menjadi sumber data validation (dropdown) pada
 * sheet "Jadwal Shift Karyawan". Formatnya meniru Sheet1 pada template asli:
 * "Day Off" lalu "{NamaShift} || {JamMasuk} - {JamKeluar} || {Toleransi}m"
 */
class ShiftOptionsSheet implements FromCollection, WithTitle, WithEvents
{
    protected Collection $shifts;

    public function __construct(Collection $shifts)
    {
        $this->shifts = $shifts;
    }

    public function collection(): Collection
    {
        $rows = collect([['Day Off']]);

        foreach ($this->shifts as $shift) {
            $jamMasuk = $shift->jam_masuk ?? '-';
            $jamKeluar = $shift->jam_keluar ?? '-';
            $toleransi = $shift->toleransi ?? 0;

            $rows->push([
                sprintf('%s || %s - %s || %sm', $shift->nama_shift, $jamMasuk, $jamKeluar, $toleransi),
            ]);
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Shift Options';
    }

    public function registerEvents(): array
    {
        return [
            // Sembunyikan sheet ini dari user, spt Sheet1 pada template asli
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setSheetState(
                    \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN
                );
            },
        ];
    }
}
