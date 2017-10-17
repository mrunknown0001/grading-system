<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use DB;

use Illuminate\Http\UploadedFile;
use Image;

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
use App\PerformanceTaskNumber;
use App\ExamScoreNumber;
use App\Avatar;

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



    // method use to view performance task of the student
    public function viwePerformanceTask($year_id = null, $section = null, $subject = null, $student_number)
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

            $ptn = PerformanceTaskNumber::where('school_year_id', $school_year->id)
                                        ->where('quarter_id', $quarter->id)
                                        ->where('section_id', $section->id)
                                        ->where('subject_id', $subject->id)
                                        ->first();
        }
        else {
            $ptn = PerformanceTaskNumber::where('school_year_id', $school_year->id)
                                        ->where('semester_id', $semester->id)
                                        ->where('section_id', $section->id)
                                        ->where('subject_id', $subject->id)
                                        ->first();
        }


        if(count($ptn) == 0) {
            return view('student.includes.no-scores-for-the-subject');
        }

        // get all scores in the quarter/sem using the id of the written work
        $scores = PerformanceTaskScore::where('performance_task_id', $ptn->id)->where('student_number', Auth::user()->user_id)->get();

        if(count($scores) == 0) {
            return 'No Scores Yet for this Subject';
        }

        return view('student.student-view-performance-task-score', ['subject' => $subject, 'section' => $section, 'ptn' => $ptn, 'scores' => $scores]);
    }



    // method use to view exam
    public function viewExamScore($year_id = null, $section = null, $subject = null, $student_number)
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

            $exam = ExamScoreNumber::where('school_year_id', $school_year->id)
                                        ->where('quarter_id', $quarter->id)
                                        ->where('section_id', $section->id)
                                        ->where('subject_id', $subject->id)
                                        ->first();
        }
        else {
            $exam = ExamScoreNumber::where('school_year_id', $school_year->id)
                                        ->where('semester_id', $semester->id)
                                        ->where('section_id', $section->id)
                                        ->where('subject_id', $subject->id)
                                        ->first();
        }


        if(count($exam) == 0) {
            return view('student.includes.no-scores-for-the-subject');
        }

        // get all scores in the quarter/sem using the id of the written work
        $scores = ExamScore::where('exam_id', $exam->id)->where('student_number', Auth::user()->user_id)->get();

        if(count($scores) == 0) {
            return 'No Scores Yet for this Subject';
        }

        return view('student.student-view-exam-score', ['subject' => $subject, 'section' => $section, 'exam' => $exam, 'scores' => $scores]);
    }



    // method use to view grades of the student
    public function studentViewGrades()
    {
        return view('student.student-view-grades');
    }



    // method to view profile of students
    public function viewProfile()
    {
        return view('student.student-view-profile');
    }




    // method use to view change password
    public function studentChangePassword()
    {
        return view('student.student-change-password');
    }





    // method use to change profile picture of students
    public function studentProfilePictureChange()
    {
        return view('student.student-change-profile-picture');
    }



    // method use to change password of the student
    public function postProfilePictureChange(Request $request)
    {
        // return 'image';
        if( $request->hasFile('image') ) {
           $file = $request->file('image');

           $img = Auth::user()->user_id . '_' . time() . "__n" . uniqid() . '.' . $file->getClientOriginalExtension();

           // return $img;
           Image::make($file)->save(public_path('/uploads/profile/' . $img))->resize(500, 500);;


           // check if the user already upload an image
           $avatar = Avatar::whereUserId(Auth::user()->id)->first();

            if(count($avatar) == 0) {
               $avatar = new Avatar();
               $avatar->user_id = Auth::user()->id;
               $avatar->name = $img;
               $avatar->save();
            }
            else {
                $avatar->name = $img;
                $avatar->save();
            }


            // user log
            $log = new UserLog();
            $log->user_id = Auth::user()->id;
            $log->action = "Teacher Change Profile Picture";
            $log->save();

           return redirect()->route('student_profile_picture_change')->with('success', 'Sucessfully Change Your Profile Picture!');

        }
    }

}
