<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRakRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_rak' => 'required|string|unique:rak,kode_rak',
            'nama_rak' => 'required|string',
            'lokasi_rak' => 'required|string',
            'kapasitas_rak' => 'required|integer|min:1',
            'status_rak' => 'required|in:active,inactive,maintenance',
            'keterangan' => 'nullable|string',
        ];
    }
}
