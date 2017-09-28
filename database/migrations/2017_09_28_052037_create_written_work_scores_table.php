<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWrittenWorkScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('written_work_scores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('school_year_id');
            $table->integer('section_id');
            $table->integer('subject_id');
            $table->integer('student_id');
            $table->integer('ww_number');
            $table->integer('score');
            $table->integer('total');
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
        Schema::dropIfExists('written_work_scores');
    }
}
