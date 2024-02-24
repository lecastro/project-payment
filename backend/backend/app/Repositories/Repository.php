<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Components\Traits\DateTrait;
use Illuminate\Database\Eloquent\Model as AbstractModel;

abstract class Repository implements BaseRepositoryContract
{
    use DateTrait;

    public function __construct(AbstractModel $model)
    {
        $this->model = $model;
    }

    /** @param array<mixed> $attributes*/
    public function create(array $attributes): AbstractModel
    {
        return $this->model->create($attributes);
    }

    public function findOnBy(string $column, string $value): null|AbstractModel
    {
        return $this->model->where($column, $value)->first();
    }
}

