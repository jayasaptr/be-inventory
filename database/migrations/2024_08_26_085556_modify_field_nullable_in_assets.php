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
        Schema::table('assets', function (Blueprint $table) {
            // $table->string('name');
            // $table->text('description');
            // $table->date('purchase_date');
            // $table->bigInteger('price');
            // $table->integer('quantity');
            $table->string('name')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->date('purchase_date')->nullable()->change();
            $table->bigInteger('price')->nullable()->change();
            $table->integer('quantity')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            //
        });
    }
};
