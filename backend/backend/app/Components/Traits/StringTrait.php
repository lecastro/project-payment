<?php

declare(strict_types=1);

namespace App\Components\Traits;

trait StringTrait
{
    private static $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function generateRandomSequence(int $length): string {
        $sequence = '';
        $charactersLength = strlen(self::$characters);

        for ($i = 0; $i < $length; $i++) {
            $sequence .= self::$characters[rand(0, $charactersLength - 1)];
        }

        return $sequence;
    }
}
