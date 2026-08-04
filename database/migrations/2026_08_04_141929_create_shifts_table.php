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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('nama_shift'); // Contoh: Pagi, Siang, dan Malam
            $table->time('jam_masuk');    // Format jam masuk (contoh: 07:00:00)
            $table->time('jam_keluar');   // Format jam keluar (contoh: 15:00:00)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
