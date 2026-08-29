<style>
    /* CSS Khusus untuk Kop Surat */
    .table-kop {
        width: 100%;
        border-collapse: collapse;
        /* INI ADALAH GARIS PEMBATAS BAWAH KOP SURAT */
        border-bottom: 4px solid #e31e24; 
        margin-bottom: 20px; /* Jarak antara garis pembatas dan judul */
    }
    
    /* Timpa style td dari CSS utama agar td di kop surat tidak ikut ada kotaknya */
    .table-kop td {
        border: none !important; 
        padding: 5px 0 15px 0 !important;
        vertical-align: middle;
    }

    .kop-logo {
        width: 20%;
        text-align: left;
    }
    
    .kop-logo img {
        width: 130px; /* Silakan sesuaikan ukuran logo di sini */
        height: auto;
    }

    .kop-info {
        width: 60%;
        text-align: center;
    }

    .kop-info h1 {
        margin: 0 0 5px 0;
        font-size: 18px;
        font-weight: bold;
        color: #284fa0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .kop-info p {
        margin: 2px 0;
        font-size: 11px;
        color: #1f2937;
    }

    .kop-spacer {
        width: 20%;
    }
</style>

<table class="table-kop">
    <tr>
        <!-- Kolom 1: Logo -->
        <td class="kop-logo">
            <!-- WAJIB MENGGUNAKAN public_path() BUKAN asset() UNTUK DOMPDF -->
            <img src="{{ public_path('Images/Logo Apoteka - Resize.png') }}" alt="Logo Apoteka">
        </td>
        
        <!-- Kolom 2: Informasi Perusahaan -->
        <td class="kop-info">
            <h1>PT. BAHAGIA MEDIFARMA NUSANTARA</h1>
            <p>Alamat: JL. Karang Asem IV No 63, Gading, Tambaksari</p>
            <p>Telp: 0851-8223-8223 &nbsp;|&nbsp; Email: medifarma.sby@gmail.com</p>
        </td>

        <!-- Kolom 3: Spacer (Penyeimbang agar text benar-benar di tengah) -->
        <td class="kop-spacer"></td>
    </tr>
</table>