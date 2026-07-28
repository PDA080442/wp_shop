<?php

namespace App\Services;

use App\Contracts\ProductServiceInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductService implements ProductServiceInterface
{
    /**
     * @return Collection<int, Product>
     */
    public function getAll(): Collection
    {
        return Product::query()->orderBy('name')->get();
    }

    public function getById(int $id): Product
    {
        return Product::query()->findOrFail($id);
    }

    public function getPaginated(int $perPage = 12): LengthAwarePaginator
    {
        return Product::query()->orderBy('name')->paginate($perPage);
    }
}
