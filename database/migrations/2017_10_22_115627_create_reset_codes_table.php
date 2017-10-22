<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResetCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reset_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned(); // id of the user 
            $table->string('code', 255);
            $table->tinyInteger('status')->default(0); // 0 for unused and 1 for used
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reset_codes');
    }
}
