<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('peraturan_kebijakan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->text('description');
            $table->text('link');
            $table->date('date');
            $table->time('time');
            $table->integer('downloadAmount');
            $table->string('file_path')->nullable(); // For uploaded document (e.g., PDF)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peraturan_kebijakan');
    }
};
