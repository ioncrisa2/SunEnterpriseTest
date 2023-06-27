<?php

namespace App\Repositories;

use App\Interfaces\RepositoriesInterface\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function getAllUser(): Collection
    {
        return $this->user->all();
    }

    public function getUserById(int $id): User
    {
        return $this->user->findOrFail($id);
    }

    public function getUserByEmail(string $email): ?User
    {
        return $this->user->where('email',$email)->first();
    }

    public function createUser(array $data): User
    {
        return $this->user::create($data);
    }

    public function updateUser(int $id,array $data): bool
    {
        return $this->user->findOrFail($id)->update($data);
    }

    public function deleteUser(int $id): bool
    {
        return $this->user->findOrFail($id)->delete();
    }

}
