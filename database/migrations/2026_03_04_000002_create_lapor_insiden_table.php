<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lapor_insiden', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('fullName');
            $table->text('email');
            $table->integer('phoneNumber');
            $table->date('foundDate');
            $table->text('domain');
            $table->text('url');
            $table->text('laporDesc');
            $table->text('riskType');
            $table->text('riskLevel');
            $table->float('cvssScore');
            $table->text('videoUrl');
            $table->text('reference')->nullable();
            $table->text('recommendation');
            $table->text('proofPic');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lapor_insiden');
    }
};
