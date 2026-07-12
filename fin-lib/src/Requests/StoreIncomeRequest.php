<?php

namespace FinTrack\FinLib\Requests;

use Illuminate\Foundation\Http\FormRequest;
use FinTrack\FinLib\Enums\IncomeType;
use Illuminate\Validation\Rule;

class StoreIncomeRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'gt:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'type' => ['required', Rule::enum(IncomeType::class)],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
