<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use DB;

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
use App\WrittenWorkScore;
use App\PerformanceTaskScore;
use App\ExamScore;
use App\WrittenWorkNumber;

class StudentController extends Controller
{
    // student dashboard
    public function getStudentDashboard()
    {
    	return view('student.student-dashboard');
    }



    // method to view subject
    public function studentSubjectView($id = null)
    {
        $subject = Subject::findorfail($id);

        $std_info = StudentInfo::where('user_id', Auth::user()->user_id)->first();
        $year = SchoolYear::whereStatus(1)->first();

        return view('student.student-subject-view', ['subject' => $subject, 'info' => $std_info, 'year' => $year]);
    }



    // method use to view written works of the students
    public function viewWrittenWorkScores($year_id = null, $section = null, $subject = null, $student_number)
    {
    	// return 'view';
        $subject = Subject::findorfail($subject);
        $section = Section::findorfail($section);

        $school_year = SchoolYear::findorfail($year_id);
        $quarter = Quarter::whereStatus(1)->first();
        $semester = Semester::whereStatus(1)->first();

        // check how many written works has taken
        // check also if junior or senior high
        if($section->grade_level->id == 1 || $section->grade_level->id == 2 || $section->grade_level->id == 3 || $section->grade_level->id == 4) {

            $ww_number = WrittenWorkNumber::where('school_year_id', $school_year->id)
                                        ->where('quarter_id', $quarter->id)
                                        ->where('section_id', $section->id)
                                        ->where('subject_id', $subject->id)
                                        ->first();
        }
        else {
            $ww_number = WrittenWorkNumber::where('school_year_id', $school_year->id)
                                        ->where('semester_id', $semester->id)
                                        ->where('section_id', $section->id)
                                        ->where('subject_id', $subject->id)
                                        ->first();
        }


        if(count($ww_number) == 0) {
            return view('student.includes.no-scores-for-the-subject');
        }

        // get all scores in the quarter/sem using the id of the written work
        $scores = WrittenWorkScore::where('written_work_id', $ww_number->id)->where('student_number', Auth::user()->user_id)->get();

        if(count($scores) == 0) {
            return 'No Scores Yet for this Subject';
        }

        return view('student.student-view-written-work-score', ['subject' => $subject, 'section' => $section, 'ww_number' => $ww_number, 'scores' => $scores]);
    }
}
