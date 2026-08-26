<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DokumenPegawai;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class CekSipaKedaluwarsa extends Command
{
    protected $signature = 'sipa:cek';
    protected $description = 'Cek SIPA yang hampir kedaluwarsa dan kirim notif OneSignal';

    public function handle()
    {
        // Cari dokumen yang kedaluwarsa dalam 6 bulan ke depan
        $dokumenHampirHabis = DokumenPegawai::with('user')
            ->whereNotNull('tanggal_kadaluarsa_sipa')
            ->where('tanggal_kadaluarsa_sipa', '<=', Carbon::now()->addMonths(6))
            ->get();

        foreach ($dokumenHampirHabis as $dokumen) {
            $user = $dokumen->user;

            // Jika user punya ID OneSignal, kirim notifikasinya!
            if ($user && $user->onesignal_id) {
                $tanggal = Carbon::parse($dokumen->tanggal_kadaluarsa_sipa)->format('d M Y');

                Http::withHeaders([
                    'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY'),
                    'Content-Type' => 'application/json',
                ])->post('https://onesignal.com/api/v1/notifications', [
                    'app_id' => env('ONESIGNAL_APP_ID'),
                    'include_player_ids' => [$user->onesignal_id],
                    'contents' => [
                        'en' => "Peringatan: Dokumen SIPA Anda akan kedaluwarsa pada " . $tanggal,
                        'id' => "Peringatan: Dokumen SIPA Anda akan kedaluwarsa pada " . $tanggal
                    ],
                    'headings' => [
                        'en' => "⚠️ SIPA Hampir Habis",
                        'id' => "⚠️ SIPA Hampir Habis"
                    ]
                ]);
            }
        }

        $this->info('Pengecekan dan pengiriman notifikasi SIPA berhasil dijalankan.');
    }
}