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
        Schema::create('barang_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('merk');
            $table->unsignedBigInteger('id_category');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->bigInteger('harga');
            $table->string('keterangan');
            $table->unsignedBigInteger('id_kondisi');
            $table->date('tanggal_masuk');
            $table->timestamps();

            $table->foreign('id_kondisi')->references('id')->on('kondisis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuks');
    }
};
