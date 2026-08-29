<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Riwayat Izin & Cuti</title>
    <style>
        /* 1. Atur Margin Halaman dengan Presisi */
        @page {
            margin: 30px 40px 50px 40px; /* Atas, Kanan, Bawah, Kiri */
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; /* Font fallback yang aman untuk dompdf */
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }

        /* 2. Posisi Footer Fixed */
        footer {
            position: fixed;
            bottom: -30px; /* Posisi ditarik ke area margin bawah @page */
            left: 0px;
            right: 0px;
            height: 20px;
            border-top: 1px solid #94a3b8;
            padding-top: 8px;
            font-size: 9px;
            color: #64748b;
        }

        .footer-left { float: left; }
        .footer-right { float: right; }
        
        /* Clearfix untuk dompdf */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        /* 3. Bungkus Konten Utama */
        main {
            padding-top: 10px;
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 10px;
        }

        .kop-surat h1 {
            margin: 0 0 5px 0;
            font-size: 20px;
            color: #1e293b;
            text-transform: uppercase;
        }

        .kop-surat p {
            margin: 0;
            font-size: 11px;
            color: #475569;
        }

        .judul {
            text-align: center;
            margin-bottom: 15px;
        }

        .judul h2 {
            margin: 0 0 5px 0;
            font-size: 16px;
        }

        .judul p {
            margin: 0;
            font-size: 11px;
            color: #64748b;
        }

        /* 4. Tabel yang Dikontrol Ketat */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Memaksa dompdf mematuhi lebar th */
        }

        th {
            background-color: #284fa0;
            color: white;
            border: 1px solid #cbd5e1;
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }

        td {
            border: 1px solid #cbd5e1;
            padding: 8px 6px;
            vertical-align: top;
            font-size: 10px;
            word-wrap: break-word; /* Mencegah teks panjang merusak tabel */
        }

        /* Menentukan Lebar Kolom (Total 100%) */
        .col-tgl { width: 12%; }
        .col-nama { width: 22%; }
        .col-kategori { width: 16%; }
        .col-durasi { width: 24%; }
        .col-status-pengganti { width: 13%; text-align: center; }
        .col-status-akhir { width: 13%; text-align: center; }

        .text-center { text-align: center; }
        .text-small { font-size: 9px; color: #64748b; margin-top: 3px; display: block; }
        .font-bold { font-weight: bold; }

        /* Badge Status Sederhana untuk PDF */
        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }
        .bg-blue { background-color: #dbeafe; color: #1e40af; }
        .bg-yellow { background-color: #fef3c7; color: #92400e; }
        .bg-green { background-color: #dcfce3; color: #166534; }
        .bg-red { background-color: #fee2e2; color: #991b1b; }
        .bg-gray { background-color: #f1f5f9; color: #475569; }

        /* Mencegah row terpotong di tengah */
        tr {
            page-break-inside: avoid;
        }
        thead {
            display: table-header-group;
        }

        /* Mengaktifkan penomoran halaman otomatis domPDF */
        .page-number:before {
            content: "Halaman " counter(page);
        }
    </style>
</head>

<body>

    {{-- FOOTER HARUS DI ATAS MAIN UNTUK DOMPDF --}}
    <footer class="clearfix">
        <div class="footer-left">
            Dicetak pada: {{ now()->locale('id')->translatedFormat('l, d F Y H:i') }} WIB
        </div>
        {{-- Script nomor halaman di Controller sudah mengambil alih posisi kanan, tapi kita sediakan wadahnya --}}
        <!-- <div class="footer-right">
            Sistem Informasi Apoteka
        </div> -->
        <div class="footer-right">
            <span class="page-number"></span>
        </div>
    </footer>

    {{-- KONTEN UTAMA --}}
    <main>
        {{-- KOP SURAT --}}
        @include('partials.KopSurat')
        <!-- <div class="kop-surat">
            <h1>Apoteka Bahagia Medifarma</h1>
            <p>Jalan Kesehatan No. 123, Surabaya | Telp: (031) 1234567 | Email: info@apoteka.com</p>
        </div> -->

        {{-- JUDUL --}}
        <div class="judul">
            <h2>Laporan Riwayat Cuti & Izin</h2>
            <p>Rekapitulasi riwayat pengajuan izin dan cuti pegawai.</p>
        </div>

        {{-- TABEL --}}
        <table>
            <thead>
                <tr>
                    <th class="col-tgl">Tgl Pengajuan</th>
                    <th class="col-nama">Nama Pegawai</th>
                    <th class="col-kategori">Kategori</th>
                    <th class="col-durasi">Durasi & Tgl</th>
                    <th class="col-status-pengganti">Status Pengganti</th>
                    <th class="col-status-akhir">Status Akhir</th>
                    <th class="col-status-akhir">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $item)
                <tr>
                    {{-- TANGGAL PENGAJUAN --}}
                    <td>
                        {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : '-' }}
                    </td>

                    {{-- NAMA --}}
                    <td class="font-bold">
                        {{ $item->user->name ?? 'Tidak ada data' }}
                    </td>

                    {{-- KATEGORI --}}
                    <td>
                        <span class="badge {{ $item->kategori == 'cuti' ? 'bg-blue' : 'bg-yellow' }}">
                            {{ ucfirst($item->kategori) }}
                        </span>
                        @if($item->kategori == 'cuti')
                            <span class="text-small">{{ $item->jenis_cuti }}</span>
                        @endif
                    </td>

                    {{-- DURASI --}}
                    <td>
                        <span class="font-bold">{{ $item->durasi }}</span>
                        <span class="text-small">
                            @if($item->kategori == 'cuti')
                                {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y') : '-' }} 
                                s/d 
                                {{ $item->tanggal_selesai ? $item->tanggal_selesai->format('d/m/Y') : '-' }}
                            @else
                                {{ $item->jam_mulai ? $item->jam_mulai->format('H:i') : '-' }} 
                                s/d 
                                {{ $item->jam_selesai ? $item->jam_selesai->format('H:i') : '-' }}
                            @endif
                        </span>
                    </td>

                    {{-- STATUS PENGGANTI --}}
                    <td class="text-center">
                        @if($item->kategori == 'cuti')
                            @if($item->status_pengganti == 'pending')
                                <span class="badge bg-yellow">Menunggu</span>
                            @elseif($item->status_pengganti == 'disetujui')
                                <span class="badge bg-green">Disetujui</span>
                            @elseif($item->status_pengganti == 'ditolak')
                                <span class="badge bg-red">Ditolak</span>
                            @else
                                -
                            @endif
                        @else
                            <span class="text-small text-center">Tidak Wajib</span>
                        @endif
                    </td>

                    {{-- STATUS AKHIR --}}
                    <td class="text-center">
                        @if($item->status_pengajuan == 'pending')
                            <span class="badge bg-yellow">Menunggu</span>
                        @elseif($item->status_pengajuan == 'disetujui')
                            <span class="badge bg-green">Disetujui</span>
                        @elseif($item->status_pengajuan == 'ditolak')
                            <span class="badge bg-red">Ditolak</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        {{ $item->keterangan ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">
                        Tidak ada data riwayat izin dan cuti.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </main>

</body>
</html>