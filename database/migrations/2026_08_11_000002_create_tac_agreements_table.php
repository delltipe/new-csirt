<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tac_agreements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('version');
            $table->timestamp('agreed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tac_agreements');
    }
};
