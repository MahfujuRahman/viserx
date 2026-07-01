<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a paginated listing of the resource.
     */
    public function index(Request $request)
    {
        // Capture pagination limit from query parameters (e.g., ?per_page=10), default to 15
        $perPage = $request->query('per_page', 15);

        $categories = $this->categoryRepository->getAll((int) $perPage);
        
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        $category = $this->categoryRepository->create($request->validated());

        return response()->json([
            'message' => 'Category created successfully',
            'data' => new CategoryResource($category),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        return response()->json([
            'data' => new CategoryResource($this->categoryRepository->getById($id)),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, $id): JsonResponse
    {
        $category = $this->categoryRepository->update($id, $request->validated());

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $this->categoryRepository->delete($id);

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}
