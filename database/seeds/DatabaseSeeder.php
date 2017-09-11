<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

    	/*
    	 * Admin User
    	 */
    	DB::table('users')->insert([
    		'user_id' => 'admin',
    		'firstname' => 'Admin',
    		'lastname' => 'Admin',
    		'email' => 'admin@admin.com',
    		'mobile' => '09111111111',
    		'password' => bcrypt('admin'),
    		'privilege' =>'1' // Admin Privillege
    		]);

        // Teacher
        DB::table('users')->insert([
            'user_id' => '0001-1111',
            'firstname' => 'Teacher',
            'lastname' => 'Adviser',
            'email' => 'teacher@admin.com',
            'mobile' => '09222222222',
            'password' => bcrypt('concsfaculty2017'),  // default password for teacher is concsfaculty2017
            'privilege' =>'2',  // Teacher Privellege
            'birthday' => date('Y-m-d', strtotime('10/1/1992')),
            'gender' => 'Male',
            'address' => 'Tarlac'
            ]);
    }
}
