<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('panduan_teknis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->text('author');
            $table->text('link');
            $table->string('file_path')->nullable(); // For uploaded file (e.g., PDF)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panduan_teknis');
    }
};
