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
        Schema::create('pendidikans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained()->onDelete('cascade');
            $table->string('institusi')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('tahun_lulus')->nullable();
            $table->string('nomer_ijazah')->nullable();
            $table->string('nomer_str')->nullable();
            $table->string('exp_str')->nullable();
            $table->string('profesi')->nullable();
            $table->string('cert_profesi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendidikans');
    }
};
