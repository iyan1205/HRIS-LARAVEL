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
        Schema::create('benefit_kartaps', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('jenis_benefit');
            $table->string('form_pengajuan');
            $table->string('resume');
            $table->string('bukti_pembayaran');
            $table->decimal('nominal', 15, 2)->default(0);
            $table->string('alasan_reject')->nullable();
            $table->enum('status', ['pending', 'approval_1', 'approval_2', 'approved', 'rejected'])->default('pending');
            $table->foreignUuid('approval_1_by')->nullable()->constrained('users')->references('id')->nullOnDelete();
            $table->foreignUuid('approval_2_by')->nullable()->constrained('users')->references('id')->nullOnDelete();
            $table->foreignUuid('approved_by')->nullable()->constrained('users')->references('id')->nullOnDelete();
            $table->timestamp('approval_1_at')->nullable();
            $table->timestamp('approval_2_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('benefit_kartaps');
    }
};
