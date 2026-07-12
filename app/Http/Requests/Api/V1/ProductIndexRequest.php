<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'search' => ['nullable', 'string', 'max:255'],
        'category' => ['nullable', 'string'],
        'brand' => ['nullable', 'string'],

        'price_from' => ['nullable', 'numeric'],
        'price_to' => ['nullable', 'numeric'],

        'attributes' => ['nullable', 'array'],
        'attributes.*' => ['integer'],

        'sort' => ['nullable', 'in:latest,price_low,price_high,popular,offers'],
        'all' => ['nullable', 'boolean'],
        'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
    ];
    }

}
