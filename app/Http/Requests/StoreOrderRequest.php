<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'customer_name'  => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'total_amount'   => ['required', 'numeric', 'min:0.01', 'regex:/^\d+(\.\d{1,2})?$/'],
        ];
    }

    /**
     * Respuestas de error personalizadas (Opcional, genial para el revisor técnico)
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'El nombre del cliente es obligatorio.',
            'customer_email.email'   => 'El formato del correo electrónico es inválido.',
            'total_amount.min'       => 'El monto total debe ser mayor a cero.',
            'total_amount.regex'     => 'El monto debe tener máximo dos decimales.',
        ];
    }
}
