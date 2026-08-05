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
        Schema::create('dokumen_pegawais', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (karyawan/pegawai)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Kolom dokumen di-set nullable agar bisa diupload bertahap
            $table->string('ijasah')->nullable();
            $table->string('transkrip')->nullable();
            $table->string('ktp')->nullable();
            $table->string('str')->nullable();
            $table->string('sertifikat_kompetensi')->nullable();
            $table->string('sipa')->nullable();
            
            // Kolom tanggal kadaluarsa SIPA untuk keperluan reminder
            $table->date('tanggal_kadaluarsa_sipa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_pegawais');
    }
};
