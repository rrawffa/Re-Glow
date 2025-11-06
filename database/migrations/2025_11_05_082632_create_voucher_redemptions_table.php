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
        Schema::create('voucher_redemptions', function (Blueprint $table) {
            $table->id();

            // Relasi ke user yang menukarkan voucher
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Relasi ke voucher yang ditukarkan
            $table->foreignId('voucher_id')
                ->constrained()
                ->cascadeOnDelete();

            // Jumlah poin yang digunakan (optional)
            $table->integer('points_used')->nullable();

            // Status penukaran (misal: pending, success, failed)
            $table->string('status')->default('success');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_redemptions');
    }
};
