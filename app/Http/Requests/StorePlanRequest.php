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
            'items.*.brand_id' => 'required|string|exists:brands,uuid',
            'items.*.tipe_barang_id' => 'required|string|exists:item_type,uuid',
            'items.*.jenis_barang_id' => 'required|string|exists:item_variety,uuid',
            'items.*.nama_barang' => 'required|string',
            'items.*.jumlah_barang' => 'required|integer',
            'no_prpo' => 'nullable',
            'file_prpo' => 'nullable|file',
        ];
    }
}