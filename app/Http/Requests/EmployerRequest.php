<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class EmployerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|min:10',
            'url' => 'required|url',
            'logo' => [
                'image', 
                'mimes:jpg,png,webp', 
                'max:4096', 
                'dimensions:min_width=100,min_height=100,max_width=500,max_height=500', 
                Rule::requiredIf(Route::currentRouteName() == "employers.store")
            ],
        ];
    }

    public function messages()
    {
        return [
            'logo.dimensions' => "The logo needs to be at least 100x100 and at max 500x500",
        ];
    }
}
