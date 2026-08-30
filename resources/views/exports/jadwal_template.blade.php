<table>
    <tbody>
        {{-- Baris 1: Judul Utama (A1:F1) --}}
        <tr>
            <th colspan="6" style="font-weight: bold; font-size: 16px; text-align: left; vertical-align: middle;">
                EMPLOYEE SHIFTING SCHEDULE<br>Jadwal Shifting Personalia
            </th>
        </tr>

        {{-- Baris 2: kosong (spacer) --}}
        <tr><td></td></tr>

        {{-- Baris 3: garis pemisah kosong (B3:G3) --}}
        <tr>
            <td></td>
            <td colspan="6" style="border-top: 1px solid #000000;"></td>
        </tr>

        {{-- Baris 4: Periode --}}
        <tr>
            <td></td>
            <td style="font-weight: bold; background-color: #FFC000; color: #4A452A; border: 1px solid #000000;">Period<br>Periode</td>
            <td style="border: 1px solid #000000; text-align: center;">{{ $periodLabel }}</td>
        </tr>

        {{-- Baris 5: Jumlah Personalia --}}
        <tr>
            <td></td>
            <td style="font-weight: bold; background-color: #FFC000; color: #4A452A; border: 1px solid #000000;">Personnel<br>Personalia</td>
            <td style="border: 1px solid #000000; text-align: center;">{{ $personnelCount }}</td>
        </tr>

        {{-- Baris 6: kosong (spacer) --}}
        <tr><td></td></tr>

        {{-- Baris 7-8: Header Tabel (2 baris, rowspan utk 4 kolom pertama) --}}
        <tr>
            <td></td>
            <th rowspan="2" style="background-color: #FFCC00; color: #333333; border: 1px solid #000000; text-align: center; vertical-align: middle;">No</th>
            <th rowspan="2" style="background-color: #FFCC00; color: #3B3838; border: 1px solid #000000; text-align: center; vertical-align: middle; white-space: normal;">Personnel ID<br>ID Personalia</th>
            <th rowspan="2" style="background-color: #FFCC00; color: #3B3838; border: 1px solid #000000; text-align: center; vertical-align: middle; white-space: normal;">Name<br>Nama</th>
            <th rowspan="2" style="background-color: #FFCC00; color: #3B3838; border: 1px solid #000000; text-align: center; vertical-align: middle; white-space: normal;">Personnel Group<br>Group Personalia</th>
            <th colspan="{{ count($dateColumns) }}" style="background-color: #FFC000; color: #4A452A; border: 1px solid #000000; text-align: center; vertical-align: middle; white-space: normal;">{{ $monthLabel }}</th>
        </tr>
        <tr>
            <td></td>
            @foreach ($dateColumns as $dc)
                <th style="background-color: #FFF9C4; border: 1px solid #000000; text-align: center; vertical-align: middle;">{{ $dc }}</th>
            @endforeach
        </tr>

        {{-- Baris Data Pegawai --}}
        @foreach ($pegawai as $index => $p)
            <tr>
                <td></td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $p->nomor_ktp }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $p->name }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $p->posisi }}</td>
                @for ($i = 0; $i < count($dateColumns); $i++)
                    <td style="border: 1px solid #000000; text-align: center;"></td>
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>
