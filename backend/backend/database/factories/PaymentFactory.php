<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'transaction_amount' => $this->faker->randomFloat(2, 10, 1000),
            'installments' => $this->faker->numberBetween(1, 12),
            'token' => $this->faker->uuid,
            'payment_method_id' => $this->faker->randomElement(['visa', 'mastercard', 'amex']),
            'entity_type' => 'individual',
            'payer_type' => 'customer',
            'payer_email' => $this->faker->safeEmail,
            'identification_type' => 'CPF',
            'identification_number' => $this->faker->numerify('###########'),
            'notification_url' => $this->faker->url,
            'status' => $this->faker->randomElement(['PENDING']),
        ];
    }
}
