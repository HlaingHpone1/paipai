<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class OrganizationRequest extends FormRequest
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
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            'address' => 'required|string',
            'phone' => 'required|string|unique:users,phone|max:15',
            'secondary_phone' => 'nullable|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        Response::validationError($this, $validator);
    }
}
