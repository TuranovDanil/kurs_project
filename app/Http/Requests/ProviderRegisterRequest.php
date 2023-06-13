<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderRegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'login' => 'required|string|max:255|unique:users',
            'password' => 'required|string',
            'name' => 'required|alpha|max:255',
            'surname' => 'required|alpha|max:255',
            'telephone' => 'required|numeric|digits_between:11,11'
        ];
    }
}
