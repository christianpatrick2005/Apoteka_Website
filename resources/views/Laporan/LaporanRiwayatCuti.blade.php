<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Riwayat Izin-Cuti - Apoteka</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Apoteka - Bahagia Medifarma2.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo Apoteka - Bahagia Medifarma2.png') }}">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @media print {
            @page {
                size: landscape;
                margin: 1cm;
            }
            nav, footer, button, .no-print {
                display: none !important;
            }
            body {
                background-color: white !important;
                padding-top: 0 !important;
            }
            .flex-grow {
                padding-top: 0 !important;
            }
            .shadow-sm {
                box-shadow: none !important;
            }
            .border {
                border: 1px solid #000 !important;
            }
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
    </style>
</head>
<body class="bg-gray-50 text-slate-800 antialiased font-['Inter'] min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="flex-grow pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Kop Surat (Hanya Tampil Saat Print) -->
            @include('partials.KopSurat')

            <div class="mb-8 flex justify-between items-center">
                <div class="print:text-center print:w-full">
                    <h1 class="text-3xl font-extrabold text-slate-900">Laporan Riwayat Cuti & Izin</h1>
                    <p class="mt-2 text-sm text-slate-500">Rekapitulasi riwayat pengajuan izin dan cuti pegawai.</p>
                </div>
                <button onclick="window.print()" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-colors cursor-pointer no-print">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Laporan
                </button>
            </div>

            @include('partials.LaporanNav')

            <div class="bg-white p-4 rounded-lg shadow-sm mb-6 border border-gray-100">
                <form action="{{ route('laporan.riwayat-cuti') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    
                    <!-- Filter Nama -->
                    <div class="w-full md:w-1/4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama Pegawai</label>
                        <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari nama..." 
                            class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:ring-[#284fa0] focus:border-[#284fa0]">
                    </div>

                    <!-- Filter Kategori -->
                    <div class="w-full md:w-1/4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="kategori" class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:ring-[#284fa0] focus:border-[#284fa0] bg-white">
                            <option value="">Semua Kategori</option>
                            <option value="izin" {{ request('kategori') == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="cuti" {{ request('kategori') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                        </select>
                    </div>

                    <!-- Filter Tanggal Mulai -->
                    <div class="w-full md:w-1/5">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" 
                            class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:ring-[#284fa0] focus:border-[#284fa0]">
                    </div>

                    <!-- Filter Tanggal Selesai -->
                    <div class="w-full md:w-1/5">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" 
                            class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:ring-[#284fa0] focus:border-[#284fa0]">
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="w-full md:w-auto flex gap-2">
                        <button type="submit" class="bg-[#284fa0] text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-[#1e3b7a] transition-colors shadow-sm">
                            Filter
                        </button>
                        <a href="{{ route('laporan.riwayat-cuti') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors border border-gray-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-[#284fa0] text-white">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Tgl Pengajuan</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Nama Pegawai</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Kategori</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Durasi & Tgl</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Status Pengganti</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Status Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($riwayat as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    {{ $item->user->name ?? 'Tidak ada data' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($item->kategori == 'cuti')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($item->kategori) }}
                                    </span>
                                    @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ ucfirst($item->kategori) }}
                                    </span>
                                    @endif
                                    @if($item->kategori == 'cuti')
                                    <span class="text-xs text-gray-500 block mt-1">{{ $item->jenis_cuti }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $item->durasi }}<br>
                                    @if($item->kategori == 'cuti')
                                    <span class="text-xs text-gray-500">
                                        {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y') : '-' }} s/d 
                                        {{ $item->tanggal_selesai ? $item->tanggal_selesai->format('d/m/Y') : '-' }}
                                    </span>
                                    @else
                                    <span class="text-xs text-gray-500">
                                        {{ $item->jam_mulai ? $item->jam_mulai->format('H:i') : '-' }} s/d 
                                        {{ $item->jam_selesai ? $item->jam_selesai->format('H:i') : '-' }}
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    @if($item->kategori == 'cuti')
                                        @if($item->status_pengganti == 'pending')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                        @elseif($item->status_pengganti == 'disetujui')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                        @elseif($item->status_pengganti == 'ditolak')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-xs">Tidak Wajib</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    @if($item->status_pengajuan == 'pending')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Manajer</span>
                                    @elseif($item->status_pengajuan == 'disetujui')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                    @elseif($item->status_pengajuan == 'ditolak')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Tidak ada data riwayat izin dan cuti.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @include('partials.footer')
</body>
</html>
