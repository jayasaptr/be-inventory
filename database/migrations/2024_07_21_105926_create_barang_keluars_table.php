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
        Schema::create('barang_keluars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barang_masuk');
            $table->string('penerima');
            $table->integer('jumlah');
            $table->date('tanggal_keluar');
            $table->text('keterangan');
            $table->unsignedBigInteger('id_kondisi');
            $table->timestamps();

            $table->foreign('id_barang_masuk')->references('id')->on('barang_masuks')->onDelete('cascade');

            $table->foreign('id_kondisi')->references('id')->on('kondisis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluars');
    }
};
