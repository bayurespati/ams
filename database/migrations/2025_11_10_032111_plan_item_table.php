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
        Schema::create('plan_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plan')->onDelete('cascade');
            $table->bigInteger('tipe_barang_id');    
            $table->bigInteger('jenis_barang_id');   
            $table->string('nama_barang', 100);
            $table->integer('jumlah_barang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_item');
    }
};
