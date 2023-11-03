<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsForPdfOnPrintingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('printings', function (Blueprint $table) {
            $table->renameColumn('tmp_relpath', 'filepath_original');
            $table->string('filepath_pdfjam')->nullable();
            $table->string('filepath_processed')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('printings', function (Blueprint $table) {
            //
        });
    }
}
