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
        Schema::create('po', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('plan_id');
            $table->string('nama_pekerjaan');
            $table->string('no_po_spk_pks')->nullable();
            $table->date('tanggal_po_spk_pks')->nullable();
            $table->string('file_po_spk_pks')->nullable();
            $table->string('file_boq')->nullable();
            $table->bigInteger('nilai_pengadaan');
            $table->date('tanggal_delivery');
            $table->string('akun');
            $table->string('cost_center');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po');
    }
};
