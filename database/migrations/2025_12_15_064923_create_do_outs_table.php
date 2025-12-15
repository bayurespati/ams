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
        Schema::create('do_outs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->bigInteger('plan_id')->nullable();
            $table->string('no_do', 50)->unique();
            $table->date('tanggal_do');
            $table->string('pengirim');
            $table->string('alamat_pengirim');
            $table->string('pic_pengirim');
            $table->string('telpon_pengirim');
            $table->string('penerima');
            $table->string('alamat_penerima');
            $table->string('pic_penerima');
            $table->string('telpon_penerima');
            $table->string('file_evidence');
            $table->string('status', 20)->default('delivery');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('do_outs');
    }
};
