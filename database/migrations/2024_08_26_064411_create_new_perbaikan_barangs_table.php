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
        Schema::create('new_perbaikan_barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_asset_barang');
            $table->date('tanggal_perbaikan');
            $table->date('tanggal_selesai');
            $table->string('biaya');
            $table->text('keterangan');
            $table->string('status');
            $table->string('kwitansi');
            $table->integer('jumlah');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_asset_barang')->references('id')->on('asset_barang_models');
            $table->foreign('id_user')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_perbaikan_barangs');
    }
};
