<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Dokumen - Apoteka</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Apoteka - Bahagia Medifarma2.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo Apoteka - Bahagia Medifarma2.png') }}">
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
    body {
        font-family: 'Inter', sans-serif;
    }
</style>
<body>
    @include("partials.navbar")    

    <div  class="mt-10 mb-10" >
        <div class="flex items-end justify-center">
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                <div class="bg-[#284fa0] px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-semibold text-white">Form Dokumen Pegawai</h3>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[70vh] overflow-y-auto">
                    @if(session('error'))
                        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                            <div class="flex items-center">
                                <!-- Icon Checklist -->
                                <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <form id="form-dokumen" action="{{ isset($dokumenPegawai) ? route('dokumen.update', $dokumenPegawai->id) : route('dokumen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @if(isset($dokumenPegawai))
                            @method('PUT')
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pilih Pegawai (User ID)</label>
                            <select name="user_id" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                                <option value="" disabled selected>-- Pilih Pegawai --</option>
                                <!-- looping data user -->
                                @foreach($users as $user) 
                                    <option value="{{ $user->id }}" {{ (old('user_id', $dokumenPegawai->user_id ?? '') == $user->id) ? 'selected' : '' }}> <!--agar data tidak hilang saat direfresh -->
                                        {{ $user->name }} - {{ $user->posisi }}  <!-- teks yang ditampilkan -->
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">File Ijasah</label>
                            <input type="file" name="ijasah" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                            @if(isset($dokumenPegawai) && $dokumenPegawai->ijasah)
                                <p class="text-xs text-gray-500 mt-1">File saat ini: <a href="{{ asset('storage/' . $dokumenPegawai->ijasah) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Ijasah</a></p>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File Transkrip</label>
                            <input type="file" name="transkrip" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                            @if(isset($dokumenPegawai) && $dokumenPegawai->transkrip)
                                <p class="text-xs text-gray-500 mt-1">File saat ini: <a href="{{ asset('storage/' . $dokumenPegawai->transkrip) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Transkrip</a></p>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File KTP</label>
                            <input type="file" name="ktp" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                            @if(isset($dokumenPegawai) && $dokumenPegawai->ktp)
                                <p class="text-xs text-gray-500 mt-1">File saat ini: <a href="{{ asset('storage/' . $dokumenPegawai->ktp) }}" target="_blank" class="text-blue-600 hover:underline">Lihat KTP</a></p>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File Surat Tanda Registrasi (STR)</label>
                            <input type="file" name="str" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                            @if(isset($dokumenPegawai) && $dokumenPegawai->str)
                                <p class="text-xs text-gray-500 mt-1">File saat ini: <a href="{{ asset('storage/' . $dokumenPegawai->str) }}" target="_blank" class="text-blue-600 hover:underline">Lihat STR</a></p>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sertifikat Kompetensi</label>
                            <input type="file" name="sertifikat_kompetensi" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                            @if(isset($dokumenPegawai) && $dokumenPegawai->sertifikat_kompetensi)
                                <p class="text-xs text-gray-500 mt-1">File saat ini: <a href="{{ asset('storage/' . $dokumenPegawai->sertifikat_kompetensi) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Sertifikat</a></p>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File SIPA</label>
                            <input type="file" name="sipa" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                            @if(isset($dokumenPegawai) && $dokumenPegawai->sipa)
                                <p class="text-xs text-gray-500 mt-1">File saat ini: <a href="{{ asset('storage/' . $dokumenPegawai->sipa) }}" target="_blank" class="text-blue-600 hover:underline">Lihat SIPA</a></p>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Kadaluarsa SIPA</label>
                            <!-- Tambahkan atribut value agar tanggal lama muncul -->
                            <input type="date" name="tanggal_kadaluarsa_sipa" value="{{ old('tanggal_kadaluarsa_sipa', isset($dokumenPegawai) && $dokumenPegawai->tanggal_kadaluarsa_sipa ? \Carbon\Carbon::parse($dokumenPegawai->tanggal_kadaluarsa_sipa)->format('Y-m-d') : '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                    <button form="form-dokumen" type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#284fa0] text-base font-medium text-white hover:bg-[#1e3b7a] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <a href="{{ route('dokumen.index') }}" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include("partials.footer")    
</body>
</html>