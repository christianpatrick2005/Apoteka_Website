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
        Schema::table('dokumen_pegawais', function (Blueprint $table) {
            // 1. Rename kolom 'ijasah' lama menjadi 'ijazah_s1'
            $table->renameColumn('ijasah', 'ijazah_s1');

            $table->renameColumn('transkrip', 'ijazah_s2');
            $table->renameColumn('sertifikat_kompetensi', 'dokumen_profesi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen_pegawais', function (Blueprint $table) {
            // Kembalikan ke struktur awal jika di-rollback
            $table->renameColumn('ijazah_s1', 'ijasah');
            $table->renameColumn('ijazah_s2', 'transkrip');
            $table->renameColumn('dokumen_profesi', 'sertifikat_kompetensi');
        });
    }
};