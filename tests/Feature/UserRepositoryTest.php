<?php

namespace Tests\Feature;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_users(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $userRepository = new UserRepository();
        $users = $userRepository->getAllUser();

        $this->assertCount(2, $users);
        $this->assertTrue($users->contains($user1));
        $this->assertTrue($users->contains($user2));

    }

    public function test_can_get_user_by_id(): void
    {
        $user = User::factory()->create();
        $userRepository = new UserRepository();
        $foundUser = $userRepository->getUserById($user->id);

        $this->assertInstanceOf(User::class,$foundUser);
        $this->assertEquals($user->id,$foundUser->id);
    }

    public function test_throws_exception_for_non_existence_user(): void
    {
        $nonExists = 999;
        $userRepository = new UserRepository();
        $this->expectException(ModelNotFoundException::class);
        $userRepository->getUserById($nonExists);
    }

    public function test_can_create_new_user(): void
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john.doe@mail.com',
            'password' => Hash::make('password')
        ];

        $userRepository = new UserRepository();

        $createdUser = $userRepository->createUser($userData);
        $this->assertInstanceOf(User::class,$createdUser);
        $this->assertDatabaseHas('users',[
            'name' => $userData['name'],
            'email' => $userData['email']
        ]);
    }

    public function test_can_update_user(): void
    {
        $user = User::factory()->create();
        $updatedData = [
            'name' => 'updated name',
            'email' => 'update@example.com'
        ];

        $userRepository = new UserRepository();

        $updatedUser = $userRepository->updateUser($user->id,$updatedData);

        $this->assertTrue($updatedUser);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $updatedData['name'],
            'email' => $updatedData['email'],
        ]);
    }

    public function test_can_delete_user(): void
    {
        $user = User::factory()->create();
        $userRepository = new UserRepository();

        $deletedUser = $userRepository->deleteUser($user->id);

        $this->assertTrue($deletedUser);
        $this->assertDatabaseMissing('users',['id'=>$user->id]);
    }

    public function test_can_get_user_id_by_email(): void
    {
        $user = User::factory()->create();
        $userRepository = new UserRepository();

        $findUser = $userRepository->getUserByEmail($user->email);

        $this->assertInstanceOf(User::class,$findUser);
        $this->assertSame($user->id,$findUser->id);
    }

}
