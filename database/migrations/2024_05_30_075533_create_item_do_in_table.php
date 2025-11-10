<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('item_do_in', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->bigInteger('do_in_id');
            $table->bigInteger('owner_id');
            $table->string('nama');
            $table->string('sn')->nullable()->unique();
            $table->integer('jumlah');
            $table->tinyInteger('is_verified')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_do_in');
    }
};
