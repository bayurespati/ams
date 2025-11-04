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
            'judul' => 'required|unique:plan,judul',
            'nama_barang' => 'required',
            'is_lop' => 'required|boolean',
            'project_id' => 'required_if:is_lop,true,1|nullable',  
            'project_name' => 'required_if:is_lop,false,0',  
            'jenis_barang_id' => 'required|exists:item_variety,uuid',
            'tipe_barang_id' => 'required|exists:item_type,uuid',
            'jumlah_barang' => 'required|integer',
            'no_prpo' => 'nullable',
            'company_ids' => 'required|array|min:1',
            'company_ids.*' => 'required|string|exists:companies,uuid',
            'file_prpo' => 'nullable|file',
        ];
    }
}
