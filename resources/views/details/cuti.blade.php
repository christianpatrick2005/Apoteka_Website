<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Izin/Cuti - Apoteka</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Apoteka - Bahagia Medifarma2.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo Apoteka - Bahagia Medifarma2.png') }}">
    @vite('resources/css/app.css')
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 text-slate-800 antialiased selection:bg-[#fde402] selection:text-slate-900 min-h-screen flex flex-col font-['Inter']">

    <!-- Navigation -->
    @include('partials.navbar')

    <!-- Main Content -->
    <div class="flex-grow pt-24 pb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900">Detail Pengajuan Izin/Cuti</h1>
                    <p class="mt-2 text-sm text-slate-500">Informasi pengajuan dan status persetujuan.</p>
                </div>
                <a href="{{ route('pengajuan-izin.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-colors">
                    Kembali
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-semibold text-slate-900">Informasi Pengajuan</h3>
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @if($pengajuanIzinCuti->status_pengajuan == 'disetujui') bg-green-100 text-green-800
                        @elseif($pengajuanIzinCuti->status_pengajuan == 'ditolak') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif capitalize">
                        {{ $pengajuanIzinCuti->status_pengajuan }}
                    </span>
                </div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Nama Pemohon</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengajuanIzinCuti->user->name ?? 'N/A' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Pegawai Pengganti</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengajuanIzinCuti->userPengganti->name ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                            <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $pengajuanIzinCuti->kategori }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Jenis</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengajuanIzinCuti->jenis ?? '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Tanggal Pengajuan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($pengajuanIzinCuti->tanggal_pengajuan)->format('d M Y') }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Rentang Waktu</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($pengajuanIzinCuti->kategori == 'izin')
                                    {{ $pengajuanIzinCuti->jam_mulai ? \Carbon\Carbon::parse($pengajuanIzinCuti->jam_mulai)->format('H:i') : '-' }} s/d 
                                    {{ $pengajuanIzinCuti->jam_selesai ? \Carbon\Carbon::parse($pengajuanIzinCuti->jam_selesai)->format('H:i') : '-' }} WIB
                                @else
                                    {{ $pengajuanIzinCuti->tanggal_mulai ? \Carbon\Carbon::parse($pengajuanIzinCuti->tanggal_mulai)->format('d M Y') : '-' }} s/d 
                                    {{ $pengajuanIzinCuti->tanggal_selesai ? \Carbon\Carbon::parse($pengajuanIzinCuti->tanggal_selesai)->format('d M Y') : '-' }}
                                @endif
                            </dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Durasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengajuanIzinCuti->durasi }}</dd>
                        </div>

                        <!-- Bagian Menampilkan Peta -->
                        @if($pengajuanIzinCuti->geolocation)
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Lokasi GPS Pegawai Saat Mengajukan</dt>
                            <dd class="mt-1">
                                <!-- Koordinat asli (disembunyikan untuk dibaca oleh Javascript nanti) -->
                                <span id="koordinat-gps" class="hidden">{{ $pengajuanIzinCuti->geolocation }}</span>
                                
                                <!-- Kotak tempat peta akan dirender -->
                                <div id="map" class="w-full h-64 rounded-lg border border-gray-300 shadow-sm z-0"></div>
                                
                                <a href="https://www.google.com/maps?q={{ str_replace(' ', '', $pengajuanIzinCuti->geolocation) }}" 
                                target="_blank" 
                                class="inline-block mt-2 text-xs font-medium text-[#284fa0] hover:text-[#1e3b7a] underline">
                                Buka di Google Maps ↗
                                </a>
                            </dd>
                        </div>
                        @else
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Lokasi GPS Pegawai Saat Mengajukan</dt>
                            <dd class="mt-1 text-sm text-gray-500 italic">Lokasi GPS tidak direkam / tidak tersedia.</dd>
                        </div>
                        @endif

                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Keterangan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengajuanIzinCuti->keterangan }}</dd>
                        </div>
                        <!-- <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Alamat Tempat / Lokasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengajuanIzinCuti->alamat_tempat }}</dd>
                        </div> -->
                        
                        <!-- @if($pengajuanIzinCuti->tanda_tangan)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Tanda Tangan Pemohon</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <img src="{{ asset('storage/' . $pengajuanIzinCuti->tanda_tangan) }}" alt="Tanda Tangan" class="max-h-32 object-contain border border-gray-200 rounded p-1">
                            </dd>
                        </div>
                        @endif -->

                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Berkas Pendukung</dt>
                            <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                @if($pengajuanIzinCuti->berkas_pendukung && is_array($pengajuanIzinCuti->berkas_pendukung) && count($pengajuanIzinCuti->berkas_pendukung) > 0)
                                    @foreach($pengajuanIzinCuti->berkas_pendukung as $index => $berkas)
                                    <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                        <div class="w-0 flex-1 flex items-center">
                                            <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="ml-2 flex-1 w-0 truncate">Berkas {{ $index + 1 }}</span>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <a href="{{ asset('storage/' . $berkas) }}" target="_blank" class="font-medium text-[#284fa0] hover:text-[#1e3b7a]">Lihat/Unduh</a>
                                        </div>
                                    </li>
                                    @endforeach
                                @else
                                    <li class="pl-3 pr-4 py-3 flex items-center justify-center text-sm text-gray-500">
                                        Tidak ada berkas pendukung
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </dl>
                </div>
            </div>

            @if($pengajuanIzinCuti->status_pengajuan != 'pending')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-lg leading-6 font-semibold text-slate-900">Respon Manajer</h3>
                </div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Tanggal Persetujuan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengajuanIzinCuti->tanggal_persetujuan ? \Carbon\Carbon::parse($pengajuanIzinCuti->tanggal_persetujuan)->format('d M Y') : '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Komentar Manajer</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengajuanIzinCuti->komentar_manajer ?: 'Tidak ada komentar.' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- Footer -->
    @include('partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Cek apakah elemen koordinat ada di halaman
        const kordinatEl = document.getElementById('koordinat-gps');
        
        if (kordinatEl) {
            // Memecah teks koordinat (contoh: "-7.250445, 112.768845") menjadi array
            const koordinat = kordinatEl.textContent.split(',');
            const lat = parseFloat(koordinat[0].trim());
            const lng = parseFloat(koordinat[1].trim());

            // 1. Inisialisasi peta dan arahkan ke koordinat tersebut dengan zoom level 15
            const map = L.map('map').setView([lat, lng], 15);

            // 2. Tambahkan layer gambar peta dari OpenStreetMap (Gratis)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // 3. Tambahkan pin/marker merah di titik koordinat
            L.marker([lat, lng]).addTo(map)
                .bindPopup('<b>Lokasi Pengajuan</b><br>Terekam dari GPS Pegawai.')
                .openPopup();
        }
    });
</script>

</body>
</html>
