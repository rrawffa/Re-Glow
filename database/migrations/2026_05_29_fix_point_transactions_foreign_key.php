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
        Schema::table('point_transactions', function (Blueprint $table) {
            // Drop foreign key lama
            $table->dropForeign(['user_id']);
            
            // Tambahkan foreign key yang benar ke pengguna.id_user
            $table->foreign('user_id')
                  ->references('id_user')
                  ->on('pengguna')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('point_transactions', function (Blueprint $table) {
            // Drop foreign key baru
            $table->dropForeign(['user_id']);
            
            // Restore foreign key lama
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
