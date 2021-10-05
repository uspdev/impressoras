<?php

namespace Database\Factories;

use App\Models\Printer;
use App\Models\Rule;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrinterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Printer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'machine_name' => $this->faker->swiftBicNumber,
            'name'         => $this->faker->lastName, 
            'rule_id'      => Rule::factory()->create()->id, 
        ];
    }
}
