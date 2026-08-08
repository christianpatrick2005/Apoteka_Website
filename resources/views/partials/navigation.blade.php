            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200 mb-8 bg-white rounded-t-xl px-6 pt-4 shadow-sm">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="{{ route('pegawai.index') }}" class="whitespace-nowrap py-4 px-1 text-base focus:outline-none border-b-4 border-transparent text-slate-500 font-medium hover:text-[#284fa0] hover:border-slate-300">
                        Data Pegawai
                    </a>
                    <a href="{{ route('dokumen.index') }}" class="whitespace-nowrap py-4 px-1 text-base focus:outline-none border-b-4 border-transparent text-slate-500 font-medium hover:text-[#284fa0] hover:border-slate-300">
                        Dokumen Pegawai
                    </a>
                    <a href="{{ route('shift.index') }}" class="whitespace-nowrap py-4 px-1 text-base focus:outline-none border-b-4 border-transparent text-slate-500 font-medium hover:text-[#284fa0] hover:border-slate-300">
                        Jadwal Shift
                    </a>
                </nav>
            </div>