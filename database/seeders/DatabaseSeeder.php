<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call other seeders
        $this->call([
            AdminUserSeeder::class,
            CybersecurityNewsSeeder::class,
            WarningPostSeeder::class,
            EventSeeder::class,
            InfographicSeeder::class,
            LawRulePostSeeder::class,
            CybersecurityGuideSeeder::class,
        ]);
    }
}
