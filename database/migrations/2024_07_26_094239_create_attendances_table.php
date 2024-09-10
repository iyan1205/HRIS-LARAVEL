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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->string('nik')->nullable(); 
            $table->string('departemen')->nullable(); 
            $table->string('jenis_kelamin')->nullable(); 
            $table->string('jabatan')->nullable(); 
            $table->string('unit')->nullable(); 
            $table->enum('status',[
                'Hadir','Izin','Sakit','Pulang']);
            $table->date('tanggal');
            $table->time('jam')->nullable();
            $table->string('lokasi')->nullable(); 
            $table->string('foto')->nullable();
            $table->enum('status',['check_in','check_out'])->default('check_in');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
