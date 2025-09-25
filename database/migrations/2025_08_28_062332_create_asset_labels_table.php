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
        Schema::create('asset_labels', function (Blueprint $table) {
            $table->id();
            $table->string('label')->unique()->nullable();
            $table->string('id_asset');
            $table->string('internal_order');
            $table->string('sn')->unique()->nullable();
            $table->string('lease_type')->nullable();
            $table->string('location_type')->nullable();
            $table->string('address')->nullable();
            $table->string('location_detail')->nullable();
            $table->string('owner')->nullable();
            $table->string('condition')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('description')->nullable();
            $table->string('description_label')->nullable();
            $table->boolean('status_barcode')->default(false);
            $table->string('barcode')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_labels');
    }
};
