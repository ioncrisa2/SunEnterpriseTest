<?php

namespace App\Repositories;

use App\Interfaces\RepositoriesInterface\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ProductRepository implements ProductRepositoryInterface
{
    private Product $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    public function getAllData(): Product|Collection
    {
        return $this->product->all();
    }

    /**
     * @return Product|null
     */
    public function getProductById(int $id): ?Product
    {
        return $this->product->findOrFail($id);
    }

    /**
     * @return Product|null
     */
    public function storeProduct(array $data): ?Product
    {
        $slug = Str::slug($data['name']);
        return $this->product->create([
            'name' => $data['name'],
            'slug' => $slug,
            'price' => $data['price'],
            'description' => $data['description'],
        ]);
    }

    public function updateProduct(int $id,array $data): ?Product
    {
        $product = $this->getProductById($id);
        $product->fill($data);
        $product->save();
        return $product;
    }

    public function deleteProduct(int $id): bool
    {
        return $this->getProductById($id)->delete();
    }
}
