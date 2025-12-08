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
        Schema::create('rak', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode_rak')->unique();
            $table->string('nama_rak');
            $table->string('baris_rak');
            $table->string('kolom_rak');
            $table->string('lokasi_rak');
            $table->integer('kapasitas_rak');
            $table->enum('status_rak', ['active', 'inactive', 'maintenance'])->default('active');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rak');
    }
};
