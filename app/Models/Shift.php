<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{


    protected $table = 'shifts';

    protected $fillable = [
        'nama_shift',
        'jam_masuk',
        'jam_keluar',
    ];

    /**
     * Relasi One-to-Many: Satu jenis shift bisa dimiliki oleh banyak jadwal pegawai
     */
    public function jadwalPegawai(): HasMany
    {
        return $this->hasMany(JadwalPegawai::class, 'shift_id');
    }
}
