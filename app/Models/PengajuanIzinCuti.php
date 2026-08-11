<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanIzinCuti extends Model
{


    protected $table = 'pengajuan_izin_cutis';

    protected $fillable = [
        'user_id',
        'user_pengganti_id',
        'kategori',
        'tanggal_pengajuan',
        'durasi',
        'keterangan',
        'alamat_tempat',
        'jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'berkas_pendukung',
        'status_pengajuan',
        'status_pengganti',
        'tanggal_persetujuan',
        'komentar_manajer',
    ];

    // Mengubah kolom tanggal menjadi format Date/Carbon
    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_persetujuan' => 'date',
        'berkas_pendukung' => 'array', //untuk array json
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userPengganti(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_pengganti_id');
    }
}
