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
        Schema::table('barang_masuk_models', function (Blueprint $table) {
            $table->string('status')->default('pending');
            $table->date('tanggal_masuk')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_masuk_models', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('tanggal_masuk');
        });
    }
};
