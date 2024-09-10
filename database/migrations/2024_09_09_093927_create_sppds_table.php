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
            $table->enum('fasilitas_kendaraan',[
                'DINAS',
                'SEWA']);
            $table->enum('fasilitas_transportasi',[
                'PESAWAT',
                'KERATA API',
                'BUS',
                'KAPAL LAUT',
                'TRAVEL']);
            $table->enum('fasilitas_akomodasi',[
                    'HOTEL',
                    'KOST']);
            $table->enum('status',[
                'pending','rejected','approved'])->default('pending');  
            $table->string('cost');
            $table->string('kota_tujuan')->default('-');
            $table->string('negara_tujuan')->default('indonesia');
            $table->string('rencana_kegiatan');
            $table->datetime('tanggal_berangkat');
            $table->datetime('tanggal_kembali');
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
