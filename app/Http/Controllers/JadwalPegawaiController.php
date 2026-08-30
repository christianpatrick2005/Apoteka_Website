<?php

namespace App\Http\Controllers;

use App\Models\JadwalPegawai;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;
use Carbon\Carbon;
use App\Exports\TemplateJadwalExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class JadwalPegawaiController
{
    private const FIRST_DATE_COL_INDEX = 6; // F
    private const FIRST_DATA_ROW = 9;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = JadwalPegawai::with(['user','shift'])->get();
        return view('ManageShift', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('forms.FormShift');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari mobile
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
            'tanggal' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Mohon periksa kembali form Anda.');
        }

        JadwalPegawai::create($request->only(['user_id','shift_id','tanggal']));

        return back()->with('success', 'Jadwal berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(JadwalPegawai $jadwalPegawai)
    {
        $jadwalPegawai->load('user','shift');

        return view('ManageShift', compact('jadwalPegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalPegawai $jadwalPegawai)
    {
        return view('forms.FormShift', compact('jadwalPegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JadwalPegawai $jadwalPegawai)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'shift_id' => 'sometimes|exists:shifts,id',
            'tanggal' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Mohon periksa kembali form Anda.');
        }

        $jadwalPegawai->update($request->only(['user_id','shift_id','tanggal']));

        return back()->with('success', 'Data jadwal berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalPegawai $jadwalPegawai)
    {
        // Hapus data dari MySQL
        $jadwalPegawai->delete();

        return back()->with('success', 'Data dan file jadwal pegawai berhasil dihapus secara permanen');
    }

    public function destroyAll($user_id)
    {
        // Menghapus semua jadwal yang memiliki user_id tersebut
        JadwalPegawai::where('user_id', $user_id)->delete();

        return back()->with('success', 'Semua jadwal untuk pegawai tersebut berhasil dihapus secara permanen.');
    }

    public function downloadTemplateJadwal(Request $request)
    {
        $request->validate([
            'start' => 'nullable|date',
            'end' => 'nullable|date|after_or_equal:start',
        ]);

        $start = $request->query('start')
            ? Carbon::parse($request->query('start'))->startOfDay()
            : now()->startOfMonth();

        $end = $request->query('end')
            ? Carbon::parse($request->query('end'))->startOfDay()
            : $start->copy()->endOfMonth();

        $filename = sprintf(
            'Template_Upload_Jadwal_Shift_%s_-_%s.xlsx',
            $start->format('d_M_Y'),
            $end->format('d_M_Y')
        );

        return Excel::download(new TemplateJadwalExport($start, $end), $filename);
    }

    /**
     * Import jadwal dari template wide/matrix (satu kolom per tanggal).
     * Ini MENGGANTI importExcel lama, karena template lama (format panjang:
     * kolom nama_pegawai / nama_shift / tanggal per baris) sudah tidak
     * cocok dengan struktur template baru.
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls',
        ]);

        $path = $request->file('file_excel')->getRealPath();

        try {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('Jadwal Shift Karyawan');

            if (!$sheet) {
                return back()->with('error', 'Sheet "Jadwal Shift Karyawan" tidak ditemukan di file yang diupload. Pastikan Anda mengupload file hasil download template.');
            }

            // Ambil tanggal mulai periode dari sel C4, contoh isinya:
            // "1 September 2026 - 30 September 2026"
            $periodRaw = trim((string) $sheet->getCell('C4')->getValue());
            $parts = array_map('trim', explode('-', $periodRaw, 2));

            if (empty($parts[0])) {
                return back()->with('error', 'Sel periode (C4) kosong atau formatnya berubah. Jangan mengubah struktur header template.');
            }

            $startDate = Carbon::parse($parts[0]);

            $highestRow = $sheet->getHighestDataRow();
            $highestColIndex = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());

            $imported = 0;
            $dihapus = 0;
            $dilewati = 0;
            $errors = [];

            for ($row = self::FIRST_DATA_ROW; $row <= $highestRow; $row++) {
                $nomorKtp = trim((string) $sheet->getCell("C{$row}")->getValue());
                $namaPegawai = trim((string) $sheet->getCell("D{$row}")->getValue());

                if ($nomorKtp === '' && $namaPegawai === '') {
                    continue; // baris kosong, lewati
                }

                $pegawai = User::where('name', $namaPegawai)->first();

                if (!$pegawai && $nomorKtp !== '') {
                    $pegawai = User::where('nomor_ktp', $nomorKtp)->first();
                }

                if (!$pegawai) {
                    $dilewati++;
                    $errors[] = "Baris {$row}: pegawai '{$namaPegawai}' (KTP: {$nomorKtp}) tidak ditemukan di database.";
                    continue;
                }

                for ($col = self::FIRST_DATE_COL_INDEX; $col <= $highestColIndex; $col++) {
                    $colLetter = Coordinate::stringFromColumnIndex($col);
                    $cellValue = trim((string) $sheet->getCell("{$colLetter}{$row}")->getValue());

                    if ($cellValue === '') {
                        continue; // sel dikosongkan, tidak diubah
                    }

                    $tanggal = $startDate->copy()
                        ->addDays($col - self::FIRST_DATE_COL_INDEX)
                        ->format('Y-m-d');

                    // "Day Off" -> hapus jadwal shift yg mungkin sudah ada di tanggal itu
                    if (strcasecmp($cellValue, 'Day Off') === 0) {
                        $hapus = JadwalPegawai::where('user_id', $pegawai->id)
                            ->where('tanggal', $tanggal)
                            ->delete();
                        $dihapus += $hapus;
                        continue;
                    }

                    // Format dropdown: "Security Pagi || 07:00 - 19:00 || 15m"
                    // Ambil bagian nama shift saja (sebelum "||")
                    $namaShift = trim(explode('||', $cellValue)[0]);
                    $shift = Shift::where('nama_shift', $namaShift)->first();

                    if (!$shift) {
                        $dilewati++;
                        $errors[] = "Baris {$row}, kolom {$colLetter}: shift '{$namaShift}' tidak ditemukan di database.";
                        continue;
                    }

                    JadwalPegawai::updateOrCreate(
                        ['user_id' => $pegawai->id, 'tanggal' => $tanggal],
                        ['shift_id' => $shift->id]
                    );

                    $imported++;
                }
            }

            $message = "Import selesai: {$imported} jadwal disimpan, {$dihapus} ditandai Day Off.";
            if ($dilewati > 0) {
                $message .= " {$dilewati} sel dilewati karena data tidak cocok.";
            }

            return back()->with($dilewati > 0 ? 'warning' : 'success', $message)
                ->with('import_errors', $errors);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

}
