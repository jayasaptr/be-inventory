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
        Schema::create('maintenance_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('assets');
            $table->date('maintenance_date');
            $table->text('description');
            $table->bigInteger('cost');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_assets');
    }
};
