<?php

namespace App\Http\Requests;

use App\Constants;
use App\Rules\OwnerEmployer;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
            "title" => "required|string|max:255|min:3",
            "salary" => "required|string|max:255|min:3",
            "location" => "required|string|max:255",
            "url" => "required|active_url",
            "schedule" => ["required", "string", Rule::in(Constants::SCHEDULES)],
            "employer_id" => ["required", "integer", new OwnerEmployer],
            "tags" => "nullable|string",
            "featured" => "nullable|boolean",
        ];
    }
}
