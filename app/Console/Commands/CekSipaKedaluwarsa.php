<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DokumenPegawai;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class CekSipaKedaluwarsa extends Command
{
    protected $signature = 'sipa:cek';
    protected $description = 'Cek SIPA yang hampir kedaluwarsa dan kirim notif OneSignal ke Pegawai dan Manajer';

    public function handle()
    {
        // 1. Ambil semua ID OneSignal milik Manajer/Admin
        $manajerIds = User::where('role', 'manajer')
            ->whereNotNull('onesignal_id')
            ->pluck('onesignal_id')
            ->toArray();

        // 2. Cari dokumen yang kedaluwarsa dalam 6 bulan ke depan
        $dokumenHampirHabis = DokumenPegawai::with('user')
            ->whereNotNull('tanggal_kadaluarsa_sipa')
            ->where('tanggal_kadaluarsa_sipa', '<=', Carbon::now()->addMonths(6))
            ->get();

        foreach ($dokumenHampirHabis as $dokumen) {
            $user = $dokumen->user;
            
            if ($user) {
                $tanggal = Carbon::parse($dokumen->tanggal_kadaluarsa_sipa)->format('d M Y');

                // 3. Gabungkan ID Manajer dengan ID Pegawai yang bersangkutan
                $targetIds = $manajerIds; 
                
                if ($user->onesignal_id) {
                    $targetIds[] = $user->onesignal_id; 
                }

                $targetIds = array_unique($targetIds);

                if (!empty($targetIds)) {
                    
                    $pesan = "Peringatan SIPA: Dokumen SIPA milik {$user->name} akan kedaluwarsa pada {$tanggal}.";

                    $response = Http::withHeaders([
                        'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY'),
                        'Content-Type' => 'application/json',
                    ])->post('https://onesignal.com/api/v1/notifications', [
                        'app_id' => env('ONESIGNAL_APP_ID'),
                        'include_player_ids' => array_values($targetIds), // Array berisi gabungan ID
                        'contents' => [
                            'en' => $pesan,
                            'id' => $pesan
                        ],
                        'headings' => [
                            'en' => "⚠️ SIPA Hampir Habis",
                            'id' => "⚠️ SIPA Hampir Habis"
                        ]
                    ]);

                    $this->info("Mengirim notif SIPA {$user->name} ke " . count($targetIds) . " perangkat -> Status: " . $response->status());
                } else {
                    $this->warn("SIPA {$user->name} kedaluwarsa, tapi baik pegawai maupun manajer belum memiliki onesignal_id.");
                }
            }
        }

        $this->info('Pengecekan dan pengiriman notifikasi SIPA selesai dijalankan.');
    }
}