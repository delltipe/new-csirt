<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infographics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_url');
            $table->string('category'); // cyber-threats, security-tips, statistics, etc.
            $table->string('tags')->nullable(); // JSON or comma-separated
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infographics');
    }
};
