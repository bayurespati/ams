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
        Schema::create('asset_recaps', function (Blueprint $table) {
            $table->id();
            $table->string('id_asset')->unique();
            $table->string('asset_description');
            $table->date('capitalized_on');
            $table->bigInteger('acquisition_value');
            $table->bigInteger('acquisition_depreciation');
            $table->bigInteger('book_value');
            $table->string('currency');
            $table->string('compnay_code');
            $table->string('business_area');
            $table->string('balance_sheet_item');
            $table->string('balance_sheet_account_apc');
            $table->string('asset_class');
            $table->string('asset_class_name');
            $table->string('internal_order');
            $table->string('am');
            $table->string('pm');
            $table->integer('quantity');
            $table->string('base_unit_of_measure');
            $table->integer('useful_life');
            $table->integer('useful_life_in_periods');
            $table->date('start_date_pbs')->nullable();
            $table->date('end_date_pbs')->nullable();
            $table->string('project_completion_status');
            $table->string('location_1');
            $table->string('location_2');
            $table->integer('qty_location_customer');
            $table->integer('qty_location_pins');
            $table->integer('qty_locastion_warehouse');
            $table->integer('verified')->default(0);
            $table->integer('verfication_not_found')->default(0);
            $table->integer('not_verified')->default(0);
            $table->integer('in_use')->default(0);
            $table->integer('unused')->default(0);
            $table->integer('lost')->default(0);
            $table->integer('purchased_by_user')->default(0);
            $table->integer('aktif')->default(0);
            $table->integer('non_aktif')->default(0);
            $table->string('asset_type');
            $table->string('depreciation_method');
            $table->string('fixed_asset');
            $table->string('asset_group');
            $table->text('description')->nullable();
            $table->text('description_2')->nullable();
            $table->string('is_project');
            $table->string('project_name')->nullable();
            $table->string('asset_group_show')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_recaps');
    }
};
