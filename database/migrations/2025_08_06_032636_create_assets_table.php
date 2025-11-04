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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('nama_barang');
            $table->string('sn')->nullable();
            $table->integer('jumlah');
            $table->string('owner_id');
            $table->bigInteger('do_in_id');
            $table->string('barcode')->nullable();
            $table->string('condition')->nullable();
            $table->string('current_location')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
