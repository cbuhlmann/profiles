<?php

namespace App\Http\Requests\Profile;

use App\Models\Profile;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstName' => ['string', 'max:255'],
            'lastName'  => ['string', 'max:255'],
            'image'     => ['file', 'mimes:jpg,jpeg,png'],
            'status'    => ['integer', Rule::in(array_keys(Profile::STATUSES))],
        ];
    }
}
