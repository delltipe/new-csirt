<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// ================================================================
// Run with: php artisan db:seed --class=EventSeeder
// Or:       php artisan migrate:fresh --seed  (if registered in DatabaseSeeder)
//
// Dates sourced directly from the flyer text visible in the
// real JakartaProv-CSIRT website HTML (csirt.jakarta.go.id/event).
// Thumbnails use picsum placeholders — replace with real image
// paths once you have them (e.g. storage/events/google-dorking.jpg)
// ================================================================

class EventSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('event')->insert([

            // 1. Google Dorking — Kamis, 21 November 2024
            [
                'title'            => 'Sosialisasi Security Awareness: "Google Dorking"',
                'description'      => 'Sosialisasi Security Awareness bertema Google Dorking bersama narasumber Gilang Dwi Setyawan, Penetration Tester. Kegiatan ini membahas teknik Google Dorking dan potensi ancamannya terhadap keamanan informasi instansi pemerintah. Diselenggarakan secara online melalui platform Zoom Meeting.',
                'thumbnail'        => 'https://picsum.photos/seed/event-google-dorking/800/500',
                'event_date'       => Carbon::create(2024, 11, 21, 9, 0, 0),
                'location'         => 'Online via Zoom Meeting',
                'event_type'       => 'webinar',
                'registration_url' => null,
                'capacity'         => null,
            ],

            // 2. Pengelolaan Insiden Siber — Jumat, 28 Juni 2024
            [
                'title'            => 'Security Awareness: Pengelolaan Insiden Siber',
                'description'      => 'Webinar Security Awareness dengan tema Pengelolaan Insiden Siber. Narasumber: Dwi Kardonii, S.SOS., M.A. — Sandiman Ahli Madya, Badan Siber dan Sandi Negara (BSSN). Kegiatan berlangsung pada pukul 09.00 – 11.00 WIB melalui Zoom Meeting, terbuka untuk ASN di lingkungan Pemprov DKI Jakarta.',
                'thumbnail'        => 'https://picsum.photos/seed/event-insiden-siber/800/500',
                'event_date'       => Carbon::create(2024, 6, 28, 9, 0, 0),
                'location'         => 'Online via Zoom Meeting',
                'event_type'       => 'webinar',
                'registration_url' => null,
                'capacity'         => null,
            ],

            // 3. Sosialisasi Security Awareness (Penetration Testing & Hardening) — Juli 2024
            [
                'title'            => 'Sosialisasi Security Awareness: Peningkatan Keamanan Sistem Elektronik dengan Penetration Testing dan Hardening',
                'description'      => 'Webinar Security Awareness membahas teknik peningkatan keamanan sistem elektronik melalui Penetration Testing dan Hardening. Narasumber: Della Aneva Pristanti, S.TrKom — IT Security Assessment (TSA), Badan Siber dan Sandi Negara. Pukul 09.00 – 11.00 WIB melalui Zoom Meeting.',
                'thumbnail'        => 'https://picsum.photos/seed/event-pentest/800/500',
                'event_date'       => Carbon::create(2024, 7, 26, 9, 0, 0),
                'location'         => 'Online via Zoom Meeting',
                'event_type'       => 'webinar',
                'registration_url' => null,
                'capacity'         => null,
            ],

            // 4. Ransomware — Selasa, 06 Juni 2023
            [
                'title'            => 'Sosialisasi Security Awareness: Pencegahan dan Penanganan Serangan Ransomware',
                'description'      => 'Sosialisasi Security Awareness bertema pencegahan dan penanganan serangan ransomware. Narasumber: R. Budi Rahardjo Msc., PhD — Security Expert. Diselenggarakan pada Selasa, 6 Juni 2023 pukul 09.00 WIB melalui Zoom Meeting. Meeting ID dan password terlampir pada undangan resmi.',
                'thumbnail'        => 'https://picsum.photos/seed/event-ransomware/800/500',
                'event_date'       => Carbon::create(2023, 6, 6, 9, 0, 0),
                'location'         => 'Online via Zoom Meeting',
                'event_type'       => 'sosialisasi',
                'registration_url' => null,
                'capacity'         => null,
            ],

            // 5. Mobile Computing — Senin, 17 April 2023
            [
                'title'            => 'Sosialisasi Security Awareness: Keamanan Mobile Computing dan Teleworking',
                'description'      => 'Sosialisasi Security Awareness membahas keamanan dalam penggunaan perangkat mobile dan pola kerja teleworking. Narasumber: Serni Yulianto, S.E., M.Kom., SME-vCSO — Security Consultant. Senin, 17 April 2023, pukul 08.00 WIB melalui Zoom Meeting.',
                'thumbnail'        => 'https://picsum.photos/seed/event-mobile-computing/800/500',
                'event_date'       => Carbon::create(2023, 4, 17, 8, 0, 0),
                'location'         => 'Online via Zoom Meeting',
                'event_type'       => 'sosialisasi',
                'registration_url' => null,
                'capacity'         => null,
            ],

            // 6. Indeks KAMI — Kamis, 13 April 2023
            [
                'title'            => 'Edukasi Pemanfaatan Indeks Keamanan Informasi (Indeks KAMI)',
                'description'      => 'Edukasi tentang pemanfaatan Indeks Keamanan Informasi (Indeks KAMI) sebagai instrumen evaluasi kesiapan pengamanan informasi di instansi pemerintah. Narasumber: Melita Irnopuri, S.ST., M.M. Kamis, 13 April 2023, pukul 08.00 WIB melalui Zoom Meeting.',
                'thumbnail'        => 'https://picsum.photos/seed/event-indeks-kami/800/500',
                'event_date'       => Carbon::create(2023, 4, 13, 8, 0, 0),
                'location'         => 'Online via Zoom Meeting',
                'event_type'       => 'sosialisasi',
                'registration_url' => null,
                'capacity'         => null,
            ],

        ]);
    }
}