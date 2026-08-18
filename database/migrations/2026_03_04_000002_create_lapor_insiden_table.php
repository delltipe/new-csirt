<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lapor_insiden', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->string('tiket_no')->unique(); // INS-YYYY-XXXX
            $table->string('kategori_insiden');
            $table->dateTime('waktu_kejadian')->nullable();
            $table->text('lokasi_url');
            $table->time('down_time')->nullable();
            $table->text('deskripsi');
            $table->text('tindakan_teknis')->nullable();
            $table->string('cwe')->nullable();
            $table->string('severity')->nullable();
            $table->string('status')->default('menunggu_validasi');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lapor_insiden');
    }
};
