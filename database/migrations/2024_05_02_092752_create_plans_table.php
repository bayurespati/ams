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
            $table->id();
            $table->string('uuid');
            $table->integer('project_id');
            $table->string('judul');
            $table->string('jenis_barang_id', 50);
            $table->string('tipe_barang_id', 50);
            $table->string('nama_barang', 100);
            $table->integer('jumlah_barang');
            $table->boolean('is_lop');
            $table->string('file_prpo')->nullable();
            $table->string('no_prpo')->nullable();
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
