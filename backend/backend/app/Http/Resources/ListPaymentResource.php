<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $payment = parent::toArray($request);

        $payments = [];

        foreach ($payment as $value) {
            $payments[] = $this->formatPaymentResource($value);
        }

        return $payments;
    }

    /**
     * @param array<string> $payment
     * @return array<string>
     */
    private function formatPaymentResource(array $payment): array
    {
        $data = Collect($payment);

        return [
            'id'                    => $data->get('id'),
            'status'                => $data->get('status'),
            'transaction_amount'    => $data->get('transaction_amount'),
            'installments'          => $data->get('installments'),
            'token'                 => $data->get('token'),
            'payment_method_id'     => $data->get('payment_method_id'),
            'payer' => [
                'entity_type'       => $data->get('entity_type'),
                'type'              => $data->get('payer_type'),
                'email'             => $data->get('payer_email'),
                'identification' => [
                    'type'          => $data->get('identification_type'),
                    'number'        => $data->get('identification_number'),
                ],
            ],
            'notification_url'   => $data->get('notification_url'),
            'created_at'         => $data->get('created_at'),
            'updated_at'         => $data->get('updated_at'),
        ];

        [
            "id" => "84e8adbf-1a14-403b-ad73-d78ae19b59bf",
            "status" => "CANCELED",
            "transaction_amount" => 245.90,
            "installments" => 3,
            "token" => "ae4e50b2a8f3h6d9f2c3a4b5d6e7f8g9",
            "payment_method_id" => "master",
            "payer" => [
                "entity_type" => "individual",
                "type" => "customer",
                "email" => "example_random@gmail.com",
                "identification" => [
                    "type" => "CPF",
                    "number" => "12345678909"
                ]
            ],
            "notification_url" => "https://webhook.site/unique-r",
            "created_at" => "2024-01-10",
            "updated_at" => "2024-01-11"
        ];
    }
}
