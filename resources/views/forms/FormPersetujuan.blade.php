<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Persetujuan Izin Cuti - Apoteka</title>
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
                    <h3 class="text-lg leading-6 font-semibold text-white">Form Persetujuan Izin Cuti</h3>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[70vh] overflow-y-auto">
                    <form id="form-dokumen" action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pilih Pegawai (User ID)</label>
                            <select name="user_id" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                                <option value="" disabled selected>-- Pilih Pegawai --</option>

                                <!-- looping data user -->
                                @foreach($users as $user) 
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }} <!--agar data tidak hilang saat direfresh --> >
                                        {{ $user->name }} - {{ $user->posisi }} <!-- teks yang ditampilkan -->
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File Ijasah</label>
                            <input type="file" name="ijasah" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File Transkrip</label>
                            <input type="file" name="transkrip" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File KTP</label>
                            <input type="file" name="ktp" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File Surat Tanda Registrasi</label>
                            <input type="file" name="str" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sertifikat Kompetensi</label>
                            <input type="file" name="sertifikat_kompetensi" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File SIPA</label>
                            <input type="file" name="sipa" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Kadaluarsa SIPA</label>
                            <input type="date" name="tanggal_kadaluarsa_sipa" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
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