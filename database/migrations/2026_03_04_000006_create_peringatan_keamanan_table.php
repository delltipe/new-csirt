<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('peringatan_keamanan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->text('description');
            $table->text('thumbnail');
            $table->text('source');
            $table->dateTime('date');
            $table->string('file_path')->nullable(); // For uploaded image file
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peringatan_keamanan');
    }
};
