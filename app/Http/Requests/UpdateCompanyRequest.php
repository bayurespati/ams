<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'name' => 'required|unique:companies,name,' . $model->id,
            'phone' => 'required|unique:companies,phone,' . $model->id,
            'email' => 'required|unique:companies,email,' . $model->id,
            'address' => 'required',
        ];
    }
}
