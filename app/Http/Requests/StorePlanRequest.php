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
            'items' => 'required|array|min:1', // Pastikan ada setidaknya satu item
            'items.*.tipe_barang_id' => 'required|string|exists:item_type,uuid',  // tipe_barang_id pada setiap item wajib diisi, string, dan harus ada di tabel item_type berdasarkan uuid
            'items.*.jenis_barang_id' => 'required|string|exists:item_variety,uuid', // jenis_barang_id pada setiap item wajib diisi, string, dan harus ada di tabel item_variety berdasarkan uuid
            'items.*.nama_barang' => 'required|string', // nama_barang pada setiap item wajib diisi dan harus berupa string
            'items.*.jumlah_barang' => 'required|integer', // jumlah_barang pada setiap item wajib diisi dan harus berupa integer
            'no_prpo' => 'nullable',
            'company_ids' => 'required|array|min:1',
            'company_ids.*' => 'required|string|exists:companies,uuid',
            'file_prpo' => 'nullable|file',
        ];
    }
}