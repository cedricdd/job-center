<?php

namespace App\Http\Requests;

use App\Constants;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

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
            'url' => 'required|active_url',
            'logo' => [
                'image',
                'mimes:jpg,png,webp',
                'max:' . Constants::MAX_WEIGHT_EMPLOYER_LOGO,
                'dimensions:min_width=' . Constants::MIN_RES_EMPLOYER_LOGO . ',min_height=' . Constants::MIN_RES_EMPLOYER_LOGO . ',max_width=' . Constants::MAX_RES_EMPLOYER_LOGO . ',max_height=' . Constants::MAX_RES_EMPLOYER_LOGO,
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
