<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use App\Components\Enums\StatusEnum;
use App\Http\Requests\CreatePaymentRequest;

class PaymentTest extends TestCase
{
    public function testShouldStorePayment(): void
    {
        $paymentService = $this->mock(PaymentService::class);

        $request = new CreatePaymentRequest($this->transaction());

        $paymentService->shouldReceive('store')
            ->once()
            ->with($request->toArray())
            ->andReturn(['id' => '84e8adbf-1a14-403b-ad73-d78ae19b59bf', 'created_at' => '2024-01-01']);

            $response = $this->json(
                method: 'POST',
                uri: route('payments.store'),
                data: $this->transaction(),
                // headers: $this->mountHeader()
            );

        $response->assertStatus(JsonResponse::HTTP_CREATED)
        ->assertJson(['id' => '84e8adbf-1a14-403b-ad73-d78ae19b59bf', 'created_at' => '2024-01-01']);
    }

    public function testShouldIndexPayment(): void
    {
        $paymentService = $this->mock(PaymentService::class);

        $paymentService->shouldReceive('index')
            ->once()
            ->andReturn($this->responseMock());

        $response = $this->json(
            method: 'GET',
            uri: route('payments.index'),
            // headers: $this->mountHeader()
        );

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson($this->transactions());
    }

    public function testShouldShowPayment(): void
    {
        $paymentService = $this->mock(PaymentService::class);

        $paymentService->shouldReceive('show')
            ->once()
            ->andReturn($this->responseMock());

        $response = $this->json(
            method: 'GET',
            uri: route('payments.show', ['id' => '84e8adbf-1a14-403b-ad73-d78ae19b59bf'])
            // headers: $this->mountHeader()
        );

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson($this->transactions());
    }

    public function testShouldConfirmPayment(): void
    {
        $paymentService = $this->mock(PaymentService::class);

        $paymentService->shouldReceive('updateStatus')
            ->once()
            ->andReturn(['status' => StatusEnum::PAID->value]);

        $response = $this->json(
            method: 'PUT',
            uri: route('payments.confirm', ['id' => '84e8adbf-1a14-403b-ad73-d78ae19b59bf'])
            // headers: $this->mountHeader()
        );

        $response->assertStatus(JsonResponse::HTTP_OK);
    }

    public function testShouldCancelPayment(): void
    {
        $paymentService = $this->mock(PaymentService::class);

        $paymentService->shouldReceive('updateStatus')
            ->once()
            ->andReturn(['status' => StatusEnum::CANCELED->value]);

        $response = $this->json(
            method: 'PUT',
            uri: route('payments.cancel', ['id' => '84e8adbf-1a14-403b-ad73-d78ae19b59bf'])
            // headers: $this->mountHeader()
        );

        $response->assertStatus(JsonResponse::HTTP_OK);
    }
}
