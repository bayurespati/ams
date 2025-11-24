<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRakRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules($model): array
    {
        return [
            'kode_rak' => 'required|string|unique:rak,kode_rak,' . $model->id,
            'nama_rak' => 'required|string',
            'lokasi_rak' => 'required|string',
            'kapasitas_rak' => 'required|integer|min:1',
            'status_rak' => 'required|in:active,inactive,maintenance',
            'keterangan' => 'nullable|string',
        ];
    }
}
