<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->text('description')->nullable();
            $table->text('thumbnail')->nullable();        // flyer image URL or path
            $table->dateTime('event_date')->nullable();   // actual event date/time
            $table->string('location')->nullable();       // venue or "Online via Zoom"
            $table->string('event_type')->nullable();     // e.g. "webinar", "sosialisasi"
            $table->string('registration_url')->nullable(); // link to register
            $table->integer('capacity')->nullable();      // max participants
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};