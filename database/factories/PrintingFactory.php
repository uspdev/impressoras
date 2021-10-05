<?php

namespace Database\Factories;

use App\Models\Printing;
use App\Models\Printer;
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
        return [
            'jobid'      => $this->faker->randomNumber($nbDigits=5, $strict=false),
            'pages'      => $this->faker->numberBetween($min=1, $max=100),
            'copies'     => $this->faker->randomDigit(),
            'filename'   => $this->faker->word() . '.pdf',
            'filesize'   => $this->faker->randomNumber(),
            'user'       => $this->faker->graduacao(),
            'host'       => $this->faker->localIpv4(),
            'printer_id' => Printer::factory()->create()->id,
        ];
    }
}
