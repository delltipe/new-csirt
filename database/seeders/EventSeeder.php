<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// ================================================================
// Seeds events from the real JakartaProv-CSIRT website
// (database/seeders/legacy/events.json). Thumbnails are hotlinked
// to the legacy site; titles, dates and descriptions were sourced
// from the live flyers at csirt.jakarta.go.id/event.
// ================================================================

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $rows = json_decode(file_get_contents(__DIR__ . '/legacy/events.json'), true);

        $now = now();
        $data = array_map(function ($row) use ($now) {
            return [
                'title'            => $row['title'],
                'description'      => $row['description'] ?? null,
                'thumbnail'        => $row['thumbnail'],
                'event_date'       => isset($row['event_date']) ? Carbon::parse($row['event_date']) : null,
                'location'         => $row['location'] ?? null,
                'event_type'       => $row['event_type'] ?? null,
                'registration_url' => $row['registration_url'] ?? null,
                'capacity'         => $row['capacity'] ?? null,
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        }, $rows);

        DB::table('events')->insert($data);
    }
}