<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPegawai extends Model
{


    protected $table = 'jadwal_pegawais';

    protected $fillable = [
        'user_id',
        'shift_id',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke model User (Jadwal ini milik siapa)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke model Shift (Jadwal ini menggunakan shift apa)
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
}
