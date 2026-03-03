<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_us', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('fullName');
            $table->text('email');
            $table->text('subject');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_us');
    }
};
