<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository extends Repository
{
    public function __construct(protected Payment $model)
    {
    }

    /** @return array<mixed> */
    public function getAll(): array
    {
        return $this->model->get()->toArray();
    }
}
