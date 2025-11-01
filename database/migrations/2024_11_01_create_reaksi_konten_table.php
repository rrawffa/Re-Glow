<?php
// database/migrations/2024_11_01_create_reaksi_konten_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reaksikonten', function (Blueprint $table) {
            $table->id('id_reaksi');
            $table->integer('id_konten');
            $table->integer('id_user')->nullable();
            $table->enum('tipe_reaksi', ['suka', 'membantu', 'menarik', 'inspiratif']);
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('id_konten')->references('id_konten')->on('kontenedukasi')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('pengguna')->onDelete('set null');
        });

        Schema::create('statistikedukasi', function (Blueprint $table) {
            $table->id('id_statistik');
            $table->integer('id_konten');
            $table->integer('total_view')->default(0);
            $table->integer('total_suka')->default(0);
            $table->integer('total_membantu')->default(0);
            $table->integer('total_menarik')->default(0);
            $table->integer('total_inspiratif')->default(0);
            $table->timestamp('last_updated')->nullable();
            
            $table->foreign('id_konten')->references('id_konten')->on('kontenedukasi')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reaksikonten');
        Schema::dropIfExists('statistikedukasi');
    }
};