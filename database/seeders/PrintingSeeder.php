<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Printing;

class PrintingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $printing = [
            'jobid' => 1,
            'pages' => 10,
            'copies' => 1,
            'filename' => 'ofício.pdf',
            'user' => '000001',
            'printer'=> 'printer_colorida',
            'status' => 'Impresso',
            'host' => '10.0.0.5',
        ];
        Printing::create($printing);
        Printing::factory()->count(30)->create();
    }
}
