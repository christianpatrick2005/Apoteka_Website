<table>
    <tbody>
        <tr>
            <th colspan="5" style="font-weight: bold; font-size: 16px; text-align: left;">SHIFTING LIST<br>Daftar Shift</th>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td></td>
            <td colspan="6" style="border-top: 1px solid #000000;"></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-weight: bold; background-color: #FFC000; border: 1px solid #000000;">Period<br>Periode</td>
            <td style="border: 1px solid #000000; text-align: center;">{{ $periodLabel }}</td>
        </tr>
        <tr>
            <td></td>
            <td style="font-weight: bold; background-color: #FFC000; border: 1px solid #000000;">Personnel<br>Personalia</td>
            <td style="border: 1px solid #000000; text-align: center;">{{ $personnelCount }}</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td></td>
            <th style="background-color: #FFCC00; border: 1px solid #000000; text-align: center;">No</th>
            <th style="background-color: #FFCC00; border: 1px solid #000000; text-align: center;">Shift Name<br>Nama Shift</th>
            <th style="background-color: #FFCC00; border: 1px solid #000000; text-align: center;">Shift Description<br>Deskripsi Shift</th>
            <th style="background-color: #FFCC00; border: 1px solid #000000; text-align: center;">Clock In<br>Jam Masuk</th>
            <th style="background-color: #FFCC00; border: 1px solid #000000; text-align: center;">Clock Out<br>Jam Keluar</th>
            <th style="background-color: #FFCC00; border: 1px solid #000000; text-align: center;">Late Tolerance<br>Toleransi Keterlambatan</th>
        </tr>

        <tr>
            <td></td>
            <td style="border: 1px solid #000000; text-align: center;">1</td>
            <td style="border: 1px solid #000000; text-align: center;">Day Off</td>
            <td style="border: 1px solid #000000;"></td>
            <td style="border: 1px solid #000000;"></td>
            <td style="border: 1px solid #000000;"></td>
            <td style="border: 1px solid #000000; text-align: center;">0</td>
        </tr>
        @foreach ($shifts as $index => $shift)
            <tr>
                <td></td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 2 }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $shift->nama_shift }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $shift->deskripsi ?? '' }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $shift->jam_masuk }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $shift->jam_keluar }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $shift->toleransi ?? 0 }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
