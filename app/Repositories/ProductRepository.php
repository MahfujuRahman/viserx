<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(int $perPage = 15)
    {
        return Product::query()
            ->with('category') // Eager load category to prevent N+1 issues
            ->latest()
            ->paginate($perPage);
    }

    public function getById($id)
    {
        return Product::query()
            ->with('category')
            ->findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
    }
}
