<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = parent::toArray($request);

        return $this->formatPaymentResource($response);
    }

    /**
     * @param array<string> $payment
     * @return array<string>
     */
    private function formatPaymentResource(array $payment): array
    {
        $data = Collect($payment);

        return [
            'id'            => $data->get('id'),
            'created_at'    => $data->get('created_at'),
        ];
    }
}
