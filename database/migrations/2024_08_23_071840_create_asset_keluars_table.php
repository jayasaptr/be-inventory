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
        Schema::create('asset_keluars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_asset');
            $table->string('penerima');
            $table->integer('jumlah');
            $table->date('tanggal_keluar');
            $table->text('keterangan');
            $table->string('kondisi');
            $table->timestamps();

            $table->foreign('id_asset')->references('id')->on('assets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_keluars');
    }
};
