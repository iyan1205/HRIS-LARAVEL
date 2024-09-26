<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sppds', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->enum('kategori_dinas',[
                'DOMESTIK DALAM KOTA',
                'DOMESTIK LUAR KOTA (MENGINAP)',
                'DOMESTIK LUAR KOTA (TIDAK MENGINAP)',
                'LUAR NEGERI']);
            $table->string('fasilitas_kendaraan');
            $table->string('fasilitas_transportasi');
            $table->string('fasilitas_akomodasi');
            $table->enum('status',[
                'pending','rejected','approved'])->default('pending');  
            $table->integer('biaya_transfortasi');
            $table->integer('biaya_akomodasi');
            $table->integer('biaya_pendaftaran');
            $table->integer('biaya_uangsaku');
            $table->string('lokasi_tujuan');
            $table->string('rencana_kegiatan');
            $table->datetime('tanggal_berangkat');
            $table->datetime('tanggal_kembali');
            $table->integer('level_approve');
            $table->string('alasan_reject');
            $table->bigInteger('approver_id')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sppds');
    }
};
