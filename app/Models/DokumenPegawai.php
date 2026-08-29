<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenPegawai extends Model
{

    protected $table = 'dokumen_pegawais';

    protected $fillable = [
        'user_id',
        'ijazah_s1',
        'ijazah_s2',
        'ktp',
        'str',
        'dokumen_profesi',
        'sipa',
        'tanggal_kadaluarsa_sipa',
    ];

    // Mengubah tipe data tanggal otomatis menjadi objek Carbon (mudah diformat)
    protected $casts = [
        'tanggal_kadaluarsa_sipa' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
