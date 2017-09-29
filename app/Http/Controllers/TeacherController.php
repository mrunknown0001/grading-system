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
        // for menu in teacher
    	$students = $this->getMyStudents();


    	return view('teacher.teacher-dashboard', ['students' => $students]);
    }


    // use to get/view all students in a section
    public function getStudentClassSubject($id = null)
    {
        // for menu in teacher
    	$students = $this->getMyStudents();

    	$assign = SubjectAssign::findorfail($id);

    	if($assign->teacher_id != Auth::user()->id) {
    		abort(404);
    	}

    	$all_students = StudentInfo::where('section', $assign->section_id)->get();

    	return view('teacher.students-on-subject', ['students' => $students, 'all_students' => $all_students, 'assign' => $assign]);
    }


    // method use to add written work score
    public function addWrittenWorkScore($section_id = null, $subject_id = null, $assign_id = null)
    {

        // check if there is selected sem & quarter
        $quarter = Quarter::whereStatus(1)->first();
        $sem = Semester::whereStatus(1)->first();

        // for menu in teacher
        $students = $this->getMyStudents();

        $section = Section::findorfail($section_id);
        $subject = Subject::findorfail($subject_id);
        $assign = SubjectAssign::findorfail($assign_id);

        if(Auth::user()->id != $assign->teacher_id) {
            abort(404);
        }


        // active school year
        $active_school_year = SchoolYear::whereStatus(1)->first();

        // select students
        $students_on_subject = StudentInfo::whereSection($section->id)
                                ->whereSchoolYear($active_school_year->id)
                                ->get();

        // return $students;
        
        return view('teacher.add-written-work', ['students' => $students, 'section' => $section, 'subject' => $subject, 'assign' => $assign]);
    }


    // method use to add performance task score
    public function addPerformanceTask($section_id = null, $subject_id = null, $assign_id = null)
    {

        // check if there is selected sem & quarter
        $quarter = Quarter::whereStatus(1)->first();
        $sem = Semester::whereStatus(1)->first();

        // for menu in teacher
        $students = $this->getMyStudents();

        $section = Section::findorfail($section_id);
        $subject = Subject::findorfail($subject_id);
        $assign = SubjectAssign::findorfail($assign_id);

        if(Auth::user()->id != $assign->teacher_id) {
            abort(404);
        }


        // active school year
        $active_school_year = SchoolYear::whereStatus(1)->first();

        // select students
        $students_on_subject = StudentInfo::whereSection($section->id)
                                ->whereSchoolYear($active_school_year->id)
                                ->get();

        // return $students;
        
        return view('teacher.add-performance-task', ['students' => $students, 'section' => $section, 'subject' => $subject, 'assign' => $assign]); 
    }


    // method use to add exam score
    public function addExam($section_id = null, $subject_id = null, $assign_id = null)
    {

        // check if there is selected sem & quarter
        $quarter = Quarter::whereStatus(1)->first();
        $sem = Semester::whereStatus(1)->first();

        // for menu in teacher
        $students = $this->getMyStudents();

        $section = Section::findorfail($section_id);
        $subject = Subject::findorfail($subject_id);
        $assign = SubjectAssign::findorfail($assign_id);

        if(Auth::user()->id != $assign->teacher_id) {
            abort(404);
        }


        // active school year
        $active_school_year = SchoolYear::whereStatus(1)->first();

        // select students
        $students_on_subject = StudentInfo::whereSection($section->id)
                                ->whereSchoolYear($active_school_year->id)
                                ->get();

        // return $students;
        
        return view('teacher.add-exam', ['students' => $students, 'section' => $section, 'subject' => $subject, 'assign' => $assign]); 
    }
}
