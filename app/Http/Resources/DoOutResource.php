<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoOutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "uuid" => $this->uuid,
            "no_do" => $this->no_do,
            "tanggal_do" => $this->tanggal_do,
            "pengirim" => $this->pengirim,
            "alamat_pengirim" => $this->alamat_pengirim,
            "pic_pengirim" => $this->pic_pengirim,
            "telpon_pengirim" => $this->telpon_pengirim,
            "penerima" => $this->penerima,
            "alamat_penerima" => $this->alamat_penerima,
            "pic_penerima" => $this->pic_penerima,
            "telpon_penerima" => $this->telpon_penerima,
            "file_evidence" => $this->status,
            "status" => $this->status,
            "plan_id" => $this->plan?->uuid,
            "plan" => $this->plan,
        ];
    }
}
