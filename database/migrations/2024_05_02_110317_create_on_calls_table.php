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
        Schema::create('on_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('interval');
            $table->enum('status', ['pending', 'rejected' ,'approved'])->default('pending');
            $table->text('keterangan');
            $table->string('alasan_reject', 500);
            $table->string('approver_id', 255);
            $tabvle->integer('level_approve');
            $table->string('updated_by', 255);
            $table->string('updated_by_atasan', 255)->nullable();
            $table->timestamps('updated_at_atasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('on_calls');
    }
};
