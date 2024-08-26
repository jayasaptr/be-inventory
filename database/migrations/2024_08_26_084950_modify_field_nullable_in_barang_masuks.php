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
        Schema::table('barang_masuks', function (Blueprint $table) {
            $table->string('nama')->nullable()->change();
            $table->string('merk')->nullable()->change();
            $table->unsignedBigInteger('id_category')->nullable()->change();
            $table->integer('jumlah')->nullable()->change();
            $table->string('satuan')->nullable()->change();
            $table->bigInteger('harga')->nullable()->change();
            $table->string('keterangan')->nullable()->change();
            $table->unsignedBigInteger('id_kondisi')->nullable()->change();
            $table->date('tanggal_masuk')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_masuks', function (Blueprint $table) {
            //
        });
    }
};
