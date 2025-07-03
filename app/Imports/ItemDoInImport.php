<?php

namespace App\Imports;

use App\Models\ItemDoIn;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ItemDoInImport implements ToModel, WithStartRow
{
    private $do_in_id;

    public function __construct($do_in_id)
    {
        $this->do_in_id = $do_in_id;
    }

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
        $item = new ItemDoIn();
        $item->do_in_id = $this->do_in_id;
        $item->sn = $row[0];
        $item->jumlah = $row[1];
        $item->save();
    }
}
