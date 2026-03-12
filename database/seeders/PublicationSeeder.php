<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Berita Siber
        DB::table('berita_siber')->insert([
            [
                'title' => 'Waspada Serangan Phishing Mengatasnamakan Pemprov DKI',
                'description' => 'Telah ditemukan kampanye phishing baru yang menargetkan ASN di lingkungan Pemprov DKI Jakarta...',
                'thumbnail' => 'phishing-alert.jpg', // Placeholder image name
                'source' => 'Jakarta CSIRT',
                'date' => Carbon::now()->subDays(2),
            ],
            [
                'title' => 'Panduan Keamanan WFH bagi Pegawai',
                'description' => 'Tips dan trik menjaga keamanan jaringan saat bekerja dari rumah (Work From Home).',
                'thumbnail' => 'wfh-security.jpg',
                'source' => 'BSSN',
                'date' => Carbon::now()->subDays(5),
            ]
        ]);

        // Seed Peraturan Kebijakan
        DB::table('peraturan_kebijakan')->insert([
            [
                'title' => 'Pergub DKI Jakarta No. XX Tahun 2024 tentang Keamanan Informasi',
                'description' => 'Regulasi standar keamanan informasi di lingkungan Pemprov DKI Jakarta.',
                'link' => 'https://jdih.jakarta.go.id/placeholder.pdf',
                'date' => Carbon::now()->subMonths(1),
                'time' => '10:00:00',
                'downloadAmount' => 145,
            ]
        ]);
    }
}