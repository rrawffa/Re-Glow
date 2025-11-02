<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop Points Table
        Schema::create('drop_point', function (Blueprint $table) {
            $table->id('id_drop_point');
            $table->string('nama_lokasi', 255);
            $table->string('koordinat', 100);
            $table->float('kapasitas_sampah', 10, 2)->default(500.50);
            $table->text('alamat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drop_point');
    }
};