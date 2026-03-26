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
        Schema::table('karyawans', function (Blueprint $table) {
            $table->foreignId('departemen_id')->constrained();
            $table->foreignId('jabatan_id')->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropForeign(['departemen_id']);
            $table->dropForeign(['jabatan_id']);
            $table->dropForeign(['unit_id']);
            $table->dropColumn('deleted_at');
        });
    }
};
