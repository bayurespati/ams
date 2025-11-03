<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemDoInResource extends JsonResource
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
            "sn" => $this->sn,
            "jumlah" => $this->jumlah,
            "is_verified" => $this->is_verified,
            "owner_id" => $this->owner?->uuid,
            "owner" => $this->owner,
            'do_in_id' => $this->do_in?->uuid,
            'do_in' => $this->do_in,
        ];
    }
}
