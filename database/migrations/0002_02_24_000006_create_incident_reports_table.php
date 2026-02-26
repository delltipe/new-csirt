<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->string('reporter_name');
            $table->string('reporter_email');
            $table->string('reporter_phone')->nullable();
            $table->string('organization')->nullable();
            $table->string('incident_type'); // malware, phishing, breach, ddos, etc.
            $table->text('description');
            $table->dateTime('incident_date')->nullable();
            $table->string('affected_systems')->nullable();
            $table->text('actions_taken')->nullable();
            $table->string('status')->default('new'); // new, acknowledged, investigating, resolved
            $table->string('severity')->nullable(); // critical, high, medium, low
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};
