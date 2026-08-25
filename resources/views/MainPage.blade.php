<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apoteka - Solusi Kesehatan Anda</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Apoteka - Bahagia Medifarma2.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo Apoteka - Bahagia Medifarma2.png') }}">
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
                                <a href="https://shopee.co.id/apoteka_bm#product_list" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-[#284fa0] hover:bg-[#1e3b7a] md:py-4 md:text-lg md:px-10 transition-all">
                                    Pesan Obat & Lihat Produk
                                </a>
                            </div>
                            
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="https://wa.me/6285182238223?text=Halo%20Apoteka,%20saya%20ingin%20konsultasi" class="w-full flex items-center justify-center px-8 py-3 border border-[#284fa0]/20 text-base font-medium rounded-full text-[#eb2128] bg-white hover:bg-slate-50 md:py-4 md:text-lg md:px-10 transition-all">
                                    Konsultasi
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-[#fde402] flex items-center justify-center">
            <img class="relative z-10 h-56 w-full object-cover sm:h-72 md:h-96 lg:w-[85%] lg:h-[85%] rounded-3xl shadow-2xl lg:translate-x-8" src="{{asset('images/TokoApoteka.webp')}}" alt="Apoteker melayani pelanggan">
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

    <!-- Testimonial Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Apa Kata Pelanggan Kami</h2>
                <p class="mt-4 max-w-2xl mx-auto text-sm text-slate-500">Pengalaman nyata dari mereka yang telah mempercayakan kebutuhan kesehatannya bersama Apoteka.</p>
            </div>
            
            <div class="mt-12 grid grid-cols-1 gap-y-8 gap-x-6 sm:grid-cols-2 lg:grid-cols-3">
                
                <!-- Testimoni 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow flex flex-col justify-between">
                    <div>
                        <!-- Rating Bintang -->
                        <div class="flex items-center mb-4 space-x-1">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 text-[#fde402]" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="text-slate-600 italic leading-relaxed mb-6">"Pelayanan di Apoteka sangat cepat dan profesional. Obat resep yang saya butuhkan selalu tersedia dan produknya dijamin 100% asli."</p>
                    </div>
                    <div class="flex items-center pt-4 border-t border-gray-50">
                        <div class="h-10 w-10 rounded-full bg-[#284fa0]/10 flex items-center justify-center text-[#284fa0] font-bold">BS</div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-slate-900">Budi Santoso</p>
                            <p class="text-xs text-slate-500">Wiraswasta</p>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow flex flex-col justify-between">
                    <div>
                        <!-- Rating Bintang -->
                        <div class="flex items-center mb-4 space-x-1">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 text-[#fde402]" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="text-slate-600 italic leading-relaxed mb-6">"Sangat terbantu dengan pelayanan apotek ini. Harga produk vitaminnya sangat bersaing dan konsultasi dengan apotekernya ramah sekali."</p>
                    </div>
                    <div class="flex items-center pt-4 border-t border-gray-50">
                        <div class="h-10 w-10 rounded-full bg-[#fde402]/20 flex items-center justify-center text-slate-800 font-bold">SA</div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-slate-900">Siti Aminah</p>
                            <p class="text-xs text-slate-500">Ibu Rumah Tangga</p>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow flex flex-col justify-between">
                    <div>
                        <!-- Rating Bintang -->
                        <div class="flex items-center mb-4 space-x-1">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 text-[#fde402]" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="text-slate-600 italic leading-relaxed mb-6">"Apotek terpercaya keluarga kami. Pengiriman alat kesehatan selalu tepat waktu dan kualitasnya sangat terjamin."</p>
                    </div>
                    <div class="flex items-center pt-4 border-t border-gray-50">
                        <div class="h-10 w-10 rounded-full bg-[#284fa0]/10 flex items-center justify-center text-[#284fa0] font-bold">AW</div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-slate-900">Andi Wijaya</p>
                            <p class="text-xs text-slate-500">Pegawai Swasta</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- insatgram section apoteka -->
    <div class="py-6 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Media Sosial</h2>
            <p class="mt-4 text-lg text-slate-500">Berikut adalah postingan terbaru dari Instagram kami</p>

            <!-- Widget Elfsight -->
            <div class="elfsight-app-91865fb5-c91b-483e-a5a5-34b15862ada7" data-elfsight-app-lazy></div>

        </div>
    </div>

    <!-- Footer -->
    @include('partials.footer')

</body>
</html>
<!-- Script Elfsight -->
<script src="https://elfsightcdn.com/platform.js" async></script>
