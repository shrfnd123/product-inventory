<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\IProductRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ProductRepository extends BaseRepository implements IProductRepository
{
    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    /**
     * Paginated product list with filters
     */
    public function paginateWithFilters(array $filters): LengthAwarePaginator
    {
        return $this->model
            ->query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($filters['sku'] ?? null, function ($query, $sku) {
                $query->where('sku', $sku);
            })
            ->when(isset($filters['out_of_stock']) && $filters['out_of_stock'], function ($query) {
                $query->where('stock', 0);
            })
            ->paginate(15);
    }

    /**
     * Find product by ID
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Create product
     */
    public function create(array $data): ?Model
    {
        return $this->model->create($data);
    }

    /**
     * Update product
     */
    public function update(int $id, array $data): ?Model
    {
        $product = $this->model->findOrFail($id);
        $product->update($data);

        return $product;
    }

    /**
     * Delete product
     */
    public function delete(int $id): bool
    {
        $product = $this->model->findOrFail($id);

        return $product->delete();
    }
}