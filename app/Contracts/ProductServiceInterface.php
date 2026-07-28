<?php

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductServiceInterface
{
    /**
     * @return Collection<int, Product>
     */
    public function getAll(): Collection;

    public function getById(int $id): Product;

    public function getPaginated(int $perPage = 12): LengthAwarePaginator;
}
