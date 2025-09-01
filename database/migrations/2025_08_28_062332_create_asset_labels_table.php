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
            $table->uuid('uuid');
            $table->string('label')->unique();
            $table->string('sn')->unique()->nullable();
            $table->string('condition')->nullable();
            $table->string('location')->nullable();
            $table->string('description')->nullable();
            $table->string('description_label')->nullable();
            $table->boolean('status')->default(false);
            $table->string('id_asset');
            $table->string('internal_order');
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
