<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    public static $migrated = false;

    /** @return array<string> */
    public function transaction(): array
    {
        return [
            'transaction_amount' => 245.90,
            'installments' => 3,
            'token' => 'ae4e50b2a8f3h6d9f2c3a4b5d6e7f8g9',
            'payment_method_id' => 'master',
            'payer' => [
                'email' => 'example_random@gmail.com',
                'identification' => [
                    'type' => 'CPF',
                    'number' => '12345678909'
                ]
            ]
        ];
    }

    /** @return array<string> */
    public function transactions():array
    {
        return [
            [
                "id" => "84e8adbf-1a14-403b-ad73-d78ae19b59bf",
                "status" => "CANCELED",
                "transaction_amount" => 245.90,
                "installments" => 3,
                "token" => "ae4e50b2a8f3h6d9f2c3a4b5d6e7f8g9",
                "payment_method_id" => "master",
                "payer" => [
                    "entity_type" => "individual",
                    "type" => "customer",
                    "email" => "example_random@gmail.com",
                    "identification" => [
                        "type" => "CPF",
                        "number" => "12345678909"
                    ]
                ],
                "notification_url" => "https://webhook.site/unique-r",
                "created_at" => "2024-01-10",
                "updated_at" => "2024-01-11"
            ]
        ];
    }

    /** @return array<string> */
    public function responseMock():array
    {
        return [
            [
                'id' => '84e8adbf-1a14-403b-ad73-d78ae19b59bf',
                'status' => 'CANCELED',
                'transaction_amount' => 245.90,
                'installments' => 3,
                'token' => 'ae4e50b2a8f3h6d9f2c3a4b5d6e7f8g9',
                'payment_method_id' => 'master',
                'entity_type' => 'individual',
                'payer_type' => 'customer',
                'payer_email' => 'example_random@gmail.com',
                'identification_type' => 'CPF',
                'identification_number' => '12345678909',
                'notification_url' => 'https://webhook.site/unique-r',
                'created_at' => '2024-01-10',
                'updated_at' => '2024-01-11'
            ]
        ];
    }

    protected function afterRefreshingDatabase()
    {
        Artisan::call('db:seed');
    }
}
