<?php

namespace App\Http\Requests;

use App\Constants;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['bail', 'required', 'string', 'min:' . Constants::MIN_STRING_LENGTH, 'max:' . Constants::MAX_STRING_LENGTH],
            'email' => "bail|required|email|unique:" . User::class,
            'password' => ['bail', 'required', 'string', 'min:' . Constants::MIN_PASSWORD_LENGTH, 'max:' . Constants::MAX_PASSWORD_LENGTH, 'confirmed'],
        ];
    }
}
