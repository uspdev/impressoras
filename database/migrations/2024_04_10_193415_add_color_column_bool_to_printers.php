<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Printer;

class AddColorColumnBoolToPrinters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->boolean('color')->default(0)->nullable();
        });
        // Até esse ponto no sistema, todas impressoras são PB:
        $printers = Printer::all();
        foreach($printers as $printer){
            $printer->color = 0;
            $printer->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('printers', function (Blueprint $table) {
            //
        });
    }
}
