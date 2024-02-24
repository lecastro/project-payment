<?php

declare(strict_types=1);

namespace App\Components\Traits;

use DateTime;
use Carbon\Carbon;

trait DateTrait
{
    public function now(): Carbon
    {
        return Carbon::now();
    }

    public function adaptCarbon(Carbon|string $date): string
    {
        if (is_string($date)) {
            return $date;
        }

        return $date->format('d-m-Y');
    }

    public function adaptCarbonDateTime(Carbon|string $date): string
    {
        if (is_string($date)) {
            return $date;
        }

        return $date->format('d-m-Y H:i:s');
    }

    public function convertCarbon(string $date): Carbon
    {
        return carbon::parse($date);
    }

    public function dateTimeFormat(string|DateTime $date): string
    {
        if (is_string($date)) {
            return $date;
        }

        return $date->format('d-m-Y');
    }

    public function parseDate(string $date): Carbon
    {
        return \Carbon\Carbon::parse($date);
    }

    public function nowformated(): string
    {
        return Carbon::now()->format('Y-m-d');;
    }
}
