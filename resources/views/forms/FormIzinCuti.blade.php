<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Izin/Cuti - Apoteka</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<style>
    body {
        font-family: 'Inter', sans-serif;
    }
</style>
<body>
    @include("partials.navbar")    

    <div class="mt-10 mb-10">
        <div class="flex items-end justify-center">
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-[#284fa0] px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-semibold text-white">
                        {{ isset($pengajuanIzinCuti) ? 'Edit Pengajuan Izin/Cuti' : 'Form Pengajuan Izin/Cuti' }}
                    </h3>
                </div>
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-4 mt-4 text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-4 mt-4 text-sm">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-4 mt-4 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[70vh] overflow-y-auto">
                    @php 
                        $users = \App\Models\User::all();
                    @endphp
                    <form action="{{ isset($pengajuanIzinCuti) ? route('pengajuan-izin.update', $pengajuanIzinCuti->id) : route('pengajuan-izin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @if(isset($pengajuanIzinCuti))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pilih Pegawai (Pemohon) <span class="text-red-500">*</span></label>
                                <select name="user_id" required class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                                    <option value="">-- Pilih Pegawai --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ (old('user_id', $pengajuanIzinCuti->user_id ?? (auth()->check() ? auth()->id() : '')) == $user->id) ? 'selected' : '' }}>{{ $user->name ?? 'User '.$user->id }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">User Pengganti</label>
                                <select name="user_pengganti_id" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                                    <option value="">-- Pilih User Pengganti --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ (old('user_pengganti_id', $pengajuanIzinCuti->user_pengganti_id ?? '') == $user->id) ? 'selected' : '' }}>{{ $user->name ?? 'User '.$user->id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- x-data untuk mendeteksi pilihan kategori secara real-time -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{ pilihanKategori: '{{ old('kategori', $pengajuanIzinCuti->kategori ?? 'izin') }}' }">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                                <!-- x-model agar Alpine.js tahu saat pilihan ini diubah -->
                                <select name="kategori" required x-model="pilihanKategori" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                                    <option value="izin">Izin</option>
                                    <option value="cuti">Cuti</option>
                                </select>
                            </div>

                            <!-- x-show agar div ini HANYA MUNCUL jika Kategori = 'cuti' -->
                            <div x-show="pilihanKategori === 'cuti'" x-transition>
                                <label class="block text-sm font-medium text-gray-700">Jenis Cuti</label>
                                <select name="jenis_cuti" id="jenis_cuti" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="cuti_tahunan" {{ (old('jenis_cuti', $pengajuanIzinCuti->jenis_cuti ?? '') == 'cuti_tahunan') ? 'selected' : '' }}>Tahunan</option>
                                    <option value="cuti_kehamilan" {{ (old('jenis_cuti', $pengajuanIzinCuti->jenis_cuti ?? '') == 'cuti_kehamilan') ? 'selected' : '' }}>Melahirkan</option>
                                </select>
                            </div>

                            <div  class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="pilihanKategori === 'cuti'" x-transition>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Mulai <span class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', isset($pengajuanIzinCuti) && $pengajuanIzinCuti->tanggal_mulai ? \Carbon\Carbon::parse($pengajuanIzinCuti->tanggal_mulai)->format('Y-m-d') : '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Selesai <span class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', isset($pengajuanIzinCuti) && $pengajuanIzinCuti->tanggal_selesai ? \Carbon\Carbon::parse($pengajuanIzinCuti->tanggal_selesai)->format('Y-m-d') : '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                                </div>
                            </div>

                            <div  class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="pilihanKategori === 'izin'" x-transition>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jam Mulai <span class="text-red-500">*</span></label>
                                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', isset($pengajuanIzinCuti) && $pengajuanIzinCuti->jam_mulai ? \Carbon\Carbon::parse($pengajuanIzinCuti->jam_mulai)->format('H:i') : '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jam Selesai <span class="text-red-500">*</span></label>
                                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', isset($pengajuanIzinCuti) && $pengajuanIzinCuti->jam_selesai ? \Carbon\Carbon::parse($pengajuanIzinCuti->jam_selesai)->format('H:i') : '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                                </div>
                            </div>
                            
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Pengajuan <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_pengajuan" required value="{{ old('tanggal_pengajuan', isset($pengajuanIzinCuti) && $pengajuanIzinCuti->tanggal_pengajuan ? \Carbon\Carbon::parse($pengajuanIzinCuti->tanggal_pengajuan)->format('Y-m-d') : date('Y-m-d')) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Durasi <span class="text-red-500">*</span></label>
                            <input type="text" name="durasi" required value="{{ old('durasi', $pengajuanIzinCuti->durasi ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm" placeholder="Contoh: 3 Hari / 4 Jam">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan / Alasan <span class="text-red-500">*</span></label>
                            <textarea name="keterangan" required rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">{{ old('keterangan', $pengajuanIzinCuti->keterangan ?? '') }}</textarea>
                        </div>

                        <!-- <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat Tempat / Selama Cuti <span class="text-red-500">*</span></label>
                            <textarea name="alamat_tempat" required rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">{{ old('alamat_tempat', $pengajuanIzinCuti->alamat_tempat ?? '') }}</textarea>
                        </div> -->

                        <!-- Input Geolocation (Disembunyikan dari layar) -->
                        <input type="hidden" id="geolocation_input" name="geolocation">

                        <!-- Indikator Status Lokasi di Layar -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Lokasi</label>
                            <div class="flex items-center gap-2">
                                <!-- Icon Loading (Bisa pakai SVG atau teks biasa) -->
                                <span id="lokasi-icon" class="text-blue-500 animate-pulse">⏳</span>
                                <p id="location-status" class="text-sm text-blue-600 font-medium">Mendeteksi lokasi Anda secara otomatis...</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Berkas Pendukung (Bisa lebih dari 1 file, format: pdf, jpeg, mp4, dsb)</label>
                            <input type="file" name="berkas_pendukung[]" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                            @if(isset($pengajuanIzinCuti) && !empty($pengajuanIzinCuti->berkas_pendukung))
                                <p class="text-xs text-gray-500 mt-1">Terdapat {{ count($pengajuanIzinCuti->berkas_pendukung) }} berkas terlampir sebelumnya.</p>
                            @endif
                        </div>
                        
                        <div class="pt-4 border-t border-gray-200 flex flex-row-reverse">
                            <button type="submit" id="submit-btn" disabled class="opacity-50 cursor-not-allowed w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#284fa0] text-base font-medium text-white hover:bg-[#1e3b7a] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm ">
                                Simpan Pengajuan
                            </button>
                            <a href="{{ route('pengajuan-izin.index') }}" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include("partials.footer")    
</body>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputLocation = document.getElementById('geolocation_input');
        const statusText = document.getElementById('location-status');
        const statusIcon = document.getElementById('lokasi-icon');
        
        // BUG FIX: Anda lupa mendefinisikan btnSubmit sebelumnya!
        const btnSubmit = document.getElementById('submit-btn'); 

        // Fungsi untuk melacak lokasi
        function autoGetLocation() {
            if (!navigator.geolocation) {
                statusText.textContent = "Browser Anda tidak mendukung pelacakan lokasi.";
                statusText.classList.replace('text-blue-600', 'text-red-500');
                statusIcon.textContent = "❌";
                return;
            }

            navigator.geolocation.getCurrentPosition(
                // kalau berhasil
                function (position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    
                    // memasukkan data ke input hidden
                    inputLocation.value = latitude + ", " + longitude;
                    
                    // update tampilan di layar
                    statusText.textContent = "Lokasi berhasil direkam.";
                    statusText.classList.replace('text-blue-600', 'text-emerald-600');
                    statusIcon.textContent = "✅";
                    statusIcon.classList.remove('animate-pulse');

                    // Buka kunci tombol (Sekarang tidak akan error karena btnSubmit sudah didefinisikan)
                    btnSubmit.disabled = false;
                    btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                },
                // kalau gagal
                function (error) {
                    statusText.classList.replace('text-blue-600', 'text-red-500');
                    statusIcon.textContent = "❌";
                    statusIcon.classList.remove('animate-pulse');
                    btnSubmit.disabled = true;
                    
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            statusText.textContent = "Akses lokasi ditolak oleh Anda. Mohon izinkan akses lokasi di browser.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            statusText.textContent = "Informasi GPS tidak tersedia saat ini.";
                            break;
                        case error.TIMEOUT:
                            statusText.textContent = "Waktu pelacakan lokasi habis (Timeout).";
                            break;
                        default:
                            statusText.textContent = "Terjadi kesalahan sistem saat melacak lokasi.";
                            break;
                    }
                },
                {
                    // OPTIMASI KECEPATAN:
                    // Jika Anda ingin INSTAN, ubah enableHighAccuracy menjadi false. 
                    // (Lokasi akan diambil dari jaringan WiFi/Provider seluler, bukan satelit murni. Sangat cepat tapi akurasi bisa meleset 50-100 meter).
                    enableHighAccuracy: true, 
                    
                    timeout: 15000, // Beri waktu toleransi lebih lama (15 detik) untuk HP kentang
                    
                    // maximumAge: 60000 berarti browser diizinkan menggunakan lokasi yang tersimpan (cache) dalam 1 menit terakhir
                    maximumAge: 60000 
                }
            );
        }

        // function akan langsung dijalankan saat halaman dibuka
        autoGetLocation();
    });
</script>

</html>
