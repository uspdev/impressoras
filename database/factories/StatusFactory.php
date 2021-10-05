<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\Printing;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Status::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'        => Status::names()->random(), 
            'printing_id' => Printing::factory()->create()->id, 
        ];
    }
}
