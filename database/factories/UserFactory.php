<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'username'   => $this->faker->unique()->safeEmail(),
            'password'   => Hash::make('password'), // default test password
            'first_name' => $this->faker->firstName(),
            'last_name'  => $this->faker->lastName(),
            'role_id'    => 4, // default athlete role
            'status'     => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
