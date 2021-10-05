<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            'name'       => 'sent_to_printer_queue',
            'printing_id'=> 1,
        ];

        Status::create($status);
        Status::factory()->create();
    }
}
