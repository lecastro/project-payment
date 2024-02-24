<?php

declare(strict_types=1);

use App\Models\Payment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create($this->table(), function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('transaction_amount', 64, 0)->default(0);
            $table->integer('installments');
            $table->string('token');
            $table->string('payment_method_id');
            $table->string('entity_type')->default('individual');
            $table->string('payer_type')->default('customer');
            $table->string('payer_email');
            $table->string('identification_type');
            $table->string('identification_number');
            $table->string('notification_url');
            $table->enum('status', ['PENDING', 'PAID', 'CANCELED'])->default('PENDING');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::drop($this->table());
    }

    private function table(): string
    {
        return (new Payment())->getTable();
    }
};
