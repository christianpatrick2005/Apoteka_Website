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
        Schema::create('pengajuan_izin_cutis', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (siapa yang mengajukan cuti)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Pegawai yang akan menggantikan
            $table->foreignId('user_pengganti_id')->nullable()->constrained('users')->onDelete('set null');

            $table->enum('kategori', ['izin', 'cuti'])->default('cuti');
            
            // Informasi dasar form
            $table->date('tanggal_pengajuan');
            $table->string('durasi'); // Dalam format hari atau jam
            $table->text('keterangan');
            $table->text('alamat_tempat');
            // jenis cuti (Opsional - bisa ditambahkan jika diperlukan, misal: tahunan, sakit, khusus)
            $table->enum('jenis_cuti', ['cuti_tahunan', 'cuti_kehamilan','lainnya'])->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            
            // Berkas dan validasi (nullable untuk mengakomodasi cuti tahunan vs cuti khusus)
            $table->json('berkas_pendukung')->nullable();
            
            // Status persetujuan oleh manajer
            $table->enum('status_pengajuan', ['pending', 'disetujui', 'ditolak'])->default('pending');
            // status pengajuan sebagai pengganti
            $table->enum('status_pengganti', ['pending', 'disetujui', 'ditolak'])->default('pending');
            // Informasi persetujuan
            $table->date('tanggal_persetujuan')->nullable();
            $table->text('komentar_manajer')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izin_cutis');
    }
};
