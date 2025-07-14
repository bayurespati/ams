<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoInResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            "uuid" => $this->uuid,
            "no_do" => $this->no_do,
            "lokasi_gudang" => $this->lokasi_gudang,
            "owner_id" => $this->owner_id,
            "owner_type" => $this->owner_type,
            "keterangan" => $this->keterangan,
            "tanggal_masuk" => $this->tanggal_masuk,
            "no_gr" => $this->no_gr,
            "file_evidence" => $this->file_evidence,
            'po_id' => $this->po?->uuid,
            'po' => $this->po
        ];
    }
}
