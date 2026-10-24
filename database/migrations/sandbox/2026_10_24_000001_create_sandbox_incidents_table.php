<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The database connection that should be used by the migration.
     */
    protected $connection = 'sandbox';

    public function up(): void
    {
        Schema::connection('sandbox')->create('sandbox_incidents', function (Blueprint $table) {
            $table->id();
            $table->string('tiket_no', 32)->unique();
            $table->string('kategori_insiden', 64);
            $table->dateTime('waktu_kejadian');
            $table->string('lokasi_url', 255);
            $table->string('down_time', 10);
            $table->text('deskripsi');
            $table->text('tindakan_teknis');
            $table->json('bukti_urls')->nullable();
            $table->string('status', 32)->default('menunggu_validasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('sandbox')->dropIfExists('sandbox_incidents');
    }
};
