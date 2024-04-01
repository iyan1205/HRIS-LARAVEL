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
        Schema::create('jabatan_jabatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jabatan_id');
            $table->unsignedBigInteger('atasan_id');
            $table->timestamps();

            // Menambahkan kunci asing
            $table->foreign('jabatan_id')->references('id')->on('jabatans')->onDelete('cascade');
            $table->foreign('atasan_id')->references('id')->on('jabatans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan_jabatan');
    }
};
