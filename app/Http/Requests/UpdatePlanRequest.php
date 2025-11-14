<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
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
            'judul' => 'required|unique:plan,judul,' . $model->id,
            'is_lop' => 'required|boolean',
            'project_id' => 'required_if:is_lop,true,1|nullable',
            'project_name' => 'required_if:is_lop,false,0',
            'items' => 'required|array|min:1', // Pastikan ada setidaknya satu item
            'items.*.tipe_barang_id' => 'required|string|exists:item_type,uuid', // tipe_barang_id pada setiap item wajib diisi, string, dan harus ada di tabel item_type berdasarkan uuid
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
