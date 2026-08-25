<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Dikosongkan agar tidak menyebabkan error "Duplicate column" saat di-hosting.
        // Kolom sudah ada di migration awal (2026_08_03_125344_create_pengajuan_izin_cutis_table.php)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
