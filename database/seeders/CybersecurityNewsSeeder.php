<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CybersecurityNewsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('berita_siber')->insert([
            [
                'title' => 'Waspada "Doxware", Varian Baru Ransomware yang Mengancam Privasi',
                'description' => 'Doxware adalah jenis perangkat perusak (malware) yang mengenkripsi data pengguna dan mengancam akan membocorkan informasi sensitif tersebut ke publik jika tebusan tidak dibayar. Berbeda dengan ransomware tradisional yang hanya mengunci akses, doxware memanipulasi rasa takut korban akan rusaknya reputasi atau pelanggaran privasi. Pastikan Anda selalu melakukan backup data secara berkala dan tidak mengunduh lampiran email dari sumber yang tidak dikenal.',
                'thumbnail' => 'https://picsum.photos/seed/cyber1/800/400',
                'source' => 'https://csirt.jakarta.go.id',
                'date' => Carbon::now()->subDays(2),
            ],
            [
                'title' => 'Google Chrome Kini Bisa Mengubah Password Anda Secara Otomatis',
                'description' => 'Pernahkah Anda mendapatkan notifikasi menakutkan bahwa "Password Anda telah bocor dalam pelanggaran data"? Google kini menghadirkan fitur baru di Chrome yang memungkinkan browser untuk mengubah kata sandi Anda secara otomatis di situs-situs yang didukung jika terdeteksi adanya kebocoran data. Ini adalah langkah besar dalam mengamankan kredensial pengguna internet.',
                'thumbnail' => 'https://picsum.photos/seed/cyber2/800/400',
                'source' => 'https://csirt.jakarta.go.id',
                'date' => Carbon::now()->subDays(5),
            ],
            [
                'title' => 'Penipuan Berkedok Video Demo Palsu Mengincar Data Pengguna Android',
                'description' => 'Penipuan dengan modus video demo palsu kembali merebak dan menjadi ancaman nyata. Kejahatan siber ini memanfaatkan isu demonstrasi besar-besaran dengan mengirimkan tautan file APK (aplikasi Android) melalui WhatsApp. Jika diunduh, aplikasi tersebut dapat mencuri data kredensial mobile banking dan OTP dari SMS korban. Masyarakat dihimbau untuk tidak sembarangan mengklik tautan.',
                'thumbnail' => 'https://picsum.photos/seed/cyber3/800/400',
                'source' => 'https://csirt.jakarta.go.id',
                'date' => Carbon::now()->subDays(10),
            ],
        ]);
    }
}