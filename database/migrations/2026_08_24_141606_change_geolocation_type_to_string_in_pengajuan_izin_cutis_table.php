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
        // Kolom geolocation sudah ada di migration awal.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
