<?php

declare(strict_types=1);

namespace App\Components\Adapter;

class PaymentAdapter
{
    /** @var array<mixed> $data*/
    public function __construct(private array $data) {}

    /** @return array<mixed>*/
    public function toArray(): array
    {
        return [
            'transaction_amount'    => data_get($this->data, 'transaction_amount'),
            'installments'          => data_get($this->data, 'installments'),
            'token'                 => data_get($this->data, 'token'),
            'payment_method_id'     => data_get($this->data, 'payment_method_id'),
            'payer_email'           => data_get($this->data, 'payer.email'),
            'identification_type'   => data_get($this->data, 'payer.identification.type'),
            'identification_number' => data_get($this->data, 'payer.identification.number'),
            'notification_url'      => 'https://webhook.site/dd30d597-c1df-42bc-a06c-5d7bce3b07b8'
        ];
    }
}
