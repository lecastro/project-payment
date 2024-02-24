<?php

declare(strict_types=1);

namespace App\Components\Enums;

enum StatusEnum: string
{
    case PENDING  = 'PENDING';
    case PAID     = 'PAID';
    case CANCELED = 'CANCELED';
}
