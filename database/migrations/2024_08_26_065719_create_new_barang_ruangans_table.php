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
        Schema::create('new_barang_ruangans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_asset_barang');
            $table->unsignedBigInteger('id_ruangan');
            $table->integer('jumlah');
            $table->string('keterangan');
            $table->unsignedBigInteger('id_user');
            $table->date('tanggal');
            $table->enum('status', ['diproses', 'disetuji']);
            $table->timestamps();

            $table->foreign('id_asset_barang')->references('id')->on('asset_barang_models');
            $table->foreign('id_ruangan')->references('id')->on('ruangans');
            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_barang_ruangans');
    }
};
