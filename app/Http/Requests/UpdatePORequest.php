<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePORequest extends FormRequest
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
            'plan_id' => 'required',
            'nama_pekerjaan' => 'required|unique:po,nama_pekerjaan,' . $model->id,
            'nilai_pengadaan' => 'required',
            'tanggal_delivery' => 'required',
            'akun' => 'required',
            'cost_center' => 'required',
        ];
    }
}
