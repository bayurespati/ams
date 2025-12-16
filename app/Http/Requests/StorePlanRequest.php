<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|unique:plan,judul',
            'is_lop' => 'required|boolean',
            'project_id' => 'required_if:is_lop,true,1|nullable',
            'project_name' => 'required_if:is_lop,false,0',
            'items' => 'required|array|min:1', 
            'items.*.company_id' => 'required|string|exists:companies,uuid',
            'items.*.tipe_barang_id' => 'required|string|exists:item_type,uuid',  
            'items.*.jenis_barang_id' => 'required|string|exists:item_variety,uuid', 
            'items.*.nama_barang' => 'required|string', 
            'items.*.jumlah_barang' => 'required|integer', 
            'items.*.brand_ids' => 'required|array|min:1',
            'items.*.brand_ids.*' => 'required|string|exists:brands,uuid',
            'no_prpo' => 'nullable',
            'company_ids' => 'required|array|min:1',
            'company_ids.*' => 'required|string|exists:companies,uuid',
            'file_prpo' => 'nullable|file',
        ];
    }
}