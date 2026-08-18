<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InfographicSeeder extends Seeder
{
    public function run(): void
    {
        $rows = json_decode(file_get_contents(__DIR__ . '/legacy/infographics.json'), true);

        $now = now();
        $data = array_map(function ($row) use ($now) {
            return [
                'title'      => $row['title'],
                'thumbnail'  => $row['thumbnail'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $rows);

        DB::table('infografis_keamanan')->insert($data);
    }
}