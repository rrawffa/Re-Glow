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
            // Ubah tipe kolom user_id dari BIGINT UNSIGNED menjadi INT untuk match pengguna.id_user
            $table->integer('user_id')->change();
        });
        
        // Kemudian add foreign key
        Schema::table('point_transactions', function (Blueprint $table) {
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
            $table->dropForeign(['user_id']);
        });
        
        Schema::table('point_transactions', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->change();
        });
    }
};
