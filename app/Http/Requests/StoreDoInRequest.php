<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoInRequest extends FormRequest
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
            'po_id' => 'required',
            'no_do' => 'required|unique:do_in,no_do',
            'lokasi_gudang' => 'required',
            'owner_id' => 'required',
            'owner_type' => 'required',
            'tanggal_masuk' => 'required',
            'no_gr' => 'required',
            'file_evidence' => 'required'
        ];
    }
}
