<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Interfaces\ProductRepositoryInterface;
use App\Jobs\SendProductEmail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display a paginated listing of the resource.
     */
    public function index(Request $request)
    {
        // Capture ?per_page from query parameters, default to 15
        $perPage = $request->query('per_page', 15);

        $products = $this->productRepository->getAll((int) $perPage);

        // Returning the Resource Collection directly maps the pagination metadata
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $product = $this->productRepository->create($request->validated());

        SendProductEmail::dispatch($product->load('category'), 'created');

        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductResource($product),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        return response()->json([
            'data' => new ProductResource($this->productRepository->getById($id)),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, $id): JsonResponse
    {
        $product = $this->productRepository->update($id, $request->validated());

        SendProductEmail::dispatch($product->load('category'), 'updated');

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $this->productRepository->delete($id);

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
