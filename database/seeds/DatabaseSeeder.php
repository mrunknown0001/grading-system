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
            'user_id' => '2017-0001',
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



        // Add Grade Levels
        // id = 1
        DB::table('grade_levels')->insert([
            'name' => 'Grade 7',
            'description' => 'Grade 7...'
            ]);
        
        // id = 2
        DB::table('grade_levels')->insert([
            'name' => 'Grade 8',
            'description' => 'Grade 8...'
            ]);
        
        // id = 3
        DB::table('grade_levels')->insert([
            'name' => 'Grade 9',
            'description' => 'Grade 9...'
            ]);
        
        // id = 4
        DB::table('grade_levels')->insert([
            'name' => 'Grade 10',
            'description' => 'Grade 10...'
            ]);
        
        // id = 5
        DB::table('grade_levels')->insert([
            'name' => 'Grade 11',
            'description' => 'Junior High School'
            ]);
        
        // id = 6
        DB::table('grade_levels')->insert([
            'name' => 'Grade 12',
            'description' => 'Senior High School'
            ]);


        // fix subjects for grade 7
         DB::table('subjects')->insert([
                [
                    'level' => '1',
                    'title' => 'Filipino',
                    'type' => '1',
                    'description' => 'Grade 7 - Filipino',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                   'level' => '1',
                    'title' => 'Araling Panlipunan',
                    'type' => '1',
                    'description' => 'Grade 7 - Araling Panlipunan',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '1',
                    'title' => 'English',
                    'type' => '1',
                    'description' => 'Grade 7 - English',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '1',
                    'title' => 'Edukasyon sa Pagpapakatao',
                    'type' => '1',
                    'description' => 'Grade 7 - Edukasyon sa Pagpapakatao',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '1',
                    'title' => 'Christian Living',
                    'type' => '1',
                    'description' => 'Grade 7 - Christian Living',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '1',
                    'title' => 'Science',
                    'type' => '1',
                    'description' => 'Grade 7 - Science',
                    'number_of_teacher' => 1,
                    'performance_task' => 40,
                    'written_work' => 40,
                    'exam' => 20
                ],
                [
                    'level' => '1',
                    'title' => 'Mathematics',
                    'type' => '1',
                    'description' => 'Grade 7 - Mathematics',
                    'number_of_teacher' => 1,
                    'performance_task' => 40,
                    'written_work' => 40,
                    'exam' => 20
                ],
                [
                    'level' => '1',
                    'title' => 'MAPEH',
                    'type' => '1',
                    'description' => 'Grade 7 - Music, Arts, Physical Education and Health',
                    'number_of_teacher' => 3,
                    'performance_task' => 20,
                    'written_work' => 60,
                    'exam' => 20
                ],
                [
                    'level' => '1',
                    'title' => 'TLE',
                    'type' => '1',
                    'description' => 'Grade 7 - Technology and Livelihood Education',
                    'number_of_teacher' => 1,
                    'performance_task' => 20,
                    'written_work' => 60,
                    'exam' => 20
                ],
                [
                    'level' => '1',
                    'title' => 'ICT',
                    'type' => '1',
                    'description' => 'Grade 7 - Information Communication Technology',
                    'number_of_teacher' => 1,
                    'performance_task' => 20,
                    'written_work' => 60,
                    'exam' => 20
                ]
            ]);



        // fix subjects for grade 8
         DB::table('subjects')->insert([
                [
                    'level' => '2',
                    'title' => 'Filipino',
                    'type' => '1',
                    'description' => 'Grade 8 - Filipino',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                   'level' => '2',
                    'title' => 'Araling Panlipunan',
                    'type' => '1',
                    'description' => 'Grade 8 - Araling Panlipunan',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '2',
                    'title' => 'English',
                    'type' => '1',
                    'description' => 'Grade 8 - English',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '2',
                    'title' => 'Edukasyon sa Pagpapakatao',
                    'type' => '1',
                    'description' => 'Grade 8 - Edukasyon sa Pagpapakatao',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '2',
                    'title' => 'Christian Living',
                    'type' => '1',
                    'description' => 'Grade 8 - Christian Living',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '2',
                    'title' => 'Science',
                    'type' => '1',
                    'description' => 'Grade 8 - Science',
                    'number_of_teacher' => 1,
                    'performance_task' => 40,
                    'written_work' => 40,
                    'exam' => 20
                ],
                [
                    'level' => '2',
                    'title' => 'Mathematics',
                    'type' => '1',
                    'description' => 'Grade 8 - Mathematics',
                    'number_of_teacher' => 1,
                    'performance_task' => 40,
                    'written_work' => 40,
                    'exam' => 20
                ],
                [
                    'level' => '2',
                    'title' => 'MAPEH',
                    'type' => '1',
                    'description' => 'Grade 8 - Music, Arts, Physical Education and Health',
                    'number_of_teacher' => 3,
                    'performance_task' => 20,
                    'written_work' => 60,
                    'exam' => 20
                ],
                [
                    'level' => '2',
                    'title' => 'TLE',
                    'type' => '1',
                    'description' => 'Grade 8 - Technology and Livelihood Education',
                    'number_of_teacher' => 1,
                    'performance_task' => 20,
                    'written_work' => 60,
                    'exam' => 20
                ],
                [
                    'level' => '2',
                    'title' => 'ICT',
                    'type' => '1',
                    'description' => 'Grade 8 - Information Communication Technology',
                    'number_of_teacher' => 1,
                    'performance_task' => 20,
                    'written_work' => 60,
                    'exam' => 20
                ]
            ]);


        // subject for grade 9
         DB::table('subjects')->insert([
                [
                    'level' => '3',
                    'title' => 'Filipino',
                    'type' => '1',
                    'description' => 'Grade 9 - Filipino',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                   'level' => '3',
                    'title' => 'Araling Panlipunan',
                    'type' => '1',
                    'description' => 'Grade 9 - Araling Panlipunan',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '3',
                    'title' => 'English',
                    'type' => '1',
                    'description' => 'Grade 9 - English',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '3',
                    'title' => 'Edukasyon sa Pagpapakatao',
                    'type' => '1',
                    'description' => 'Grade 9 - Edukasyon sa Pagpapakatao',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '3',
                    'title' => 'Christian Living',
                    'type' => '1',
                    'description' => 'Grade 9 - Christian Living',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '3',
                    'title' => 'Science',
                    'type' => '1',
                    'description' => 'Grade 9 - Science',
                    'number_of_teacher' => 1,
                    'performance_task' => 40,
                    'written_work' => 40,
                    'exam' => 20
                ],
                [
                    'level' => '3',
                    'title' => 'Mathematics',
                    'type' => '1',
                    'description' => 'Grade 9 - Mathematics',
                    'number_of_teacher' => 1,
                    'performance_task' => 40,
                    'written_work' => 40,
                    'exam' => 20
                ],
                [
                    'level' => '3',
                    'title' => 'MAPEH',
                    'type' => '1',
                    'description' => 'Grade 9 - Music, Arts, Physical Education and Health',
                    'number_of_teacher' => 3,
                    'performance_task' => 20,
                    'written_work' => 60,
                    'exam' => 20
                ],
                [
                    'level' => '3',
                    'title' => 'TLE',
                    'type' => '1',
                    'description' => 'Grade 9 - Technology and Livelihood Education',
                    'number_of_teacher' => 1,
                    'performance_task' => 20,
                    'written_work' => 60,
                    'exam' => 20
                ],
                [
                    'level' => '3',
                    'title' => 'ICT',
                    'type' => '1',
                    'description' => 'Grade 9 - Information Communication Technology',
                    'number_of_teacher' => 1,
                    'performance_task' => 20,
                    'written_work' => 60,
                    'exam' => 20
                ]
            ]);


        // subject for grade 10
         DB::table('subjects')->insert([
                [
                    'level' => '4',
                    'title' => 'Filipino',
                    'type' => '1',
                    'description' => 'Grade 10 - Filipino',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                   'level' => '4',
                    'title' => 'Araling Panlipunan',
                    'type' => '1',
                    'description' => 'Grade 10 - Araling Panlipunan',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '4',
                    'title' => 'English',
                    'type' => '1',
                    'description' => 'Grade 10 - English',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '4',
                    'title' => 'Edukasyon sa Pagpapakatao',
                    'type' => '1',
                    'description' => 'Grade 10 - Edukasyon sa Pagpapakatao',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '4',
                    'title' => 'Christian Living',
                    'type' => '1',
                    'description' => 'Grade 10 - Christian Living',
                    'number_of_teacher' => 1,
                    'performance_task' => 30,
                    'written_work' => 50,
                    'exam' => 20
                ],
                [
                    'level' => '4',
                    'title' => 'Science',
                    'type' => '1',
                    'description' => 'Grade 10 - Science',
                    'number_of_teacher' => 1,
                    'performance_task' => 40,
                    'written_work' => 40,
                    'exam' => 20
                ],
                [
                    'level' => '4',
                    'title' => 'Mathematics',
                    'type' => '1',
                    'description' => 'Grade 10 - Mathematics',
                    'number_of_teacher' => 1,
                    'performance_task' => 40,
                    'written_work' => 40,
                    'exam' => 20
                ],
                [
                    'level' => '4',
                    'title' => 'MAPEH',
                    'type' => '1',
                    'description' => 'Grade 10 - Music, Arts, Physical Education and Health',
                    'number_of_teacher' => 3,
                    'performance_task' => 20,
                    'written_work' => 60,
                    'exam' => 20
                ],
                [
                    'level' => '4',
                    'title' => 'TLE',
                    'type' => '1',
                    'description' => 'Grade 10 - Technology and Livelihood Education',
                    'number_of_teacher' => 1,
                    'performance_task' => 20,
                    'written_work' => 60,
                    'exam' => 20
                ],
                [
                    'level' => '4',
                    'title' => 'ICT',
                    'type' => '1',
                    'description' => 'Grade 10 - Information Communication Technology',
                    'number_of_teacher' => 1,
                    'performance_task' => 20,
                    'written_work' => 60,
                    'exam' => 20
                ]
            ]);


         DB::table('quarters')->insert([
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ],
                [
                    'name' => 'third'
                ],
                [
                    'name' => 'forth'
                ]
            ]);

         DB::table('semesters')->insert([
                [
                    'name' => 'first'
                ],
                [
                    'name' => 'second'
                ]
            ]);


    }
}
