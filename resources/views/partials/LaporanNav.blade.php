<!-- Tabs Navigation -->
<div class="border-b border-gray-200 mb-8 bg-white rounded-t-xl px-6 pt-4 shadow-sm">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        <a href="{{ route('laporan.sisa-cuti') }}" class="whitespace-nowrap py-4 px-1 text-base focus:outline-none border-b-4 {{ request()->routeIs('laporan.sisa-cuti') ? 'border-[#284fa0] text-[#284fa0]' : 'border-transparent text-slate-500 hover:text-[#284fa0] hover:border-slate-300' }} font-medium">
            Laporan Sisa Cuti
        </a>
        <a href="{{ route('laporan.riwayat-cuti') }}" class="whitespace-nowrap py-4 px-1 text-base focus:outline-none border-b-4 {{ request()->routeIs('laporan.riwayat-cuti') ? 'border-[#284fa0] text-[#284fa0]' : 'border-transparent text-slate-500 hover:text-[#284fa0] hover:border-slate-300' }} font-medium">
            Laporan Riwayat Izin-Cuti
        </a>
        <!-- <a href="{{ route('shift.index') }}" class="whitespace-nowrap py-4 px-1 text-base focus:outline-none border-b-4 border-transparent text-slate-500 font-medium hover:text-[#284fa0] hover:border-slate-300">
            Jadwal Shift
        </a> -->
    </nav>
</div>