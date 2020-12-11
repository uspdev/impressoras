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
        return [
            jobid    bigint(20)
            pages    bigint(20)
            copies   bigint(20)
            filename varchar(191)
            user     varchar(191)
            printer  varchar(191)
            status   varchar(1024)
            host     varchar(191)
        ];
    }
}
