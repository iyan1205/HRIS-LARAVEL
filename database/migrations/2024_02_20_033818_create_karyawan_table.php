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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('nik', 4);
            $table->enum('status_karyawan',['kontrak','kartap'])->default('kontrak');
            $table->date('tgl_kontrak1');
            $table->string('gender');
            $table->date('akhir_kontrak1');
            $table->date('tgl_kontrak2')->nullable();
            $table->date('akhir_kontrak2')->nullable();
            $table->enum('status',['active','resign'])->default('active');
            $table->string('tgl_resign')->nullable();
            $table->string('resign_id')->nullable();
            $table->string('nomer_ktp')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('alamat_ktp')->nullable();
            $table->enum('status_ktp',['menikah','belum_menikah']);
            $table->string('telepon',14)->nullable();
            $table->string('npwp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
