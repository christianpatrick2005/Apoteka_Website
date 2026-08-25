<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AkunManajerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek dulu agar tidak terjadi duplikat jika seeder dijalankan 2x
        if (!User::where('email', 'Admin@gmail.com')->exists()) {
            User::create([
                'name'     => 'Admin',
                'email'    => 'Admin@gmail.com',
                'password' => Hash::make('AdminGanteng12345'),
                'role'  => 'manajer', 
            ]);
        }
    }
}
