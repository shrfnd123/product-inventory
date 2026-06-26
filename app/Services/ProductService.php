<?php

namespace App\Services;

use App\Repositories\Interfaces\IProductRepository;

class ProductService
{
    public function __construct(
        private IProductRepository $productRepository
    ) {}

    /**
     * Get all products with filters + pagination
     */
    public function list(array $filters)
    {
        return $this->productRepository->paginateWithFilters($filters);
    }

    /**
     * Get single product
     */
    public function show(int $id)
    {
        return $this->productRepository->find($id);
    }

    /**
     * Create product
     */
    public function store(array $data)
    {
        return $this->productRepository->create($data);
    }

    /**
     * Update product
     */
    public function update(int $id, array $data)
    {
        return $this->productRepository->update($id, $data);
    }

    /**
     * Delete product
     */
    public function delete(int $id): bool
    {
        return $this->productRepository->delete($id);
    }
}