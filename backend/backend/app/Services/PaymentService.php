<?php

declare(strict_types=1);

namespace App\Services;

use App\Components\Enums\StatusEnum;
use App\Repositories\PaymentRepository;
use App\Components\Adapter\PaymentAdapter;
use App\Http\Requests\CreatePaymentRequest;
use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\PaymentNotFoundException;

class PaymentService
{
    public function __construct(private PaymentRepository $paymentRepository)
    {
    }

    /**
     * @param array<mixed> $paymentData
     * @return array<mixed>
     */
    public function store(array $paymentData): array
    {
        $paymentAdapted = (new PaymentAdapter($paymentData))->toArray();

        return $this->paymentRepository->create($paymentAdapted)->toArray();
    }

    /** @return array<mixed> */
    public function index(): array
    {
        return $this->paymentRepository->getAll();
    }

    /** @return array<mixed> */
    public function show(string $id): array
    {
        return [$this->paymentRepository->findOnBy('id', $id)->toArray()];
    }

    /** @return array<mixed> */
    public function updateStatus(string $id, string $status): array
    {
        $payment = $this->paymentRepository->findOnBy('id', $id);

        if ($payment === null) {
            throw new PaymentNotFoundException();
        }

        $payment = $payment->where('id', $id)->update(['status' => $status]);

        if ($payment == true) {
           return [
            'status' => StatusEnum::PAID->value
           ];
        }

        return [
            'status' => StatusEnum::CANCELED->value
        ];
    }
}
