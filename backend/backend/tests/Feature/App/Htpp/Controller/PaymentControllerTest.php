<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentControllerTest extends TestCase
{
    public function testShouldCreatePayment(): void
    {
        $response = $this->json(
            method: 'POST',
            uri: route('payments.store'),
            data: $this->transaction(),
            // headers: $this->mountHeader()
        );

        $response->assertJsonStructure([
            'id',
            'created_at'
        ])->assertStatus(201);
    }

    public function testShouldListPayment(): void
    {
        Payment::factory()->create();

        $response = $this->json(
            method: 'GET',
            uri: route('payments.index'),
            // headers: $this->mountHeader()
        );

        $response->assertJsonStructure(
            [
                [
                    'id',
                    'status',
                    'transaction_amount',
                    'installments',
                    'token',
                    'payment_method_id',
                    'payer' => [
                        'entity_type',
                        'type',
                        'email',
                        'identification' => [
                            'type',
                            'number',
                        ],
                    ],
                    'notification_url',
                    'created_at',
                    'updated_at'
                ]
            ]
        )->assertStatus(200);
    }

    public function testShouldListPaymentById(): void
    {
        $id = Payment::factory()->create();

        $response = $this->json(
            method: 'GET',
            uri: route('payments.show', ['id' => $id->getId()])
            // headers: $this->mountHeader()
        );

        $response->assertJsonStructure(
            [
                [
                    'id',
                    'status',
                    'transaction_amount',
                    'installments',
                    'token',
                    'payment_method_id',
                    'payer' => [
                        'entity_type',
                        'type',
                        'email',
                        'identification' => [
                            'type',
                            'number',
                        ],
                    ],
                    'notification_url',
                    'created_at',
                    'updated_at'
                ]
            ]
        )->assertStatus(200);
    }

    public function testShouldConfirmPaymentById(): void
    {
        $id = Payment::factory()->create();

        $response = $this->json(
            method: 'PUT',
            uri: route('payments.confirm', ['id' => $id->getId()])
            // headers: $this->mountHeader()
        );

        $response->assertJsonStructure(['status'])->assertStatus(200);
    }

    public function testShouldCancelPaymentById(): void
    {
        $id = Payment::factory()->create();

        $response = $this->json(
            method: 'PUT',
            uri: route('payments.cancel', ['id' => $id->getId()])
            // headers: $this->mountHeader()
        );

        $response->assertJsonStructure(['status'])->assertStatus(200);
    }
}
