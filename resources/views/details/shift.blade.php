<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Shift - Apoteka</title>
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
                    <h1 class="text-3xl font-extrabold text-slate-900">Detail Shift</h1>
                    <p class="mt-2 text-sm text-slate-500">Informasi lengkap data jadwal shift kerja.</p>
                </div>
                <a href="{{ route('shift.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 shadow-sm transition-colors">
                    Kembali
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50">
                    <h3 class="text-lg leading-6 font-semibold text-slate-900">Informasi Shift</h3>
                </div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Nama Shift</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $shift->nama_shift }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Jam Masuk</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $shift->jam_masuk }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Jam Keluar</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $shift->jam_keluar }}</dd>
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
