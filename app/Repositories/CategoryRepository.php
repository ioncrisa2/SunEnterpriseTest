<?php

namespace App\Repositories;

use App\Interfaces\RepositoriesInterface\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryRepository implements CategoryRepositoryInterface
{
    private Category $category;

    public function __construct()
    {
        $this->category = new Category();
    }

    public function getAllCategories(): Collection
    {
        return $this->category->all();
    }

    public function getCategoryById(int $id): Category
    {
        return $this->category->findOrFail($id);
    }

    public function storeCategory(array $data): ?Category
    {
        return $this->category->create($data);
    }

    public function getDataOrCreate(string $name): ?Category
    {
        return $this->category->firstOrCreate(['name' => $name]);
    }

    public function updateCategory(int $id, array $data): ?Category
    {
        $category = $this->getCategoryById($id);
        $category->fill($data);
        $category->save();
        return $category;
    }

    public function deleteCategory(int $id): bool
    {
        return $this->getCategoryById($id)->delete();
    }
}
