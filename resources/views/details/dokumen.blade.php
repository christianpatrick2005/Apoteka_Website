<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Dokumen - Apoteka</title>
    @vite('resources/css/app.css')
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-slate-800 antialiased selection:bg-[#fde402] selection:text-slate-900 min-h-screen flex flex-col font-['Inter']">

    <!-- Navigation -->
    @include('partials.navbar')

    <!-- Main Content -->
    <div class="flex-grow pt-24 pb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900">Detail Dokumen Pegawai</h1>
                    <p class="mt-2 text-sm text-slate-500">Lihat berkas dokumen dari pegawai terkait.</p>
                </div>
                <a href="{{ route('dokumen.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-colors">
                    Kembali
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-lg leading-6 font-semibold text-slate-900">Informasi Dokumen</h3>
                </div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Nama Pegawai</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $dokumenPegawai->user->name ?? 'Pegawai Tidak Ditemukan' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Tanggal Kedaluwarsa SIPA</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $dokumenPegawai->tanggal_kadaluarsa_sipa ? \Carbon\Carbon::parse($dokumenPegawai->tanggal_kadaluarsa_sipa)->format('d M Y') : '-' }}</dd>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Daftar Berkas Terlampir</dt>
                            <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                
                                @if($dokumenPegawai->ktp)
                                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                    <div class="w-0 flex-1 flex items-center">
                                        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="ml-2 flex-1 w-0 truncate">KTP</span>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <a href="{{ asset('storage/' . $dokumenPegawai->ktp) }}" target="_blank" class="font-medium text-[#284fa0] hover:text-[#1e3b7a]">Lihat/Unduh</a>
                                    </div>
                                </li>
                                @endif

                                @if($dokumenPegawai->ijasah)
                                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                    <div class="w-0 flex-1 flex items-center">
                                        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="ml-2 flex-1 w-0 truncate">Ijazah</span>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <a href="{{ asset('storage/' . $dokumenPegawai->ijasah) }}" target="_blank" class="font-medium text-[#284fa0] hover:text-[#1e3b7a]">Lihat/Unduh</a>
                                    </div>
                                </li>
                                @endif

                                @if($dokumenPegawai->transkrip)
                                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                    <div class="w-0 flex-1 flex items-center">
                                        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="ml-2 flex-1 w-0 truncate">Transkrip</span>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <a href="{{ asset('storage/' . $dokumenPegawai->transkrip) }}" target="_blank" class="font-medium text-[#284fa0] hover:text-[#1e3b7a]">Lihat/Unduh</a>
                                    </div>
                                </li>
                                @endif

                                @if($dokumenPegawai->str)
                                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                    <div class="w-0 flex-1 flex items-center">
                                        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="ml-2 flex-1 w-0 truncate">STR</span>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <a href="{{ asset('storage/' . $dokumenPegawai->str) }}" target="_blank" class="font-medium text-[#284fa0] hover:text-[#1e3b7a]">Lihat/Unduh</a>
                                    </div>
                                </li>
                                @endif

                                @if($dokumenPegawai->sertifikat_kompetensi)
                                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                    <div class="w-0 flex-1 flex items-center">
                                        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="ml-2 flex-1 w-0 truncate">Sertifikat Kompetensi</span>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <a href="{{ asset('storage/' . $dokumenPegawai->sertifikat_kompetensi) }}" target="_blank" class="font-medium text-[#284fa0] hover:text-[#1e3b7a]">Lihat/Unduh</a>
                                    </div>
                                </li>
                                @endif

                                @if($dokumenPegawai->sipa)
                                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                    <div class="w-0 flex-1 flex items-center">
                                        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="ml-2 flex-1 w-0 truncate">SIPA</span>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <a href="{{ asset('storage/' . $dokumenPegawai->sipa) }}" target="_blank" class="font-medium text-[#284fa0] hover:text-[#1e3b7a]">Lihat/Unduh</a>
                                    </div>
                                </li>
                                @endif

                                @if(!$dokumenPegawai->ktp && !$dokumenPegawai->ijasah && !$dokumenPegawai->transkrip && !$dokumenPegawai->str && !$dokumenPegawai->sertifikat_kompetensi && !$dokumenPegawai->sipa)
                                <li class="pl-3 pr-4 py-3 flex items-center justify-center text-sm text-gray-500">
                                    Tidak ada berkas yang diunggah
                                </li>
                                @endif
                            </ul>
                        </div>
                    </dl>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    @include('partials.footer')

</body>
</html>
