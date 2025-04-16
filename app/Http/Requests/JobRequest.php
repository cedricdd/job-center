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
            "title" => "bail|required|string|max:" . Constants::MAX_STRING_LENGTH . "|min:" . Constants::MIN_STRING_LENGTH,
            "salary" => "bail|required|string|max:" . Constants::MAX_STRING_LENGTH . "|min:" . Constants::MIN_STRING_LENGTH,
            "location" => "bail|required|string|max:" . Constants::MAX_STRING_LENGTH. "|min:" . Constants::MIN_STRING_LENGTH,
            "url" => "bail|required|active_url",
            "schedule" => ["bail", "required", "string", Rule::in(Constants::SCHEDULES)],
            "employer_id" => ["bail", "required", "integer", new OwnerEmployer],
            "tags" => "nullable|string",
            "featured" => "nullable|boolean",
        ];
    }
}
