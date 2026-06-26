<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;


interface IProductRepository
{
    public function paginateWithFilters(array $filters);

    public function find(int $id): ?Model;

    public function create(array $data): ?Model;

    public function update(int $id, array $data): ?Model;

    public function delete(int $id): bool;
}