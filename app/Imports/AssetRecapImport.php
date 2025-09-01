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
        // dd();
        $item = new AssetRecap();
        $item->id_asset = $row[1];
        $item->asset_description = $row[2];
        $item->capitalized_on = $this->fix_date($row[3]);
        $item->acquisition_value = (int) preg_replace('/[^0-9]/', '', $row[4]);
        $item->acquisition_depreciation = (int) preg_replace('/[^0-9]/', '', $row[5]);
        $item->book_value = (int) preg_replace('/[^0-9]/', '', $row[6]);
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
        $item->start_date_pbs = $this->fix_date($row[19]);
        $item->end_date_pbs = $this->fix_date($row[20]);
        $item->project_completion_status = $row[21];
        $item->location_1 = $row[22];
        $item->location_2 = $row[23];
        $item->qty_location_customer = $row[24] == "-" ? 0 : (int) str_replace('.', '', $row[24]);
        $item->qty_location_pins = $row[25] == "-" ? 0 : (int) str_replace('.', '', $row[25]);
        $item->qty_locastion_warehouse = $row[26] == "-" ? 0 : (int) str_replace('.', '', $row[26]);
        $item->verified = $row[27] == "-" ? 0 : (int) str_replace('.', '', $row[27]);
        $item->verfication_not_found = $row[28] == "-" ? 0 : (int) str_replace('.', '', $row[28]);
        $item->not_verified = $row[29] == "-" ? 0 : (int) str_replace('.', '', $row[29]);
        $item->in_use = $row[30] == "-" ? 0 : (int) str_replace('.', '', $row[30]);
        $item->unused = $row[31] == "-" ? 0 : (int) str_replace('.', '', $row[31]);
        $item->lost = $row[32] == "-" ? 0 : (int) str_replace('.', '', $row[32]);
        $item->purchased_by_user = $row[33] == "-" ? 0 : (int) str_replace('.', '', $row[33]);
        $item->aktif = $row[34] == "-" ? 0 : (int) str_replace('.', '', $row[34]);
        $item->non_aktif = $row[35] == "-" ? 0 : (int) str_replace('.', '', $row[35]);
        $item->asset_type = $row[36];
        $item->depreciation_method = $row[37];
        $item->fixed_asset = $row[38];
        $item->asset_group = $row[39];
        $item->description = $row[40];
        $item->is_project = $row[41];
        $item->project_name = $row[42];
        $item->asset_group_show = $row[39];
        $item->save();
    }

    public function fix_date($data)
    {
        $result = null;

        if (is_numeric($data)) {
            // Excel serial number ? Y-m-d
            $result = date('Y-m-d', ($data - 25569) * 86400);
        } else {
            // Coba beberapa format tanggal
            $formats = ['m/d/Y', 'd-M-y', 'd-M-Y', 'Y-m-d'];

            foreach ($formats as $format) {
                $date = \DateTime::createFromFormat($format, $data);
                if ($date) {
                    $result = $date->format('Y-m-d');
                    break;
                }
            }
        }

        return $result;
    }
}
