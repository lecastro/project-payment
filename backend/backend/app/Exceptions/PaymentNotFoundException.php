<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class PaymentNotFoundException extends Exception
{
    public function getShortMessage(): string
    {
        return 'AddressNotFoundException';
    }

    public function getDescription(): string
    {
        return trans('Payment not found with the specified id');
    }

    public function getHttpStatus(): int
    {
        return Response::HTTP_NOT_FOUND;
    }
}
