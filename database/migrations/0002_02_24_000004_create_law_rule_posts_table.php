<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('law_rule_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->text('summary')->nullable();
            $table->string('law_number')->nullable(); // e.g., UU No. 11 Tahun 2008
            $table->string('regulation_type'); // undang-undang, peraturan, keputusan, etc.
            $table->date('effective_date')->nullable();
            $table->string('document_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('law_rule_posts');
    }
};
