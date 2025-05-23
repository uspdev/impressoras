<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Printing;
use App\Models\User;

class AddForeignKeyUsersOnPrintingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('printings', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained();
        });
        // Fazendo relacionamento dos usuários com printing
        $printings = Printing::all();
        foreach($printings as $printing){
            $user = User::where('codpes',$printing->user)->first();
            if($user) {
                $printing->user_id = $user->id;
                $printing->save();
            }

        }
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
