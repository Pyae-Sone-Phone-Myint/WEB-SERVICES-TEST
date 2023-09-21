<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            (object)['name' => "admin", "label" => "Administrator"],
            (object)['name' => "manager", "label" => "Manager"],
            (object)['name' => "standard_user", "label" => "Standard User"]
        ];
        $arr = [];
        foreach ($roles as $role) {
            $arr[] = [
                "name" => $role->name,
                "label" => $role->label,
                "updated_at" => now(),
                "created_at" => now(),
            ];
        }
        Role::insert($arr);
    }
}
