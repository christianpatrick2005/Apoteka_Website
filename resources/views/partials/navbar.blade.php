    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('MainPage') }}" class="flex items-center gap-2">
                        <img class="w-35 h-20" fill="none" viewBox="0 0 24 24" src="{{ asset('images/logo_Apoteka.png') }}"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('MainPage') }}" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Beranda</a>
                    <a href="https://shopee.co.id/apoteka_bm#product_list" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Produk</a>
                    <a href="#" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Tentang Kami</a>

                    <a href="{{ route('pegawai.index') }}" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Manage Data</a>
                    <a href="{{ route('pengajuan-izin.index') }}" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Pengajuan Izin/Cuti</a>
                    <!-- verifikasi role -->
                    @auth
                        @if(auth()->user()->role === 'manajer')
                            <!-- <a href="{{ route('pegawai.index') }}" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Manage Pegawai</a>
                            <a href="{{ route('dokumen.index') }}" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Manage Dokumen</a>
                            <a href="{{ route('shift.index') }}" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Manage Shift</a>
                            <a href="{{ route('pengajuan-izin.index') }}" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Manage Pengajuan Izin Cuti</a> -->
                        @endif

                        @if(auth()->user()->role === 'pegawai')
                            <a href="{{ route('AjukanIzinCuti') }}" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Ajukan Izin Cuti</a>
                        @endif
                    @endauth
                </div>
                <div class="hidden md:flex items-center">
                    <a href="{{ route('login') }}" class="bg-[#fde402] hover:bg-[#284fa0] text-slate-900 hover:text-white px-6 py-2.5 rounded-full font-medium transition-all shadow-md shadow-[#fde402]/50 hover:shadow-lg hover:shadow-[#284fa0]/40">
                        Login Pegawai
                    </a>
                </div>
                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button id="mobile-menu-btn" class="text-slate-600 hover:text-[#284fa0] focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu-dropdown" class="md:hidden border-t border-gray-100 hidden">
            <div class="px-4 pt-2 pb-4 space-y-2 bg-white">
                <a href="#" class="block px-3 py-2 text-slate-600 hover:text-[#eb2128] hover:bg-slate-50 rounded-md font-medium transition-colors">Beranda</a>
                <a href="#" class="block px-3 py-2 text-slate-600 hover:text-[#eb2128] hover:bg-slate-50 rounded-md font-medium transition-colors">Produk</a>
                <a href="#" class="block px-3 py-2 text-slate-600 hover:text-[#eb2128] hover:bg-slate-50 rounded-md font-medium transition-colors">Tentang Kami</a>
                <a href="{{ route('pegawai.index') }}" class="block px-3 py-2 text-slate-600 hover:text-[#eb2128] hover:bg-slate-50 rounded-md font-medium transition-colors">Manage Data</a>
                <a href="{{ route('pengajuan-izin.index') }}" class="block px-3 py-2 text-slate-600 hover:text-[#eb2128] hover:bg-slate-50 rounded-md font-medium transition-colors">Pengajuan Izin/Cuti</a>
                
                @auth
                    @if(auth()->user()->role === 'pegawai')
                        <a href="{{ route('AjukanIzinCuti') }}" class="block px-3 py-2 text-slate-600 hover:text-[#eb2128] hover:bg-slate-50 rounded-md font-medium transition-colors">Ajukan Izin Cuti</a>
                    @endif
                @endauth
                
                <a href="{{ route('login') }}" class="block w-full mt-4 px-3 py-2 bg-[#fde402] hover:bg-[#284fa0] text-slate-900 hover:text-white rounded-md font-medium transition-colors text-center">
                    Login Pegawai
                </a>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu-dropdown');
            
            if (btn && menu) {
                btn.addEventListener('click', function () {
                    menu.classList.toggle('hidden');
                });
            }
        });
    </script>

