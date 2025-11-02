<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Transaksi Sampah Table
        Schema::create('transaksisampah', function (Blueprint $table) {
            $table->id('id_tSampah');
            $table->integer('id_user');
            $table->unsignedBigInteger('id_drop_point');
            $table->dateTime('tgl_tSampah');
            $table->string('foto_bukti', 255)->nullable();
            $table->string('status', 50)->default('Menunggu');
            $table->integer('total_poin')->default(0);
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('pengguna')->onDelete('cascade');
            $table->foreign('id_drop_point')->references('id_drop_point')->on('drop_point')->onDelete('cascade');
        });

        // Detail Sampah Table
        Schema::create('detailsampah', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_tSampah');
            $table->string('jenis_sampah', 100);
            $table->string('ukuran_sampah', 50);
            $table->integer('quantity')->default(1);
            $table->integer('poin_per_sampah')->default(0);
            $table->timestamps();

            $table->foreign('id_tSampah')->references('id_tSampah')->on('transaksisampah')->onDelete('cascade');
        });

        // Riwayat Sampah Table
        Schema::create('riwayatsampah', function (Blueprint $table) {
            $table->id('id_riwayat');
            $table->unsignedBigInteger('id_tSampah');
            $table->string('status');
            $table->dateTime('tanggal_update');
            $table->timestamps();

            $table->foreign('id_tSampah')->references('id_tSampah')->on('transaksisampah')->onDelete('cascade');
        });

        // Jadwal Logistik Table
        Schema::create('jadwallogistik', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->integer('id_user');
            $table->unsignedBigInteger('id_drop_point');
            $table->dateTime('tanggal_jemput');
            $table->string('status_jadwal');
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('pengguna')->onDelete('cascade');
            $table->foreign('id_drop_point')->references('id_drop_point')->on('drop_point')->onDelete('cascade');
        });

        // Riwayat Pengambilan Table
        Schema::create('riwayatpengambilan', function (Blueprint $table) {
            $table->id('id_riwayat_peng');
            $table->unsignedBigInteger('id_drop_point');
            $table->integer('id_user');
            $table->dateTime('tanggal_ambil');
            $table->timestamps();

            $table->foreign('id_drop_point')->references('id_drop_point')->on('drop_point')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('pengguna')->onDelete('cascade');
        });

        // Bukti Pengambilan Table
        Schema::create('buktipengambilan', function (Blueprint $table) {
            $table->id('id_bukti');
            $table->unsignedBigInteger('id_riwayat_peng');
            $table->string('foto_bukti', 255);
            $table->integer('jumlah_item');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_riwayat_peng')->references('id_riwayat_peng')->on('riwayatpengambilan')->onDelete('cascade');
        });

        // Statistik Transaksi Table
        Schema::create('statistiktransaksi', function (Blueprint $table) {
            $table->id('id_stat_transaksi');
            $table->integer('total_transaksi')->default(5000);
            $table->integer('total_poin_terdistribusi')->default(250000);
            $table->float('total_sampah_terkumpul', 10, 2)->default(12500.50);
            $table->timestamps();
        });

        // Jadwal Pengambilan Table (Activity Log for Logistics)
        Schema::create('jadwal_pengambilan', function (Blueprint $table) {
            $table->id('id_jadwal_pengambilan');
            $table->unsignedBigInteger('id_transaksi')->nullable();
            $table->integer('id_user')->nullable();
            $table->unsignedBigInteger('id_drop_point');
            $table->string('lokasi_droppoint', 255);
            $table->string('koordinat_lokasi', 100)->nullable();
            $table->string('jenis_sampah', 100);
            $table->dateTime('waktu_pengambilan');
            $table->enum('status', ['Pending', 'Dikonfirmasi', 'Selesai', 'Bermasalah'])->default('Pending');
            $table->text('catatan_pengguna')->nullable();
            $table->timestamps();

            $table->foreign('id_transaksi')->references('id_tSampah')->on('transaksisampah')->onDelete('set null');
            $table->foreign('id_user')->references('id_user')->on('pengguna')->onDelete('set null');
            $table->foreign('id_drop_point')->references('id_drop_point')->on('drop_point')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pengambilan');
        Schema::dropIfExists('statistiktransaksi');
        Schema::dropIfExists('buktipengambilan');
        Schema::dropIfExists('riwayatpengambilan');
        Schema::dropIfExists('jadwallogistik');
        Schema::dropIfExists('riwayatsampah');
        Schema::dropIfExists('detailsampah');
        Schema::dropIfExists('transaksisampah');
        Schema::dropIfExists('drop_point');
    }
};