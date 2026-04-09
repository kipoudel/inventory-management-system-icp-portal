<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function list(array $filters): LengthAwarePaginator
    {
        return Product::with('category')
            ->when(isset($filters['search']), fn($q) =>
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('sku', 'like', "%{$filters['search']}%")
            )
            ->when(isset($filters['category_id']), fn($q) =>
                $q->where('category_id', $filters['category_id'])
            )
            ->paginate(15);
    }

    public function store(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }

    public function destroy(Product $product): void
    {
        $product->delete();
    }
}