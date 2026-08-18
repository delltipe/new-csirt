<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lampiran_insiden', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('lapor_insiden')->cascadeOnDelete();
            $table->string('jenis'); // file | url
            $table->text('value');   // file path or URL
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lampiran_insiden');
    }
};
