<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Staff::factory()->create([
            "code" => 1,
            "name" => "Super Admin",
            "email" => "superadmin@gmail.com",
            "mobile" => '09887788665',
            "joinedDate" => "2020-01-23",
            "department_id" => 1,
            "position" => 'admin',
            "age" => 33,
            "gender" => "male",
            "status" => 'active',
        ]);
        Staff::factory()->create([
            "code" => 2,
            "name" => "Manager",
            "email" => "manager@gmail.com",
            "mobile" => '09656532321',
            "joinedDate" => "2021-02-23",
            "department_id" => 2,
            "position" => 'manager',
            "age" => 33,
            "gender" => "male",
            "status" => 'active',
            "updated_at" => now(),
            "createdBy" => "admin",
            "updatedBy" => "admin",
        ]);
        Staff::factory()->create([
            "code" => 3,
            "name" => "Standard User 1",
            "email" => "standarduser1@gmail.com",
            "mobile" => '09868654542',
            "joinedDate" => "2022-05-20",
            "department_id" => 2,
            "position" => 'staff',
            "age" => 33,
            "gender" => "male",
            "status" => 'active',
            "updated_at" => now(),
            "createdBy" => "admin",
            "updatedBy" => "admin",
        ]);

        $user1 = User::factory()->create([
            "staff_id" => 1,
            "name" => "Super Admin",
            "email" => "superadmin@gmail.com",
            "password" => Hash::make("superadmin"),
        ]);

        $user2 = User::factory()->create([
            "staff_id" => 2,
            "name" => "Manager",
            "email" => "manager@gmail.com",
            "password" => Hash::make("manager"),
            "createdBy" => "admin",
            "updatedBy" => "admin",
        ]);

        $user3 = User::factory()->create([
            "staff_id" => 3,
            "name" => "Mg Mg",
            "email" => "standarduser1@gmail.com",
            "password" => Hash::make("standarduser1"),
            "createdBy" => "admin",
            "updatedBy" => "admin",
        ]);

        $staff = [];
        $user = [];
        for ($i = 1; $i <= 2; $i++) {
            $name = fake()->name();
            $email  = fake()->unique()->safeEmail();
            $staff[] = [
                "code" => $i + 3,
                "name" => $name,
                "email" => $email,
                "mobile" => fake()->numerify('###########'),
                "joinedDate" => fake()->date(),
                "department_id" => rand(1, 5),
                "position" => fake()->randomElement(['manager', 'staff']),
                "age" => 33,
                "gender" => fake()->randomElement(['male', 'female']),
                "status" => 'active',
                "updated_at" => now(),
                "created_at" => now(),
                "createdBy" => "admin",
                "updatedBy" => "admin",
            ];
            $user[] = [
                "staff_id" => $i + 3,
                "name" => $name,
                "email" => $email,
                "password" => Hash::make("password"),
                "createdBy" => "admin",
                "updatedBy" => "admin",
            ];
        }
        Staff::insert($staff);
        User::insert($user);
        User::all()->each(function ($user) {
            if ($user->staff->position == 'admin') {
                $user->roles()->attach([1, 2]);
            }
            if ($user->staff->position == 'manager') {
                $user->roles()->attach([2, 3]);
            } else {
                $user->roles()->attach([3]);
            }
        });
    }
}
