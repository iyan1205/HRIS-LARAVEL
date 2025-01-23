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
        Schema::create('karyawan_pelatihan', function (Blueprint $table) {
            $table->unsignedBigInteger('karyawan_id');
            $table->unsignedBigInteger('pelatihan_id');
            $table->foreign('karyawan_id')->references('id')->on('karyawans')->onDelete('cascade');
            $table->foreign('pelatihan_id')->references('id')->on('pelatihans')->onDelete('cascade');
            $table->primary(['karyawan_id', 'pelatihan_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_pelatihan');
    }
};
