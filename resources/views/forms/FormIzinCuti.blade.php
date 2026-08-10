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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                                <select name="kategori" required class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                                    <option value="izin" {{ (old('kategori', $pengajuanIzinCuti->kategori ?? '') == 'izin') ? 'selected' : '' }}>Izin</option>
                                    <option value="cuti" {{ (old('kategori', $pengajuanIzinCuti->kategori ?? '') == 'cuti') ? 'selected' : '' }}>Cuti</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis</label>

                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Pengajuan <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_pengajuan" required value="{{ old('tanggal_pengajuan', isset($pengajuanIzinCuti) && $pengajuanIzinCuti->tanggal_pengajuan ? \Carbon\Carbon::parse($pengajuanIzinCuti->tanggal_pengajuan)->format('Y-m-d') : date('Y-m-d')) }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', isset($pengajuanIzinCuti) && $pengajuanIzinCuti->tanggal_mulai ? \Carbon\Carbon::parse($pengajuanIzinCuti->tanggal_mulai)->format('Y-m-d') : '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', isset($pengajuanIzinCuti) && $pengajuanIzinCuti->tanggal_selesai ? \Carbon\Carbon::parse($pengajuanIzinCuti->tanggal_selesai)->format('Y-m-d') : '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Durasi <span class="text-red-500">*</span></label>
                            <input type="text" name="durasi" required value="{{ old('durasi', $pengajuanIzinCuti->durasi ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm" placeholder="Contoh: 3 Hari / 4 Jam">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan / Alasan <span class="text-red-500">*</span></label>
                            <textarea name="keterangan" required rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">{{ old('keterangan', $pengajuanIzinCuti->keterangan ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat Tempat / Selama Cuti <span class="text-red-500">*</span></label>
                            <textarea name="alamat_tempat" required rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">{{ old('alamat_tempat', $pengajuanIzinCuti->alamat_tempat ?? '') }}</textarea>
                        </div>

                        <!-- <div>
                            <label class="block text-sm font-medium text-gray-700">Tanda Tangan (Gambar: jpeg/png/jpg)</label>
                            <input type="file" name="tanda_tangan" accept=".jpeg,.png,.jpg,image/jpeg,image/png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                            @if(isset($pengajuanIzinCuti) && $pengajuanIzinCuti->tanda_tangan)
                                <p class="text-xs text-gray-500 mt-1">File saat ini: <a href="{{ asset('storage/' . $pengajuanIzinCuti->tanda_tangan) }}" target="_blank" class="text-blue-500 underline">Lihat Tanda Tangan</a></p>
                            @endif
                        </div> -->

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Berkas Pendukung (Bisa lebih dari 1 file, format: pdf, jpeg, mp4, dsb)</label>
                            <input type="file" name="berkas_pendukung[]" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                            @if(isset($pengajuanIzinCuti) && !empty($pengajuanIzinCuti->berkas_pendukung))
                                <p class="text-xs text-gray-500 mt-1">Terdapat {{ count($pengajuanIzinCuti->berkas_pendukung) }} berkas terlampir sebelumnya.</p>
                            @endif
                        </div>
                        
                        <div class="pt-4 border-t border-gray-200 flex flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#284fa0] text-base font-medium text-white hover:bg-[#1e3b7a] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
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
</html>
