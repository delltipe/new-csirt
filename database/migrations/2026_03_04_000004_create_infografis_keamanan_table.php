<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('infografis_keamanan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->text('thumbnail');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infografis_keamanan');
    }
};
