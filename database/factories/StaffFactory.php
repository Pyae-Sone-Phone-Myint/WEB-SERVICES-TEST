<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return [
            "code" => $randomString,
            "name" => fake()->name(),
            "email" => fake()->unique()->safeEmail(),
            "mobile" => fake()->numerify('###########'),
            "joinedDate" => fake()->date(),
            "department_id" => rand(1,5),
            "position" => fake()->randomElement(['manager','staff']),
            "age" => 33,
            "gender" => fake()->randomElement(['male', 'female']),
            "status" => 'active',
            "updated_at" => now(),
            "createdBy" => "admin",
            "updatedBy" => "admin",
        ];
    }
}
