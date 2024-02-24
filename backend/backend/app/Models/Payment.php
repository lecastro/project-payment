<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'transaction_amount',
        'installments',
        'token',
        'payment_method_id',
        'entity_type',
        'payer_type',
        'payer_email',
        'identification_type',
        'identification_number',
        'notification_url',
        'status',
    ];

    public function getId(): string
    {
        return $this->id;
    }
}
