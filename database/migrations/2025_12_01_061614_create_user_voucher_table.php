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
    Schema::create('user_voucher', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('id_user');
        $table->unsignedBigInteger('voucher_id');
        $table->string('status')->default('Menunggu'); // atau 'Redeemed'
        $table->timestamps();

        // Foreign Key
        $table->foreign('id_user')
            ->references('id_user')->on('pengguna')
            ->onDelete('cascade');

        $table->foreign('voucher_id')
            ->references('id')->on('vouchers')
            ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_voucher');
    }
};