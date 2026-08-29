<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Pegawai - Apoteka</title>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-slate-900">Manajemen Data Pegawai</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola informasi pegawai, dokumen, dan jadwal shift di sini.</p>
            </div>

            @include('partials.navigation')

            <!-- CONTENT: DATA PEGAWAI -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg leading-6 font-semibold text-slate-900">Daftar Pegawai</h3>
                    <a href="{{ route('pegawai.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-[#284fa0] hover:bg-[#1e3b7a] shadow-sm transition-colors">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Pegawai
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Posisi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Placeholder data for UI -->
                             @forelse($data as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- kolom nama pegawai -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($user->Foto_Profil)
                                            <div class="h-10 w-10 rounded-full bg-[#fde402] text-[#284fa0] flex items-center justify-center font-bold text-lg">
                                                <img src="{{ asset('storage/' . $user->Foto_Profil) }}" alt="{{ $user->name }}">
                                            </div>
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-[#fde402] text-[#284fa0] flex items-center justify-center font-bold text-lg">AJ</div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name ?? 'Pegawai Tidak Ditemukan' }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email ?? 'Email Tidak Ditemukan' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->posisi ?? 'Posisi Tidak Ditemukan' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $user->role ?? 'Role Tidak Ditemukan' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('pegawai.show', $user->id) }}" class="text-[#284fa0] hover:text-[#1e3b7a] mr-3">Detail</a>

                                    <!-- Tombol Edit (Menggunakan tag <a> ke route edit) -->
                                    <a href="{{ route('pegawai.edit', $user->id) }}" class="text-[#284fa0] hover:text-[#1e3b7a] mr-3">Edit</a>
                                    
                                    <!-- Tombol Hapus (Wajib menggunakan tag <form> dengan metode DELETE) -->
                                    <form action="{{ route('pegawai.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[#eb2128] hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 text-sm">
                                    Belum ada data pegawai.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    @include('partials.footer')

</body>
</html>
