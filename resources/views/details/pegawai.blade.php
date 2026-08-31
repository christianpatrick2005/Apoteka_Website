<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pegawai - Apoteka</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Apoteka - Bahagia Medifarma2.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo Apoteka - Bahagia Medifarma2.png') }}">
    @vite('resources/css/app.css')
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                    <h1 class="text-3xl font-extrabold text-slate-900">Detail Pegawai</h1>
                    <p class="mt-2 text-sm text-slate-500">Informasi lengkap data pegawai.</p>
                </div>
                @if(auth()->user()->role === 'manajer')
                <a href="{{ route('pegawai.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-colors">
                    Kembali
                </a>
                @else
                <a href="{{ route('MainPage') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-colors">
                    Kembali
                </a>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-lg leading-6 font-semibold text-slate-900">Informasi Dasar</h3>
                </div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Foto Profil</dt>
                            @if($user->Foto_Profil)
                            <div class="mt-1">
                                <img src="{{ asset('storage/' . $user->Foto_Profil) }}" alt="{{ $user->name }}" class="h-32 w-auto rounded-lg shadow-sm border border-gray-200 object-cover">
                            </div>
                            @else
                            <div class="mt-1">
                                <dd class="text-sm text-gray-900">User Tidak Memiliki Foto Profil</dd>
                            </div>
                            @endif
                            @if(auth()->user()->role === 'pegawai')
                            <form action="{{ route('pegawai.update-profil-saya') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="file" name="Foto_Profil" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                                <button type="submit" class="mt-2 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-[#284fa0] hover:bg-[#284fa0] shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#284fa0]">Upload</button>
                            </form>
                            @endif
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Posisi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->posisi }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Role</dt>
                            <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $user->role }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Nomor KTP</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->nomor_ktp }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Nomor HP</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->nomor_hp }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Tempat, Tanggal Lahir</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->tempat_lahir }}, {{ \Carbon\Carbon::parse($user->tanggal_lahir)->format('d M Y') }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Jenis Kelamin</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->jenis_kelamin }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Agama</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->agama }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Status Pernikahan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->status_pernikahan }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Alamat Surabaya</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->alamat_surabaya }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Alamat Asal</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->alamat_asal }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Gaji</dt>
                            <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($user->gaji, 0, ',', '.') }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Jatah Cuti Tahunan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->jatah_cuti_tahunan }} hari</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Jatah Cuti Kehamilan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->jatah_cuti_kehamilan }} hari</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-semibold text-slate-900">Jadwal Shift</h3>
                
                <!-- Tombol Hapus Semua Jadwal -->
                @if(auth()->user()->role === 'manajer' && $user->jadwalPegawai && $user->jadwalPegawai->count() > 0)
                    <form action="{{ route('jadwal.destroyAll', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA jadwal untuk pegawai ini? Tindakan ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm transition-colors">
                            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Semua Jadwal
                        </button>
                    </form>
                @endif
            </div>

            @if(auth()->user()->role === 'manajer')
            <div class="px-6 py-4 bg-white border-b border-gray-100">
                    <form action="{{ route('jadwal.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end">
                        @csrf
                        <!-- Input tersembunyi untuk menyimpan ID pegawai ini -->
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <div class="w-full sm:w-1/3">
                            <label class="block text-xs font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="tanggal" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:ring-[#284fa0] focus:border-[#284fa0]">
                        </div>

                        <div class="w-full sm:w-1/3">
                            <label class="block text-xs font-medium text-gray-700">Pilih Shift</label>
                            <select name="shift_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:ring-[#284fa0] focus:border-[#284fa0] bg-white">
                                <option value="" disabled selected>-- Pilih Shift --</option>
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}">{{ $shift->nama_shift }} ({{ \Carbon\Carbon::parse($shift->jam_masuk)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->jam_keluar)->format('H:i') }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full sm:w-1/3">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#284fa0] text-sm font-medium text-white hover:bg-[#1e3b7a] focus:outline-none">
                                + Tambah Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="p-0 overflow-x-auto">
                <!-- Mengecek apakah pegawai ini punya jadwal atau belum -->
                @if($user->jadwalPegawai && $user->jadwalPegawai->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hari / Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Shift</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            
                            <!-- Looping data jadwal yang sudah dibawa oleh Controller -->
                            @foreach($user->jadwalPegawai as $jadwal)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y')}}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <!-- Menampilkan nama shift (Asumsi berelasi dengan tabel Shift) -->
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $jadwal->shift->nama_shift ?? 'Nama Shift' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <!-- Tombol Hapus -->
                                        @if(auth()->user()->role === 'manajer')
                                        <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                @else
                    <!-- Tampilan jika tabel jadwal_pegawai untuk user ini masih kosong -->
                    <div class="px-6 py-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-4 text-sm text-gray-500">Belum ada jadwal shift yang ditetapkan untuk pegawai ini.</p>
                    </div>
                @endif
            </div>

            @if(auth()->user()->role === 'manajer')

            <div class="mb-5 mt-5 flex gap-4 items-end bg-blue-50/50 p-4 rounded-lg border border-blue-100">
                    <a href="{{ route('jadwal-pegawai.template.download') }}" class="inline-flex items-center justify-center px-4 py-2 bg-[#284fa0] text-white text-sm font-medium rounded-md hover:bg-[#1e3b7a] transition-colors shadow-sm w-full">
                        Unduh Template Excel
                    </a>
            </div>

            <!-- Form Import Excel -->
            <form action="{{ route('jadwal.import') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-4 items-end bg-blue-50/50 p-4 rounded-lg border border-blue-100">
                @csrf

                @if(session('success'))
                    <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
                        <p class="text-sm text-yellow-700">{{ session('warning') }}</p>

                        @if(session('import_errors') && count(session('import_errors')) > 0)
                            <ul class="list-disc list-inside text-xs text-yellow-700 mt-2 max-h-40 overflow-y-auto">
                                @foreach(session('import_errors') as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

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

                <div class="w-full sm:w-2/3">
                    <label class="block text-xs font-semibold text-[#284fa0] mb-1">Import Jadwal Sebulan Penuh (Excel)</label>
                    <p class="text-[10px] text-gray-500 mb-2">Pastikan file sesuai dengan template yang ditentukan.</p>
                    <input type="file" name="file_excel" required accept=".xlsx,.xls,.csv" class="block w-full text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#284fa0] file:text-white hover:file:bg-[#1e3b7a]">
                </div>

                <div class="w-full sm:w-1/3 flex gap-2">
                    <button type="submit" class="w-full justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none">
                        Unggah File
                    </button>
                </div>
            </form>
            @endif

        </div>
    </div>

    <!-- Footer -->
    @include('partials.footer')

</body>
</html>
