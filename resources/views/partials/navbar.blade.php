@php
    $notifikasiPengganti = [];
    $notifikasiSipa = [];
    $notifikasiManajer = []; // Tambahan variabel baru
    $totalNotif = 0;

    if(auth()->check()) {
        $user = auth()->user();

        // 1. Query Notifikasi Pengganti Cuti
        $notifikasiPengganti = \App\Models\PengajuanIzinCuti::where('user_pengganti_id', $user->id)
            ->where('status_pengganti', 'pending')
            ->with('user')
            ->get();

        // 2. Query Notifikasi Kedaluwarsa SIPA (< 6 Bulan)
        $querySipa = \App\Models\DokumenPegawai::with('user')
            ->whereNotNull('tanggal_kadaluarsa_sipa')
            ->where('tanggal_kadaluarsa_sipa', '<=', \Carbon\Carbon::now()->addMonths(6));

        if($user->role === 'pegawai') {
            $querySipa->where('user_id', $user->id);
        }

        $notifikasiSipa = $querySipa->get();

        // 3. Query Notifikasi Khusus Manajer (Menunggu Persetujuan)
        if($user->role === 'manajer') {
            $notifikasiManajer = \App\Models\PengajuanIzinCuti::with('user')
                ->where('status_pengajuan', 'pending')
                ->latest('created_at')
                ->get();
        }

        // Hitung total semua notifikasi untuk badge merah
        $totalNotif = count($notifikasiPengganti) + count($notifikasiSipa) + count($notifikasiManajer);
    }
@endphp

<!-- Navigation -->
<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <a href="{{ route('MainPage') }}" class="flex items-center gap-2">
                    <img class="w-35 h-20" fill="none" viewBox="0 0 24 24" src="{{ asset('images/logo_Apoteka.png') }}">
                </a>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('MainPage') }}" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Beranda</a>
                <a href="https://shopee.co.id/apoteka_bm#product_list" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Produk</a>
                <a href="#" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Tentang Kami</a>

                <!-- verifikasi role -->
                @auth
                    @if(auth()->user()->role === 'manajer')
                        <a href="{{ route('pegawai.index') }}" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Manage Data</a>
                        <a href="{{ route('pengajuan-izin.index') }}" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Pengajuan Izin/Cuti</a>
                    @endif

                    @if(auth()->user()->role === 'pegawai')
                        <a href="{{ route('pengajuan-izin.create') }}" class="text-slate-600 hover:text-[#eb2128] font-medium transition-colors">Ajukan Izin Cuti</a>
                    @endif
                @endauth
            </div>
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <div class="flex items-center space-x-4">
                        
                        <!-- Notification Dropdown Desktop -->
                        <div class="relative group">
                            <button class="relative p-2 text-slate-500 hover:text-slate-700 focus:outline-none transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                
                                <!-- Badge Total Notifikasi -->
                                @if($totalNotif > 0)
                                    <span class="absolute top-1 right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $totalNotif }}</span>
                                @endif
                            </button>
                            
                            <!-- Dropdown Menu Desktop -->
                            <div class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block border border-gray-100">
                                <div class="px-4 py-2 border-b border-gray-100 font-semibold text-sm text-gray-700">Notifikasi</div>
                                
                                @if($totalNotif > 0)
                                    <div class="max-h-64 overflow-y-auto">
                                        
                                        <!-- Looping Notifikasi Pengajuan Baru (Manajer) -->
                                        @if(auth()->user()->role === 'manajer')
                                            @foreach($notifikasiManajer as $notifM)
                                                <div class="px-4 py-3 border-b border-gray-50 hover:bg-blue-50/50 transition-colors">
                                                    <p class="text-sm text-gray-600 mb-1">
                                                        <span class="font-bold text-[#284fa0]">📄 Pengajuan Baru</span><br>
                                                        Pegawai <span class="font-semibold text-slate-800">{{ $notifM->user->name ?? 'Seseorang' }}</span> 
                                                        mengajukan permohonan {{ $notifM->kategori }}.
                                                    </p>
                                                    <a href="{{ route('pengajuan-izin.show-persetujuan', $notifM->id) }}" class="text-xs font-semibold text-[#284fa0] hover:underline">Proses Persetujuan &rarr;</a>
                                                </div>
                                            @endforeach
                                        @endif

                                        <!-- Looping Notifikasi SIPA -->
                                        @foreach($notifikasiSipa as $sipa)
                                            <div class="px-4 py-3 border-b border-gray-50 hover:bg-red-50/50 transition-colors">
                                                <p class="text-sm text-gray-600 mb-1">
                                                    <span class="font-bold text-red-600">⚠️ Peringatan SIPA</span><br>
                                                    @if(auth()->user()->role === 'manajer')
                                                        SIPA milik <span class="font-semibold text-slate-800">{{ $sipa->user->name ?? 'Pegawai' }}</span>
                                                    @else
                                                        SIPA Anda
                                                    @endif
                                                    akan kedaluwarsa pada <span class="font-semibold text-red-600">{{ \Carbon\Carbon::parse($sipa->tanggal_kadaluarsa_sipa)->translatedFormat('d M Y') }}</span>.
                                                </p>
                                                @if(auth()->user()->role === 'manajer')
                                                    <a href="{{ route('dokumen.show', $sipa->id) }}" class="text-xs text-[#284fa0] hover:underline">Lihat Detail Dokumen &rarr;</a>
                                                @endif
                                            </div>
                                        @endforeach

                                        <!-- Looping Notifikasi Pengganti -->
                                        @foreach($notifikasiPengganti as $notif)
                                            <div class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50">
                                                <p class="text-sm text-gray-600 mb-2"><span class="font-semibold">{{ $notif->user->name ?? 'Seseorang' }}</span> mengajukan Anda sebagai pengganti untuk {{ $notif->kategori }}.</p>
                                                <div class="flex space-x-2">
                                                    <form action="{{ route('pengajuan-izin.persetujuan-pengganti', $notif->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status_pengganti" value="disetujui">
                                                        <button type="submit" class="px-3 py-1 bg-emerald-500 text-white text-xs rounded hover:bg-emerald-600 transition-colors">Setuju</button>
                                                    </form>
                                                    <form action="{{ route('pengajuan-izin.persetujuan-pengganti', $notif->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status_pengganti" value="ditolak">
                                                        <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition-colors">Tolak</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                @else
                                    <div class="px-4 py-3 text-sm text-gray-500 text-center">Belum ada notifikasi baru.</div>
                                @endif
                            </div>
                        </div>

                        <div class="text-slate-600 font-medium">
                            Halo, {{ auth()->user()->name }} 
                            <span class="text-xs bg-[#284fa0] text-white px-2 py-1 rounded-full ml-1">{{ ucfirst(auth()->user()->role) }}</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 px-4 py-2 rounded-full font-medium transition-all shadow-sm">
                                Logout
                            </button>
                        </form>
                    </div>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="bg-[#fde402] hover:bg-[#284fa0] text-slate-900 hover:text-white px-6 py-2.5 rounded-full font-medium transition-all shadow-md shadow-[#fde402]/50 hover:shadow-lg hover:shadow-[#284fa0]/40">
                        Login Pegawai
                    </a>
                @endguest
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
            
            @auth
                @if(auth()->user()->role === 'manajer')
                    <a href="{{ route('pegawai.index') }}" class="block px-3 py-2 text-slate-600 hover:text-[#eb2128] hover:bg-slate-50 rounded-md font-medium transition-colors">Manage Data</a>
                    <a href="{{ route('pengajuan-izin.index') }}" class="block px-3 py-2 text-slate-600 hover:text-[#eb2128] hover:bg-slate-50 rounded-md font-medium transition-colors">Pengajuan Izin/Cuti</a>
                @endif

                @if(auth()->user()->role === 'pegawai')
                    <a href="{{ route('pengajuan-izin.create') }}" class="block px-3 py-2 text-slate-600 hover:text-[#eb2128] hover:bg-slate-50 rounded-md font-medium transition-colors">Ajukan Izin Cuti</a>
                @endif
            @endauth
            
            @auth
                <!-- Mobile Notifikasi -->
                @if($totalNotif > 0)
                    <div class="block px-3 py-2 mt-2 text-sm font-semibold text-slate-700 bg-gray-50 rounded-md">
                        Anda memiliki {{ $totalNotif }} notifikasi baru.
                        <div class="mt-2 space-y-2">
                            
                            <!-- Mobile Looping Notifikasi Manajer -->
                            @if(auth()->user()->role === 'manajer')
                                @foreach($notifikasiManajer as $notifM)
                                    <div class="p-3 bg-blue-50 border border-blue-200 rounded shadow-sm">
                                        <p class="text-xs text-gray-800 mb-1">
                                            <span class="font-bold text-[#284fa0]">📄 Pengajuan Baru</span><br>
                                            Pegawai <span class="font-semibold">{{ $notifM->user->name ?? 'Seseorang' }}</span> 
                                            mengajukan {{ $notifM->kategori }}.
                                        </p>
                                        <a href="{{ route('pengajuan-izin.show-persetujuan', $notifM->id) }}" class="text-[10px] font-semibold text-[#284fa0]">Proses Persetujuan &rarr;</a>
                                    </div>
                                @endforeach
                            @endif

                            <!-- Mobile Looping Notifikasi SIPA -->
                            @foreach($notifikasiSipa as $sipa)
                                <div class="p-3 bg-red-50 border border-red-200 rounded shadow-sm">
                                    <p class="text-xs text-gray-800 mb-1">
                                        <span class="font-bold text-red-600">⚠️ Peringatan SIPA</span><br>
                                        @if(auth()->user()->role === 'manajer')
                                            SIPA milik <span class="font-semibold">{{ $sipa->user->name ?? 'Pegawai' }}</span>
                                        @else
                                            SIPA Anda
                                        @endif
                                        akan kedaluwarsa pada <span class="font-bold text-red-600">{{ \Carbon\Carbon::parse($sipa->tanggal_kadaluarsa_sipa)->translatedFormat('d M Y') }}</span>.
                                    </p>
                                    @if(auth()->user()->role === 'manajer')
                                        <a href="{{ route('dokumen.show', $sipa->id) }}" class="text-[10px] font-semibold text-[#284fa0]">Detail &rarr;</a>
                                    @endif
                                </div>
                            @endforeach

                            <!-- Mobile Looping Notifikasi Pengganti -->
                            @foreach($notifikasiPengganti as $notif)
                                <div class="p-3 bg-white border border-gray-200 rounded shadow-sm">
                                    <p class="text-xs text-gray-600 mb-2"><span class="font-semibold">{{ $notif->user->name ?? 'Seseorang' }}</span> mengajukan Anda sebagai pengganti.</p>
                                    <div class="flex space-x-2">
                                        <form action="{{ route('pengajuan-izin.persetujuan-pengganti', $notif->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status_pengganti" value="disetujui">
                                            <button type="submit" class="w-full py-1.5 bg-emerald-500 text-white text-[10px] font-medium rounded hover:bg-emerald-600">Setuju</button>
                                        </form>
                                        <form action="{{ route('pengajuan-izin.persetujuan-pengganti', $notif->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status_pengganti" value="ditolak">
                                            <button type="submit" class="w-full py-1.5 bg-red-500 text-white text-[10px] font-medium rounded hover:bg-red-600">Tolak</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                            
                        </div>
                    </div>
                @endif

                <div class="block px-3 py-2 mt-2 text-slate-600 font-medium border-t border-gray-100 pt-4">
                    Halo, {{ auth()->user()->name }} <span class="text-xs font-bold text-[#284fa0]">({{ ucfirst(auth()->user()->role) }})</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="block w-full mt-2">
                    @csrf
                    <button type="submit" class="w-full px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 rounded-md font-medium transition-colors text-center">
                        Logout
                    </button>
                </form>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="block w-full mt-4 px-3 py-2 bg-[#fde402] hover:bg-[#284fa0] text-slate-900 hover:text-white rounded-md font-medium transition-colors text-center">
                    Login Pegawai
                </a>
            @endguest
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