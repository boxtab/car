<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $items = [];
        $words = $this->faker->words(rand(0, 3));
        foreach ($words as $word) {
            $items[$word] = rand(100, 999);
        }
        return [
            'email' => $this->faker->unique()->safeEmail,
            'items' => $items,
        ];
    }
}
