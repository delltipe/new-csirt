<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarningPostSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('peringatan_keamanan')->insert([
            [
                'title' => 'Peringatan: Serangan Phishing Target ASN DKI',
                'description' => 'Ditemukan kampanye phishing aktif yang menargetkan kredensial email resmi pemerintah. Mohon tidak mengklik tautan dari pengirim tidak dikenal.',
                'thumbnail' => 'https://picsum.photos/seed/warn1/800/400',
                'source' => 'https://csirt.jakarta.go.id',
                'date' => now(),
            ],
            [
                'title' => 'Update Keamanan Kritis: Windows Kerberos',
                'description' => 'Microsoft merilis patch untuk kerentanan kritis pada protokol Kerberos. Segera lakukan update pada server Windows Anda.',
                'thumbnail' => 'https://picsum.photos/seed/warn2/800/400',
                'source' => 'https://csirt.jakarta.go.id',
                'date' => now()->subDays(2),
            ]
        ]);
    }
}