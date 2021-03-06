<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinalGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_grades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('student_id', 15); // student number of the students
            $table->integer('school_year_id');
            $table->integer('grade_level_id');
            $table->integer('subject_id');
            $table->integer('quarter_id')->nullable();
            $table->integer('semester_id')->nullalbe();          
            $table->integer('grade')->nullable();
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
        Schema::dropIfExists('final_grades');
    }
}
