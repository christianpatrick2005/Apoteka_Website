<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenPegawai extends Model
{
    use HasFactory;

    protected $table = 'dokumen_pegawais';

    protected $fillable = [
        'user_id',
        'ijasah',
        'transkrip',
        'ktp',
        'str',
        'sertifikat_kompetensi',
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
