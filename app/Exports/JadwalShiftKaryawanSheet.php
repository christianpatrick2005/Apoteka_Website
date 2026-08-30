<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class JadwalShiftKaryawanSheet implements FromView, WithTitle, WithEvents
{
    // Label hari dalam Bahasa Indonesia, disingkat 3 huruf (spt template asli: Sel, Rab, dst)
    private const HARI_ID = [
        1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab', 7 => 'Min',
    ];

    protected Collection $pegawai;
    protected Collection $shifts;
    protected Carbon $startDate;
    protected Carbon $endDate;

    // Kolom data tanggal dimulai dari kolom F (kolom 6), sesuai template
    private const FIRST_DATE_COL_INDEX = 6;
    // Baris pertama data pegawai (setelah 2 baris header di 7-8), sesuai template
    private const FIRST_DATA_ROW = 9;

    public function __construct(Collection $pegawai, Collection $shifts, Carbon $startDate, Carbon $endDate)
    {
        $this->pegawai = $pegawai;
        $this->shifts = $shifts;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Bangun label kolom tanggal, contoh: "Sel 1", "Rab 2", dst,
     * sesuai jumlah hari pada rentang periode yg dipilih.
     */
    protected function buildDateColumns(): array
    {
        $columns = [];
        $cursor = $this->startDate->copy();

        while ($cursor->lte($this->endDate)) {
            $columns[] = self::HARI_ID[$cursor->isoWeekday()] . ' ' . $cursor->day;
            $cursor->addDay();
        }

        return $columns;
    }

    public function view(): \Illuminate\Contracts\View\View
    {
        $dateColumns = $this->buildDateColumns();

        return view('exports.jadwal_template', [
            'pegawai' => $this->pegawai,
            'periodLabel' => $this->startDate->translatedFormat('j F Y') . ' - ' . $this->endDate->translatedFormat('j F Y'),
            'personnelCount' => $this->pegawai->count(),
            'monthLabel' => $this->startDate->translatedFormat('F Y'),
            'dateColumns' => $dateColumns,
        ]);
    }

    public function title(): string
    {
        return 'Jadwal Shift Karyawan';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $jumlahHari = $this->startDate->diffInDays($this->endDate) + 1;
                $jumlahPegawai = max($this->pegawai->count(), 1);

                // "Day Off" + jumlah shift dari DB
                $jumlahOpsiShift = $this->shifts->count() + 1;

                $firstCol = self::FIRST_DATE_COL_INDEX;
                $lastCol = self::FIRST_DATE_COL_INDEX + $jumlahHari - 1;

                $firstColLetter = Coordinate::stringFromColumnIndex($firstCol);
                $lastColLetter = Coordinate::stringFromColumnIndex($lastCol);

                $firstRow = self::FIRST_DATA_ROW;
                $lastRow = self::FIRST_DATA_ROW + $jumlahPegawai - 1;

                // Terapkan data validation (dropdown) ke setiap sel pada range tanggal x pegawai
                for ($row = $firstRow; $row <= $lastRow; $row++) {
                    for ($colIndex = $firstCol; $colIndex <= $lastCol; $colIndex++) {
                        $colLetter = Coordinate::stringFromColumnIndex($colIndex);

                        $validation = new DataValidation();
                        $validation->setType(DataValidation::TYPE_LIST);
                        $validation->setErrorStyle(DataValidation::STYLE_STOP);
                        $validation->setAllowBlank(true);
                        $validation->setShowInputMessage(true);
                        $validation->setShowErrorMessage(true);
                        $validation->setShowDropDown(true);
                        $validation->setErrorTitle('Shift tidak valid');
                        $validation->setError('Silakan pilih shift dari daftar dropdown.');
                        $validation->setPromptTitle('Pilih Shift');
                        $validation->setPrompt('Pilih salah satu shift dari daftar.');

                        // Sumber dropdown: sheet tersembunyi "Shift Options"
                        $validation->setFormula1("'Shift Options'!\$A\$1:\$A\${$jumlahOpsiShift}");

                        $sheet->getCell("{$colLetter}{$row}")->setDataValidation($validation);
                    }
                }
            },
        ];
    }
}
