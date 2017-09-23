<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function teacherDasboard()
    {
    	return view('teacher.teacher-dashboard');
    }
}
