<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreRequest extends FormRequest
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
            'sender_id' => ['required', 'integer', 'exists:accounts,id'],
            'receiver_id' => ['required', 'integer', 'exists:accounts,id'],
            'amount' => ['required', 'numeric', 'min:0.01', 'regex:/^\d+(\.\d{1,2})?$/'],
            'process_at' => ['nullable', 'date_format:Y-m-d', 'after:today'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'sender_id' => [
                'description' => 'Sender account ID',
                'example' => 1,
            ],
            'receiver_id' => [
                'description' => 'Receiver account ID',
                'example' => 2,
            ],
            'amount' => [
                'description' => 'Transaction amount, must be at least 0.01 and have up to two decimal places.',
                'example' => 10.00,
            ],
            'process_at' => [
                'description' => 'Transaction scheduled date, must be a date after the current date',
                'example' => '2024-12-31'
            ]
        ];
    }
}
