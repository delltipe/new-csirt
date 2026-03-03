<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('berita_siber', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->text('description');
            $table->text('thumbnail');
            $table->text('source');
            $table->dateTime('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_siber');
    }
};
