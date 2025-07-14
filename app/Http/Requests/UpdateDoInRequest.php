<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoInRequest extends FormRequest
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
    public function rules($model): array
    {
        return [
            'po_id' => 'required',
            'no_do' => 'required|unique:do_in,no_do,' . $model->id,
            'lokasi_gudang' => 'required',
            'owner_id' => 'required',
            'tanggal_masuk' => 'required',
            'no_gr' => 'required',
        ];
    }
}
