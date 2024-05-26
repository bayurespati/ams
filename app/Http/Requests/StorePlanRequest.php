<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlanRequest extends FormRequest
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
            'nama_barang' => 'required|unique:plan,nama_barang',
            'jenis_barang_id' => 'required',
            'tipe_barang_id' => 'required',
            'jumlah_barang' => 'required',
            'no_spk' => 'required',
            'no_prpo' => 'required',
            'is_lop' => 'required',
        ];
    }
}
