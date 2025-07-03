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
        Schema::create('do_in', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->bigInteger('po_id');
            $table->string('no_do', 50)->unique();
            $table->string('lokasi_gudang');
            $table->date('tanggal_masuk');
            $table->string('no_gr');
            $table->string('owner_id', 50);
            $table->string('owner_type', 20);
            $table->string('file_evidence', 100);
            $table->text('keterangan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('do_ins');
    }
};
