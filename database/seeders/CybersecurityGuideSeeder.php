<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CybersecurityGuideSeeder extends Seeder
{
    public function run(): void
    {
        $rows = json_decode(file_get_contents(__DIR__ . '/legacy/guides.json'), true);

        $now = now();
        $data = array_map(function ($row) use ($now) {
            return [
                'title'      => $row['title'],
                'author'     => $row['author'],
                'link'       => $row['link'],
                'file_path'  => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $rows);

        DB::table('panduan_teknis')->insert($data);
    }
}