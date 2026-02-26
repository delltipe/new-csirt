<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cybersecurity_guides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('category'); // best-practices, authentication, malware-prevention, etc.
            $table->string('difficulty_level'); // beginner, intermediate, advanced
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cybersecurity_guides');
    }
};
