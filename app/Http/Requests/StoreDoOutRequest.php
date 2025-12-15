<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoOutRequest extends FormRequest
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
            'plan_id' => 'required',
            'no_do' => 'required|unique:do_outs,no_do',
            'tanggal_do' => 'required',
            'pengirim' => 'required',
            'alamat_pengirim' => 'required',
            'pic_pengirim' => 'required',
            'telpon_pengirim' => 'required',
            'penerima' => 'required',
            'alamat_penerima' => 'required',
            'pic_penerima' => 'required',
            'telpon_penerima' => 'required',
            'file_evidence' => 'required',
        ];
    }
}
