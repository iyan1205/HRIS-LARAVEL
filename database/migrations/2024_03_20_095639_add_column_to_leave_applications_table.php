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
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->foreignId('leave_type_id')->constrained();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('total_days', 30)->nullable();
            $table->string('alasan_reject', 255)->nullable();
            $table->string('updated_by', 30)->nullable();
            $table->bigInteger('manager_id')->nullable();
            $table->integer('level_approve')->nullable(); // Removed the length parameter
            $table->string('file_upload', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_applications', function (Blueprint $table) {
            $table->dropForeign(['leave_type_id']);
            $table->dropColumn('leave_type_id');
            $table->dropColumn('status');
            $table->dropColumn('total_days');
            $table->dropColumn('alasan_reject');
            $table->dropColumn('updated_by');
            $table->dropColumn('manager_id');
            $table->dropColumn('level_approve');
            $table->dropColumn('file_upload');
        });
    }
};

