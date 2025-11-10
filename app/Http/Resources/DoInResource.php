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
            "keterangan" => $this->keterangan,
            "tanggal_masuk" => $this->tanggal_masuk,
            "penerima" => $this->penerima,
            "no_gr" => $this->no_gr,
            "file_evidence" => $this->file_evidence,
            "file_foto_terima" => $this->file_foto_terima,
            'po_id' => $this->po?->uuid,
            'po' => $this->po
        ];
    }
}
