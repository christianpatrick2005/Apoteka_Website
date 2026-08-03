<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanIzinCuti extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_cutis';

    protected $fillable = [
        'user_id',
        'kategori',
        'tanggal_pengajuan',
        'durasi',
        'keterangan',
        'alamat_tempat',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanda_tangan',
        'berkas_pendukung',
        'status_pengajuan',
        'tanggal_persetujuan',
        'komentar_manajer',
    ];

    // Mengubah kolom tanggal menjadi format Date/Carbon
    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_persetujuan' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
