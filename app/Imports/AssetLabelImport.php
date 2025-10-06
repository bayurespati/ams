<?php

namespace App\Imports;

use App\Models\AssetLabel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Str;

class AssetLabelImport implements ToModel, WithStartRow
{
    private $asset_recap;

    public function __construct($asset_recap)
    {
        $this->asset_recap = $asset_recap;
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        try {
            if ($row[1]) {
                $asset_label = new AssetLabel();
                $asset_label->uuid = Str::uuid();
                $asset_label->id_asset = $this->asset_recap->id_asset;
                $asset_label->internal_order = $this->asset_recap->internal_order;
                $asset_label->sn = $row[9];
                $asset_label->lease_type = strtolower($row[1]);
                $asset_label->location_type = strtolower($row[2]);
                $asset_label->location_type_operation = strtolower($row[3]);
                $asset_label->address = strtolower($row[4]);
                $asset_label->location_detail = strtolower($row[5]);
                $asset_label->owner = strtolower($row[6]);
                $asset_label->condition = strtolower($row[7]);
                $asset_label->is_active = strtolower($row[8]) === 'aktif' ? true : false;
                $asset_label->description = $row[10];
                $asset_label->description_label = $row[11];
                $asset_label->save();
            }
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getTraceAsString());
        }
    }
}
