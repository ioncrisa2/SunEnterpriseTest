<?php

namespace App\Interfaces\RepositoriesInterface;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function getProductById(int $id): ?Product;
    public function storeProduct(array $data): ?Product;
}
