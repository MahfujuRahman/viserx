<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAll(int $perPage = 15)
    {
        return Category::query()
            ->withCount('products')
            ->latest()
            ->paginate($perPage); // <-- Swapped get() out for paginate()
    }

    public function getById($id)
    {
        return Category::query()
            ->withCount('products')
            ->findOrFail($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update($id, array $data)
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
    }
}
