<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ================================================================
// REPLACES: 2026_03_04_000003_create_event_table.php
// Run with: php artisan migrate:fresh --seed
// ================================================================

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->text('description')->nullable();
            $table->text('thumbnail')->nullable();        // flyer image URL or path
            $table->dateTime('event_date')->nullable();   // actual event date/time
            $table->string('location')->nullable();       // venue or "Online via Zoom"
            $table->string('event_type')->nullable();     // e.g. "webinar", "sosialisasi"
            $table->string('registration_url')->nullable(); // link to register
            $table->integer('capacity')->nullable();      // max participants
            // No timestamps — keep consistent with other tables
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};