<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    /**
     * GET /products
     * List products with search + filters + pagination
     */
    public function index(Request $request)
    {
        $products = $this->productService->list($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Product list fetched successfully',
            'data' => $products
        ]);
    }

    /**
     * POST /products
     * Create product
     */
    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->store($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    /**
     * GET /products/{id}
     * Show single product
     */
    public function show(int $id)
    {
        $product = $this->productService->show($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $product
        ]);
    }

    /**
     * PUT/PATCH /products/{id}
     * Update product (partial update supported)
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        $product = $this->productService->update($id, $request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    /**
     * DELETE /products/{id}
     */
    public function destroy(int $id)
    {
        $deleted = $this->productService->delete($id);

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully',
            'deleted' => $deleted
        ]);
    }
}