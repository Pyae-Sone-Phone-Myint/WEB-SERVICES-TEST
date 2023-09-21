<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $createUser = Permission::create(['name' => 'create-user', 'label' => "Create User"]);
        $editUser = Permission::create(['name' => 'edit-user', 'label' => "Edit User"]);
        $deleteUser = Permission::create(['name' => 'delete-user', "label" => "Delete User"]);
        $viewUser = Permission::create(['name' => 'view-user', "label" => "View User"]);

        // Assign permissions to roles
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $standardUser1 = Role::where('name', 'standard_user')->first();

        $adminRole->permissions()->attach([$createUser->id, $editUser->id, $viewUser->id, $deleteUser->id]);
        $managerRole->permissions()->attach([$viewUser->id, $createUser->id, $editUser->id]);
        $standardUser1->permissions()->attach([$viewUser->id, $editUser->id]);
    }
}
