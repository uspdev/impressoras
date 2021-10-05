<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Printing;
use App\Models\Printer;

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
            'jobid'     => 1,
            'pages'     => 10,
            'copies'    => 1,
            'filename'  => 'ofício.pdf',
            'filesize'  => '20000',
            'user'      => '5385361',
            'host'      => '10.0.0.5',
            'printer_id'=> 1,
        ];

        Printing::create($printing);
    }
}
