<?php

namespace App\Http\Controllers\Api;

use App\Models\JadwalPegawai;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class JadwalPegawaiController
{
    private const FIRST_DATE_COL_INDEX = 6;
    private const FIRST_DATA_ROW = 9;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = JadwalPegawai::with(['user', 'shift'])->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar jadwal pegawai berhasil diambil.',
            'data'    => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required|exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
            'tanggal'  => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Mohon periksa kembali form Anda.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $jadwal = JadwalPegawai::create($request->only(['user_id', 'shift_id', 'tanggal']));

        return response()->json([
            'status'  => 'success',
            'message' => 'Jadwal berhasil ditambahkan.',
            'data'    => $jadwal
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(JadwalPegawai $jadwalPegawai)
    {
        $jadwalPegawai->load('user', 'shift');

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail jadwal pegawai berhasil diambil.',
            'data'    => $jadwalPegawai
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JadwalPegawai $jadwalPegawai)
    {
        $validator = Validator::make($request->all(), [
            'user_id'  => 'sometimes|exists:users,id',
            'shift_id' => 'sometimes|exists:shifts,id',
            'tanggal'  => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Mohon periksa kembali form Anda.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $jadwalPegawai->update($request->only(['user_id', 'shift_id', 'tanggal']));

        return response()->json([
            'status'  => 'success',
            'message' => 'Data jadwal berhasil diperbarui.',
            'data'    => $jadwalPegawai
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalPegawai $jadwalPegawai)
    {
        $jadwalPegawai->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data jadwal pegawai berhasil dihapus.'
        ], 200);
    }

    public function destroyAll($user_id)
    {
        JadwalPegawai::where('user_id', $user_id)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Semua jadwal untuk pegawai tersebut berhasil dihapus.'
        ], 200);
    }

    /**
     * Import jadwal dari Excel.
     */
    public function importExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_excel' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'File tidak valid.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $path = $request->file('file_excel')->getRealPath();

        try {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('Jadwal Shift Karyawan');

            if (!$sheet) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Sheet "Jadwal Shift Karyawan" tidak ditemukan di file yang diupload.'
                ], 400);
            }

            $periodRaw = trim((string) $sheet->getCell('C4')->getValue());
            $parts = array_map('trim', explode('-', $periodRaw, 2));

            if (empty($parts[0])) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Sel periode (C4) kosong atau formatnya berubah.'
                ], 400);
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
                    continue;
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
                        continue;
                    }

                    $tanggal = $startDate->copy()
                        ->addDays($col - self::FIRST_DATE_COL_INDEX)
                        ->format('Y-m-d');

                    if (strcasecmp($cellValue, 'Day Off') === 0) {
                        $hapus = JadwalPegawai::where('user_id', $pegawai->id)
                            ->where('tanggal', $tanggal)
                            ->delete();
                        $dihapus += $hapus;
                        continue;
                    }

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

            return response()->json([
                'status'  => 'success',
                'message' => "Import selesai: {$imported} jadwal disimpan, {$dihapus} ditandai Day Off.",
                'summary' => [
                    'imported' => $imported,
                    'deleted'  => $dihapus,
                    'skipped'  => $dilewati,
                    'errors'   => $errors
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}