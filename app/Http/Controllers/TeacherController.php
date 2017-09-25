<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\UserLog;
use App\GradeLevel;
use App\Subject;
use App\Section;
use App\SchoolYear;
use App\Quarter;
use App\Semester;
use App\StudentInfo;
use App\StudentImport;
use App\SubjectAssign;

class TeacherController extends Controller
{

	private function getMyStudents()
	{
		$user = Auth::user();
		$asy = SchoolYear::where('status', 1)->first();

		$students = SubjectAssign::where('teacher_id', $user->id)
								->where('school_year_id', $asy->id)
								->get();

		return $students;
	}

    public function teacherDasboard()
    {

    	$students = $this->getMyStudents();


    	return view('teacher.teacher-dashboard', ['students' => $students]);
    }


    // use to get/view all students in a section
    public function getStudentClassSubject($id = null)
    {

    	$students = $this->getMyStudents();

    	$assign = SubjectAssign::findorfail($id);

    	if($assign->teacher_id != Auth::user()->id) {
    		abort(404);
    	}

    	$all_students = StudentInfo::where('section', $assign->section_id)->get();

    	return view('teacher.students-on-subject', ['students' => $students, 'all_students' => $all_students, 'assign' => $assign]);
    }
}
