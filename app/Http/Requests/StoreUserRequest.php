<?php

namespace App\Http\Requests;

use App\Traits\HttpResponses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreUserRequest extends FormRequest
{

    use HttpResponses;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => 'required|string|max:255',
            "email" => 'required|email|unique:users,email',
            "password" => 'required|min:8'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException(
            $validator,
            $this->error('error', 422, $validator->errors()),
        );
    }
}
