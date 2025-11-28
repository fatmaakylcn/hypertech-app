<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartUpdateRequest extends FormRequest
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
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'Adet alanı zorunludur.',
            'quantity.integer'  => 'Adet sayısal olmalıdır.',
            'quantity.min'      => 'Adet en az 1 olabilir.',
            'quantity.max'      => 'Adet en fazla 100 olabilir.',
        ];
    }
}
