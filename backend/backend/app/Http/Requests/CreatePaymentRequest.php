<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_amount'            => 'required|numeric',
            'installments'                  => 'required|integer',
            'token'                         => 'required|string',
            'payment_method_id'             => 'required|string',
            'payer'                         => 'required|array',
            'payer.email'                   => 'required|email',
            'payer.identification.type'     => 'required|string',
            'payer.identification.number'   => 'required|string',
        ];
    }
}
