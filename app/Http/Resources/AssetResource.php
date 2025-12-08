<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "uuid" => $this->uuid,
            "nama_barang" => $this->nama_barang,
            "sn" => $this->sn,
            "label" => $this->label,
            "jumlah" => $this->jumlah,
            "owner_id" => $this->owner?->uuid,
            "owner" => $this->owner,
            "do_in" => $this->do_in,
            "rak" => $this->rak ? $this->rak : null,
            "rak_id" => $this->rak?->uuid,
        ];
    }
}
