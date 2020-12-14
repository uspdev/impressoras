<?php

namespace Database\Factories;

use App\Models\Printing;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrintingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Printing::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = ['Fila', 'Impresso', 'Cancelado', 'Problema'];
        return [
            'jobid' => $this->faker->randomNumber($nbDigits=5, $strict=false),
            'pages' => $this->faker->numberBetween($min=1, $max=100),
            'copies' => $this->faker->randomDigit(),
            'filename' => $this->faker->word() . '.pdf',
            'user' => $this->faker->graduacao(),
            'printer'=> $this->faker->numerify('printer #'),
            'status' => $status[array_rand($status)],
            'host' => $this->faker->localIpv4(),
        ];
    }
}
