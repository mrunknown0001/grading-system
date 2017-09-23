<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 15)->unique(); // admin for admin, ID number for teachers, Student Number for students
            $table->string('firstname', 100)->nullable();
            $table->string('lastname', 100)->nullable();
            $table->string('address')->nullable();
            $table->string('gender', 6)->nullable();
            $table->date('birthday')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('mobile', 12)->nullable();
            $table->string('password');
            $table->tinyInteger('privilege');
            $table->tinyInteger('status')->default(1); // for students: make is 0 if the school year is finished
            $table->integer('school_year')->unsigned()->nullable(); // for students: what school year was added
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
