<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'alamat_surabaya',
        'alamat_asal',
        'nomor_hp',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'status_pernikahan',
        'jenis_kelamin',
        'posisi',
        'gaji',
        'nomor_ktp',
        'kewarganegaraan',
        'role',
        'jatah_cuti',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi One-to-One: Satu user memiliki satu set dokumen pegawai
     */
    public function dokumenPegawai()
    {
        return $this->hasOne(DokumenPegawai::class, 'user_id');
    }

    /**
     * Relasi One-to-Many: Satu user bisa memiliki banyak pengajuan cuti
     */
    public function pengajuanCuti()
    {
        return $this->hasMany(PengajuanIzinCuti::class, 'user_id');
    }

    /**
     * Relasi One-to-Many: Satu user bisa memiliki banyak jadwal shift
     */
    public function jadwalPegawai()
    {
        return $this->hasMany(JadwalPegawai::class, 'user_id');
    }
}
