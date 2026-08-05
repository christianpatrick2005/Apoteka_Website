<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Dokumen - Apoteka</title>
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-slate-900">Manajemen Data Pegawai</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola informasi pegawai, dokumen, dan jadwal shift di sini.</p>
            </div>

            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200 mb-8 bg-white rounded-t-xl px-6 pt-4 shadow-sm">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="{{ route('ManagePegawai') }}" class="whitespace-nowrap py-4 px-1 text-base focus:outline-none border-b-4 border-transparent text-slate-500 font-medium hover:text-[#284fa0] hover:border-slate-300">
                        Data Pegawai
                    </a>
                    <a href="{{ route('ManageDokumen') }}" class="whitespace-nowrap py-4 px-1 text-base focus:outline-none border-b-4 border-[#284fa0] text-[#284fa0] font-semibold">
                        Dokumen Pegawai
                    </a>
                    <a href="{{ route('ManageShift') }}" class="whitespace-nowrap py-4 px-1 text-base focus:outline-none border-b-4 border-transparent text-slate-500 font-medium hover:text-[#284fa0] hover:border-slate-300">
                        Jadwal Shift
                    </a>
                </nav>
            </div>

            <!-- CONTENT: DOKUMEN PEGAWAI -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg leading-6 font-semibold text-slate-900">Daftar Dokumen Pegawai</h3>
                    <button onclick="openModal('modal-dokumen')" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-[#284fa0] hover:bg-[#1e3b7a] shadow-sm transition-colors">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Upload Dokumen
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Pegawai</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status SIPA</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tgl Exp. SIPA</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Placeholder data for UI -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Ahmad Jaelani</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Uploaded
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    12 Okt 2027
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-[#284fa0] hover:text-[#1e3b7a] mr-3">Detail/Edit</button>
                                    <button class="text-[#eb2128] hover:text-red-900">Hapus</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Modal: Form Dokumen Pegawai -->
    <div id="modal-dokumen" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal('modal-dokumen')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                <div class="bg-[#284fa0] px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-semibold text-white" id="modal-title">Form Dokumen Pegawai</h3>
                    <button type="button" onclick="closeModal('modal-dokumen')" class="text-white hover:text-[#fde402] focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[70vh] overflow-y-auto">
                    <form action="#" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pilih Pegawai (User ID)</label>
                            <select name="user_id" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                                <option value="1">Ahmad Jaelani</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File Ijasah</label>
                            <input type="file" name="ijasah" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File Transkrip</label>
                            <input type="file" name="transkrip" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File KTP</label>
                            <input type="file" name="ktp" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File STR</label>
                            <input type="file" name="str" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sertifikat Kompetensi</label>
                            <input type="file" name="sertifikat_kompetensi" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">File SIPA</label>
                            <input type="file" name="sipa" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#284fa0]/10 file:text-[#284fa0] hover:file:bg-[#284fa0]/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Kadaluarsa SIPA</label>
                            <input type="date" name="tanggal_kadaluarsa_sipa" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#284fa0] focus:border-[#284fa0] sm:text-sm">
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#284fa0] text-base font-medium text-white hover:bg-[#1e3b7a] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModal('modal-dokumen')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts for interactivity -->
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
</body>
</html>
