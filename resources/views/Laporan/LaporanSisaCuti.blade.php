<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sisa Cuti - Apoteka</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Apoteka - Bahagia Medifarma2.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo Apoteka - Bahagia Medifarma2.png') }}">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body class="bg-gray-50 text-slate-800 antialiased font-['Inter'] min-h-screen flex flex-col">

    @include('partials.navbar')

    <div class="flex-grow pt-24 pb-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8 flex justify-between items-center">
                <div class="print:text-center print:w-full">
                    <h1 class="text-3xl font-extrabold text-slate-900">Laporan Sisa Cuti Pegawai</h1>
                    <p class="mt-2 text-sm text-slate-500">Rekapitulasi sisa kuota cuti tahunan dan cuti kehamilan pegawai aktif, dll.</p>
                </div>
                <a href="{{ route('laporan.sisa-cuti.pdf', request()->query()) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-colors cursor-pointer no-print">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Laporan
                </a>
            </div>

            @include('partials.LaporanNav')

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-[#284fa0] text-white">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">No</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Nama Pegawai</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Posisi</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Sisa Cuti Tahunan</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider">Sisa Cuti Kehamilan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($dataPegawai as $index => $pegawai)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $pegawai->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pegawai->posisi }}</td>
                                
                                <!-- Logika Warna: Merah jika sisa cuti menipis (<= 2) -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center {{ $pegawai->jatah_cuti_tahunan <= 2 ? 'text-red-600' : 'text-emerald-600' }}">
                                    {{ $pegawai->jatah_cuti_tahunan }} Hari
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center text-slate-700">
                                    {{ $pegawai->jatah_cuti_kehamilan }} Hari
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Tidak ada data pegawai yang dapat ditampilkan.</td>
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