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
        Schema::create('plan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('jenis_barang_id');
            $table->integer('tipe_barang_id');
            $table->string('nama_barang');
            $table->integer('jumlah_barang');
            $table->boolean('is_lop');
            $table->string('file_prpo');
            $table->string('file_spk');
            $table->string('no_prpo');
            $table->string('no_spk');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan');
    }
};
