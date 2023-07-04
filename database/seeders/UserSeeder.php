<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolePermission = [
            'super admin' => [
                'create user', 'see all user', 'get user by id', 'edit user', 'delete user',
                'create product', 'see all product', 'get product by id', 'edit product', 'delete product',
                'create category', 'see all category', 'get category by id', 'edit category', 'delete category',
            ],
            'admin' => [
                'delete category', 'delete product', 'edit category', 'edit category',
            ],
            'customer' => [
                'see all product', 'get product by id', 'see all category', 'get category by id',
            ],
        ];

        foreach ($rolePermission as $roleName => $permissionName) {
            $role = Role::create(['name' => $roleName]);
            foreach ($permissionName as $permission) {
                if (!Permission::where('name', $permission)->exists()) {
                    Permission::create(['name' => $permission]);
                }
            }
            $role->syncPermissions($permissionName);
        }


        $superAdmin = User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password')
        ]);
        $superAdmin->assignRole('super admin');

        $adminUser = User::factory(2)->create();
        $adminUser->each(function ($user) {
            $user->assignRole('admin');
            $user->givePermissionTo(['delete category', 'delete product', 'edit category', 'edit product']);
        });

        $customerUser = User::factory(15)->create();
        $customerUser->each(function ($user) {
            $user->assignRole('customer');
            $user->givePermissionTo(['see all product', 'get product by id', 'see all category', 'get category by id']);
        });
    }
}
