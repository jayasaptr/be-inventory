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
        Schema::create('perbaikans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_barang_masuk');
            $table->date('tanggal_perbaikan');
            $table->date('tanggal_selesai');
            $table->string('biaya');
            $table->text('keterangan');
            $table->enum('status', ['proses', 'disetuji']);
            $table->string('kwitansi');
            $table->integer('jumlah');
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->foreign('id_barang_masuk')->references('id')->on('barang_masuks');

            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbaikans');
    }
};
