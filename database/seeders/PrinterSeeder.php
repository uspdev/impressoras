<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Printer;

class PrinterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $printer = [
            'machine_name' => 'ProAlunoPrinter008726',
            'name'         => 'Pró-Aluno',
            'rule_id'      => 1,
        ];

        Printer::create($printer);
    }
}
