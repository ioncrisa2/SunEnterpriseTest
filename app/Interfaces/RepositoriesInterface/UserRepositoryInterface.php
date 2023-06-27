<?php

namespace App\Interfaces\RepositoriesInterface;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function getAllUSer(): Collection;
    public function getUserById(int $id): User;
    public function getUserByEmail(string $email): ?User;
    public function createUser(array $data): User;
    public function updateUser(int $id, array $data): bool;
    public function deleteUser(int $id): bool;
}
