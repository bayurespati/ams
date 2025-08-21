<?php

namespace App\Imports;

use App\Models\AssetRecap;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Str;

class AssetRecapImport implements ToModel, WithStartRow
{

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 3;
    }

    /**
     * Model 
     */
    public function model(array $row)
    {
        dd($row);
        $item = new AssetRecap();
        $item->id_asset = $row[0];
        $item->asset_description = $row[0];
        $item->capitalized_on = $row[0];
        $item->acquisition_value = $row[0];
        $item->acquisition_depreciation = $row[0];
        $item->currency = $row[0];
        $item->compnay_code = $row[0];
        $item->business_area = $row[0];
        $item->balance_sheet_item = $row[0];
        $item->balance_sheet_account_apc = $row[0];
        $item->asset_class = $row[0];
        $item->asset_class_name = $row[0];
        $item->internal_order = $row[0];
        $item->quantity = $row[0];
        $item->base_unit_of_measure = $row[0];
        $item->useful_life = $row[0];
        $item->useful_life_in_periods = $row[0];
        $item->start_date_pbs = $row[0];
        $item->end_date_pbs = $row[0];
        $item->location_1 = $row[0];
        $item->location_2 = $row[0];
        $item->qty_location_customer = $row[0];
        $item->qty_location_pins = $row[0];
        $item->qty_locastion_warehouse = $row[0];
        $item->verfication_not_found = $row[0];
        $item->asset_group_id = $row[0];
        $item->description = $row[0];
        $item->is_project = $row[0];
        $item->project_name = $row[0];
        $item->save();
    }
}
