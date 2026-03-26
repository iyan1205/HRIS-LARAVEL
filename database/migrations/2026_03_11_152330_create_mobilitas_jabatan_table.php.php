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
        Schema::create('mobilitas_jabatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained()->onDelete('cascade');
            $table->enum('aspek', ['promosi', 'demosi', 'ratasi', 'mutasi']);
            $table->string('jabatan_sekarang');
            $table->string('jabatan_baru');
            $table->string('departemen_sekarang');
            $table->string('departemen_baru');
            $table->string('unit_sekarang');
            $table->string('unit_baru');
            $table->date('tanggal_efektif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobilitas_jabatan');
    }
};
