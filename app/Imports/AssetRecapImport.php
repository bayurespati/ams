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
        return 2;
    }

    /**
     * Model 
     */
    public function model(array $row)
    {
        $item = new AssetRecap();
        $item->id_asset = $row[1];
        $item->asset_description = $row[2];
        $item->capitalized_on = is_numeric($row[3]) ? date('Y-m-d', ($row[3] - 25569) * 86400) : $row[3];
        $item->acquisition_value = (int) preg_replace('/[^0-9]/', '', $row[4]);
        $item->acquisition_depreciation = (int) preg_replace('/[^0-9]/', '', $row[4]);
        $item->book_value = (int) str_replace('.', '', $row[6]);
        $item->currency = $row[7];
        $item->compnay_code = $row[8];
        $item->business_area = $row[9];
        $item->balance_sheet_item = $row[10];
        $item->balance_sheet_account_apc = $row[11];
        $item->asset_class = $row[12];
        $item->asset_class_name = $row[13];
        $item->internal_order = $row[14];
        $item->quantity = (int) preg_replace('/[^\d]/', '', $row[15]);
        $item->base_unit_of_measure = $row[16];
        $item->useful_life = $row[17];
        $item->useful_life_in_periods = $row[18];
        $item->start_date_pbs = date('Y-m-d', ($row[19] - 25569) * 86400);
        $item->end_date_pbs = date('Y-m-d', ($row[20] - 25569) * 86400);
        $item->location_1 = $row[21];
        $item->location_2 = $row[22];
        $item->qty_location_customer = $row[23] == "-" ? 0 : (int) str_replace('.', '', $row[23]);
        $item->qty_location_pins = $row[24] == "-" ? 0 : (int) str_replace('.', '', $row[24]);
        $item->qty_locastion_warehouse = $row[25] == "-" ? 0 : (int) str_replace('.', '', $row[25]);
        $item->verified = $row[26] == "-" ? 0 : (int) str_replace('.', '', $row[26]);
        $item->verfication_not_found = $row[27] == "-" ? 0 : (int) str_replace('.', '', $row[27]);
        $item->not_verified = $row[28] == "-" ? 0 : (int) str_replace('.', '', $row[28]);
        $item->asset_group = $row[29];
        $item->description = $row[30];
        $item->is_project = $row[31];
        $item->project_name = $row[32];
        $item->save();
    }
}
