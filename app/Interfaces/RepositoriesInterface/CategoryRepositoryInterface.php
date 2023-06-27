<?php

namespace App\Interfaces\RepositoriesInterface;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function getAllCategories(): Collection;
    public function getCategoryById(int $id): Category;
    public function storeCategory(array $data): ?Category;
    public function updateCategory(int $id,array $data): ?Category;
    public function deleteCategory(int $id): bool;
}
