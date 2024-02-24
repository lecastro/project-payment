<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model as AbstractModel;

interface BaseRepositoryContract
{
    /**
     * @param array<mixed> $attributes
     */
    public function create(array $attributes): AbstractModel;

    public function findOnBy(string $column, string $value): null|AbstractModel;
}
