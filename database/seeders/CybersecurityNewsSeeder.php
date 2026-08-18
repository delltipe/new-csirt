<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CybersecurityNewsSeeder extends Seeder
{
    public function run(): void
    {
        $rows = json_decode(file_get_contents(__DIR__ . '/legacy/warnings.json'), true);

        $now = now();
        $data = array_map(function ($row) use ($now) {
            return [
                'title'       => $row['title'],
                'description' => $row['description'],
                'thumbnail'   => $row['thumbnail'],
                'source'      => $row['source'],
                'date'        => Carbon::parse($row['date']),
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }, $rows);

        DB::table('berita_siber')->insert($data);
    }
}