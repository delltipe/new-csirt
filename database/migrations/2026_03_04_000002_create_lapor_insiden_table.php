<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lapor_insiden', function (Blueprint $table) {
            $table->bigIncrements('id');
            // Data Pelapor
            $table->string('fullName');
            $table->string('email');
            $table->string('phoneNumber'); // CHANGED to string
            
            // Data Website & Insiden
            $table->date('foundDate')->nullable();
            $table->string('domain');
            $table->text('url');
            $table->text('laporDesc');
            
            // Risk & Scoring
            $table->string('riskType')->nullable();
            $table->string('riskLevel')->nullable();
            $table->float('cvssScore')->nullable();
            
            // Evidences
            $table->text('videoUrl')->nullable();
            $table->text('reference')->nullable();
            $table->text('recommendation')->nullable();
            $table->string('proofPic')->nullable(); // Storing the file path
            
            // CSIRT Internal Status
            $table->string('status')->default('Menunggu Validasi'); // ADDED for tracking
            $table->timestamps(); // ADDED: Critical for knowing WHEN the report was submitted
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lapor_insiden');
    }
};
