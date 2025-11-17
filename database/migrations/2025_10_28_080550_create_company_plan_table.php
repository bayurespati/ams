// database/migrations/2025_11_04_000002_create_company_plan_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_plan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('company_id');
            $table->timestamps();

            $table->foreign('plan_id')->references('id')->on('plan')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->unique(['plan_id', 'company_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_plan');
    }
};
