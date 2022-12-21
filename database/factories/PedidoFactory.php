<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'codpes'   => $this->faker->ean8(),
            'quantidade' => $this->faker->sentence(2),
            'quantidade_usada' => $this->faker->sentence(2),
            'motivo' => $this->faker->sentence(3),
            'user_id' => User::factory()->create()->id,

        ];
    }
}
