<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PublicPreviewSeeder extends Seeder
{
    /**
     * Seed only the seven approved public-content tables.
     * Explicitly omits AdminUserSeeder and any user/incident tables.
     */
    public function run(): void
    {
        $this->call([
            CybersecurityNewsSeeder::class,
            WarningPostSeeder::class,
            EventSeeder::class,
            InfographicSeeder::class,
            LawRulePostSeeder::class,
            CybersecurityGuideSeeder::class,
            HeroSlideSeeder::class,
        ]);
    }
}
