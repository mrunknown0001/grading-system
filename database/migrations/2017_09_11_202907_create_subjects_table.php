<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('level')->unsigned();
            $table->string('title', 100);
            $table->tinyInteger('type')->default(0);
            $table->string('description', 255)->nullable();
            $table->integer('number_of_teacher')->default(1);
            $table->integer('written_work')->unsigned()->default(0);
            $table->integer('performance_task')->unsigned()->default(0);
            $table->integer('exam')->unsigned()->default(0);
            $table->integer('others')->unsigned()->default(0);
            $table->tinyInteger('visible')->default(1);
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
        Schema::dropIfExists('subjects');
    }
}
