<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apoteka - Solusi Kesehatan Anda</title>
    @vite('resources/css/app.css')
    <!-- Optional: Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-slate-800 antialiased selection:bg-[#fde402] selection:text-slate-900">

    <!-- Navigation -->
    @include('partials.navbar')

    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-20 px-4 sm:px-6 lg:px-8 bg-[#fde402]">
                <main class="mt-10 mx-auto max-w-7xl sm:mt-12 md:mt-16 lg:mt-20 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <span class="inline-block py-1 px-3 rounded-full bg-white text-[#284fa0] text-sm font-semibold mb-4 border border-[#284fa0]/20">Solusi Kesehatan Terpercaya</span>
                        <h1 class="text-4xl tracking-tight font-extrabold text-slate-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Kesehatan Anda adalah</span>
                            <span class="block text-[#284fa0] xl:inline">Prioritas Kami</span>
                        </h1>
                        <p class="mt-3 text-base text-slate-800 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0 font-medium">
                            Apoteka menyediakan berbagai obat-obatan asli, alat kesehatan, vitamin, dan produk kesehatan lainnya. Dapatkan konsultasi gratis dengan apoteker profesional kami.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-full shadow-md shadow-[#284fa0]/30">
                                <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-[#284fa0] hover:bg-[#1e3b7a] md:py-4 md:text-lg md:px-10 transition-all">
                                    Pesan Obat
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-[#284fa0]/20 text-base font-medium rounded-full text-[#eb2128] bg-white hover:bg-slate-50 md:py-4 md:text-lg md:px-10 transition-all">
                                    Lihat Produk
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-[#284fa0]/20 text-base font-medium rounded-full text-[#284fa0] bg-white hover:bg-slate-50 md:py-4 md:text-lg md:px-10 transition-all">
                                    Konsultasi
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-[#fde402] flex items-center justify-center">
            <img class="relative z-10 h-56 w-full object-cover sm:h-72 md:h-96 lg:w-[85%] lg:h-[85%] rounded-3xl shadow-2xl lg:translate-x-8" src="https://images.unsplash.com/photo-1576602976047-174e57a47881?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Apoteker melayani pelanggan">
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center mb-12">
                <h2 class="text-base text-[#eb2128] font-semibold tracking-wide uppercase">Layanan Unggulan</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    Cara Lebih Baik Menjaga Kesehatan
                </p>
                <p class="mt-4 max-w-2xl text-xl text-slate-500 lg:mx-auto">
                    Kami memastikan Anda mendapatkan produk dan pelayanan terbaik untuk kesehatan Anda dan keluarga.
                </p>
            </div>

            <div class="mt-10">
                <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 lg:grid-cols-4 md:gap-x-8 md:gap-y-10">
                    
                    <div class="relative bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-xl bg-[#284fa0]/10 text-[#284fa0]">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-slate-900">Produk Asli</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-slate-500">
                            Semua obat dan produk kesehatan dijamin keasliannya dan terdaftar resmi.
                        </dd>
                    </div>

                    <div class="relative bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-xl bg-[#284fa0]/10 text-[#284fa0]">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-slate-900">Pelayanan Cepat</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-slate-500">
                            Apotek kami siap melayani anda dengan cepat dan tanggap.
                        </dd>
                    </div>

                    <div class="relative bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-xl bg-[#284fa0]/10 text-[#284fa0]">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-slate-900">Pengiriman Gratis</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-slate-500">
                            Pesanan Anda akan segera diantar dengan aman dan tanpa biaya.
                        </dd>
                    </div>

                    <div class="relative bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-xl bg-[#284fa0]/10 text-[#284fa0]">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-slate-900">Konsultasi Ahli</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-slate-500">
                            Apoteker profesional siap memberikan konsultasi secara gratis.
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Category Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Kategori Populer</h2>
            
            <div class="mt-8 grid grid-cols-2 gap-y-6 gap-x-4 sm:grid-cols-2 md:grid-cols-4 lg:gap-x-8">
                <div class="group relative bg-[#fde402]/20 rounded-2xl p-6 hover:bg-[#fde402] transition-colors cursor-pointer text-center flex flex-col items-center justify-center min-h-[160px]">
                    <div class="p-3 bg-white rounded-full group-hover:bg-[#284fa0] transition-colors mb-4">
                        <svg class="h-8 w-8 text-[#284fa0] group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 group-hover:text-slate-900">Obat Resep</h3>
                </div>

                <div class="group relative bg-[#fde402]/20 rounded-2xl p-6 hover:bg-[#fde402] transition-colors cursor-pointer text-center flex flex-col items-center justify-center min-h-[160px]">
                    <div class="p-3 bg-white rounded-full group-hover:bg-[#284fa0] transition-colors mb-4">
                        <svg class="h-8 w-8 text-[#284fa0] group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 group-hover:text-slate-900">Vitamin</h3>
                </div>

                <div class="group relative bg-[#fde402]/20 rounded-2xl p-6 hover:bg-[#fde402] transition-colors cursor-pointer text-center flex flex-col items-center justify-center min-h-[160px]">
                    <div class="p-3 bg-white rounded-full group-hover:bg-[#284fa0] transition-colors mb-4">
                        <svg class="h-8 w-8 text-[#284fa0] group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 group-hover:text-slate-900">Perawatan Tubuh</h3>
                </div>

                <div class="group relative bg-[#fde402]/20 rounded-2xl p-6 hover:bg-[#fde402] transition-colors cursor-pointer text-center flex flex-col items-center justify-center min-h-[160px]">
                    <div class="p-3 bg-white rounded-full group-hover:bg-[#284fa0] transition-colors mb-4">
                        <svg class="h-8 w-8 text-[#284fa0] group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900 group-hover:text-slate-900">P3K</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('partials.footer')

</body>
</html>
