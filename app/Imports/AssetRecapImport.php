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
        $item->id_asset = $row[0];
        $item->asset_description = $row[1];
        $item->capitalized_on = $this->fix_date($row[2]);
        $item->acquisition_value = (int) preg_replace('/[^0-9]/', '', $row[3]);
        $item->acquisition_depreciation = (int) preg_replace('/[^0-9]/', '', $row[4]);
        $item->book_value = (int) preg_replace('/[^0-9]/', '', $row[5]);
        $item->currency = $row[6];
        $item->compnay_code = $row[7];
        $item->business_area = $row[8];
        $item->balance_sheet_item = $row[9];
        $item->balance_sheet_account_apc = $row[10];
        $item->asset_class = $row[11];
        $item->asset_class_name = $row[12];
        $item->internal_order = $row[13];
        $item->am = $row[14];
        $item->pm = $row[15];
        $item->quantity = (int) preg_replace('/[^\d]/', '', $row[16]);
        $item->base_unit_of_measure = $row[17];
        $item->useful_life = $row[18];
        $item->useful_life_in_periods = $row[19];
        $item->start_date_pbs = $this->fix_date($row[20]);
        $item->end_date_pbs = $this->fix_date($row[21]);
        $item->project_completion_status = $row[22];
        $item->location_1 = $row[23];
        $item->location_2 = $row[24];
        $item->qty_location_customer = $row[25] == "-" ? 0 : (int) str_replace('.', '', $row[25]);
        $item->qty_location_pins = $row[26] == "-" ? 0 : (int) str_replace('.', '', $row[26]);
        $item->qty_locastion_warehouse = $row[27] == "-" ? 0 : (int) str_replace('.', '', $row[27]);
        $item->verified = $row[28] == "-" ? 0 : (int) str_replace('.', '', $row[28]);
        $item->verfication_not_found = $row[29] == "-" ? 0 : (int) str_replace('.', '', $row[29]);
        $item->not_verified = $row[30] == "-" ? 0 : (int) str_replace('.', '', $row[30]);
        $item->in_use = $row[31] == "-" ? 0 : (int) str_replace('.', '', $row[31]);
        $item->unused = $row[32] == "-" ? 0 : (int) str_replace('.', '', $row[32]);
        $item->lost = $row[33] == "-" ? 0 : (int) str_replace('.', '', $row[33]);
        $item->purchased_by_user = $row[34] == "-" ? 0 : (int) str_replace('.', '', $row[34]);
        $item->aktif = $row[35] == "-" ? 0 : (int) str_replace('.', '', $row[35]);
        $item->non_aktif = $row[36] == "-" ? 0 : (int) str_replace('.', '', $row[36]);
        $item->asset_type = $row[37];
        $item->depreciation_method = $row[38];
        $item->fixed_asset = $row[39];
        $item->asset_group = $row[40];
        $item->description = $row[41];
        $item->description_2 = $row[42];
        $item->is_project = $row[43];
        $item->project_name = $row[44];
        $item->asset_group_show = $row[40];
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
