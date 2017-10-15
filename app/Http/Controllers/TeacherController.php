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

class TeacherController extends Controller
{

    public function __construct()
    {

    }

	private function getMyStudents()
	{
		// $user = Auth::user();


		$asy = SchoolYear::where('status', 1)->first();

        if(count($asy) == 0) {
            return 'No Active School Year. Report to admin.';
        }

		$students = SubjectAssign::where('teacher_id', Auth::user()->id)
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


    // method use to change password the teacher
    public function teacherPasswordChange()
    {
        $students = $this->getMyStudents();
        return view('teacher.password-change', ['students' => $students]);
    }


    // method use to view teacher profile
    public function viewTeacherProfile()
    {
        $students = $this->getMyStudents();

        return view('teacher.teacher-view-profile', ['students' => $students]);
    }



    // method use to change profile picture of the teacher
    public function teacherChangeProfilePicture()
    {
        $students = $this->getMyStudents();

        return view('teacher.teacher-change-profile-picture', ['students' => $students]);
    }

    public function postTeacherChangeProfilePicture(Request $request)
    {
        // return 'image';
        if( $request->hasFile('image') ) {
           $file = $request->file('image');

           $img = Auth::user()->user_id . time() . "__n" . uniqid() . '.' . $file->getClientOriginalExtension();

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

           return redirect()->route('teacher_change_profile_picture')->with('success', 'Sucessfully Change Your Profile Picture!');

        }
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


    // method use to add written work post
    public function postAddWrittenWork(Request $request)
    {
        $this->validate($request, [
            'total' => 'required'
        ]);

        // total number of score
        $total = $request['total'];

        // subject assignment id
        $assign_id = $request['assign_id'];

        // subject assign
        $assign = SubjectAssign::findorfail($assign_id);

        // school year
        $active_school_year = SchoolYear::whereStatus(1)->first();

        // active quarter
        $active_quarter = Quarter::whereStatus(1)->first();

        // active semester
        $active_sem = Semester::whereStatus(1)->first();

        // section
        $section = Section::findorfail($assign->section_id);

        // subject
        $subject = Subject::findorfail($assign->subject_id);


        // get the last number of  the written work
        // for grade 7 to 10
        if($section->grade_level->id == 1 || $section->grade_level->id == 2 || $section->grade_level->id == 3 || $section->grade_level->id == 4) {
         
            $wwn = WrittenWorkNumber::where('school_year_id', $active_school_year->id)
                                    ->where('quarter_id', $active_quarter->id)
                                    ->where('section_id', $section->id)
                                    ->where('subject_id', $subject->id)
                                    ->first();

        }
        // for grade 11 and 12
        else {
            $wwn = WrittenWorkNumber::where('school_year_id', $active_school_year->id)
                        ->where('semester_id', $active_sem->id)
                        ->where('section_id', $section->id)
                        ->where('subject_id', $subject->id)
                        ->first();
        }


        
        if(count($wwn) == 0) {
            $wwn = new WrittenWorkNumber();
            $wwn->school_year_id = $active_school_year->id;
            if($section->grade_level->id == 5 || $section->grade_level->id == 6) {
                $wwn->semester_id = $active_sem->id;
            }
            else {
                $wwn->quarter_id = $active_quarter->id;
            }
            $wwn->section_id = $section->id;
            $wwn->subject_id = $subject->id;
            $wwn->total = $total;
            $wwn->save();
            
        }

        // increase the number of the written work number
        $wwn->number = $wwn->number + 1;
        $wwn->total = $total;
        $wwn->save();

        // set array for score together with student id of the student
        foreach($section->students as $std) {
            // 
            $score[] = [
                'student_id' => $std->id,
                'student_number' => $std->user->user_id,
                'written_work_number' => $wwn->number,
                'written_work_id' => $wwn->id,
                'score' => $request[$std->user_id],
                'total' => $total

            ];
        }

        // insert score in written work scores table
        DB::table('written_work_scores')->insert($score);


        // user log 
        $log = new UserLog();
        $log->user_id = Auth::user()->id;
        $log->action = 'Added Written Work # ' . $wwn->number . ' on ' . $section->grade_level->name . ' - ' . $section->name;
        $log->save();

        return redirect()->back()->with('success', 'Written Work #' . $wwn->number . ' Sucessfully Saved!');
        
        return 'error in post add written work';
        

    }



    // method to view written work on current
    public function viewWrittenWorkScore($sectionid = null, $subjectid = null, $assignid = null)
    {

        $students = $this->getMyStudents();

        $school_year = SchoolYear::whereStatus(1)->first();
        $quarter = Quarter::whereStatus(1)->first();
        $semester = Semester::whereStatus(1)->first();

        $section = Section::findorfail($sectionid);
        $subject = Subject::findorfail($subjectid);
        $assign = SubjectAssign::findorfail($assignid);

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
            return view('teacher.no-score-written-work', ['students' => $students, 'assign' => $assign]);
        }

    
        // get all scores in the quarter/sem using the id of the written work
        $scores = WrittenWorkScore::where('written_work_id', $ww_number->id)->get();



        return view('teacher.view-written-work-scores', ['scores' => $scores, 'ww_number' => $ww_number, 'students' => $students, 'assign' => $assign]);


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


    // method use to add performance task
    public function postAddPerformanceTask(Request $request)
    {

        $this->validate($request, [
            'total' => 'required'
        ]);

        // total number of score
        $total = $request['total'];

        // subject assignment id
        $assign_id = $request['assign_id'];

        // subject assign
        $assign = SubjectAssign::findorfail($assign_id);

        // school year
        $active_school_year = SchoolYear::whereStatus(1)->first();

        // active quarter
        $active_quarter = Quarter::whereStatus(1)->first();

        // active semester
        $active_sem = Semester::whereStatus(1)->first();

        // section
        $section = Section::findorfail($assign->section_id);

        // subject
        $subject = Subject::findorfail($assign->subject_id);


        // get the last number of  the written work
        // for grade 7 to 10
        if($section->grade_level->id == 1 || $section->grade_level->id == 2 || $section->grade_level->id == 3 || $section->grade_level->id == 4) {
         
            $ptn = PerformanceTaskNumber::where('school_year_id', $active_school_year->id)
                                    ->where('quarter_id', $active_quarter->id)
                                    ->where('section_id', $section->id)
                                    ->where('subject_id', $subject->id)
                                    ->first();

        }
        // for grade 11 and 12
        else {
            $ptn = PerformanceTaskNumber::where('school_year_id', $active_school_year->id)
                        ->where('semester_id', $active_sem->id)
                        ->where('section_id', $section->id)
                        ->where('subject_id', $subject->id)
                        ->first();
        }


        
        if(count($ptn) == 0) {
            $ptn = new PerformanceTaskNumber();
            $ptn->school_year_id = $active_school_year->id;
            if($section->grade_level->id == 5 || $section->grade_level->id == 6) {
                $ptn->semester_id = $active_sem->id;
            }
            else {
                $ptn->quarter_id = $active_quarter->id;
            }
            $ptn->section_id = $section->id;
            $ptn->subject_id = $subject->id;
            $ptn->total = $total;
            $ptn->save();
            
        }

        // increase the number of the written work number
        $ptn->number = $ptn->number + 1;
        $ptn->total = $total;
        $ptn->save();

        // set array for score together with student id of the student
        foreach($section->students as $std) {
            // 
            $score[] = [
                'student_id' => $std->id,
                'student_number' => $std->user->user_id,
                'performance_task_number' => $ptn->number,
                'performance_task_id' => $ptn->id,
                'score' => $request[$std->user_id],
                'total' => $total

            ];
        }

        // insert score in written work scores table
        DB::table('performance_task_scores')->insert($score);


        // user log 
        $log = new UserLog();
        $log->user_id = Auth::user()->id;
        $log->action = 'Added Performance Task # ' . $ptn->number . ' on ' . $section->grade_level->name . ' - ' . $section->name;
        $log->save();

        return redirect()->back()->with('success', 'Performance Task #' . $ptn->number . ' Sucessfully Saved!');
        
        return 'error in post add performance task';

    }


    // method use to view performance task
    public function viwePerformanceTask($sectionid = null, $subjectid = null, $assignid = null)
    {

        $students = $this->getMyStudents();

        $school_year = SchoolYear::whereStatus(1)->first();
        $quarter = Quarter::whereStatus(1)->first();
        $semester = Semester::whereStatus(1)->first();

        $section = Section::findorfail($sectionid);
        $subject = Subject::findorfail($subjectid);
        $assign = SubjectAssign::findorfail($assignid);

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
            return view('teacher.no-score-performance-task', ['students' => $students, 'assign' => $assign]);
        }
    
        // get all scores in the quarter/sem using the id of the written work
        $scores = PerformanceTaskScore::where('performance_task_id', $ptn->id)->get();

    

        return view('teacher.view-performance-task-scores', ['scores' => $scores, 'ptn' => $ptn, 'students' => $students, 'assign' => $assign]);

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


    // method use to add exam score
    public function postAddExam(Request $request)
    {

        $this->validate($request, [
            'total' => 'required'
        ]);

        // total number of score
        $total = $request['total'];

        // subject assignment id
        $assign_id = $request['assign_id'];

        // subject assign
        $assign = SubjectAssign::findorfail($assign_id);

        // school year
        $active_school_year = SchoolYear::whereStatus(1)->first();

        // active quarter
        $active_quarter = Quarter::whereStatus(1)->first();

        // active semester
        $active_sem = Semester::whereStatus(1)->first();

        // section
        $section = Section::findorfail($assign->section_id);

        // subject
        $subject = Subject::findorfail($assign->subject_id);


        // get the last number of  the written work
        // for grade 7 to 10
        if($section->grade_level->id == 1 || $section->grade_level->id == 2 || $section->grade_level->id == 3 || $section->grade_level->id == 4) {
         
            $exam = ExamScoreNumber::where('school_year_id', $active_school_year->id)
                                    ->where('quarter_id', $active_quarter->id)
                                    ->where('section_id', $section->id)
                                    ->where('subject_id', $subject->id)
                                    ->first();

        }
        // for grade 11 and 12
        else {
            $exam = ExamScoreNumber::where('school_year_id', $active_school_year->id)
                        ->where('semester_id', $active_sem->id)
                        ->where('section_id', $section->id)
                        ->where('subject_id', $subject->id)
                        ->first();
        }


        
        if(count($exam) == 0) {
            $exam = new ExamScoreNumber();
            $exam->school_year_id = $active_school_year->id;
            if($section->grade_level->id == 5 || $section->grade_level->id == 6) {
                $exam->semester_id = $active_sem->id;
            }
            else {
                $exam->quarter_id = $active_quarter->id;
            }
            $exam->section_id = $section->id;
            $exam->subject_id = $subject->id;
            $exam->total = $total;
            $exam->save();
            
        }

        // increase the number of the written work number
        $exam->number = $exam->number + 1;
        $exam->total = $total;
        $exam->save();

        // set array for score together with student id of the student
        foreach($section->students as $std) {
            // 
            $score[] = [
                'student_id' => $std->id,
                'student_number' => $std->user->user_id,
                'exam_number' => $exam->number,
                'exam_id' => $exam->id,
                'score' => $request[$std->user_id],
                'total' => $total

            ];
        }

        // insert score in written work scores table
        DB::table('exam_scores')->insert($score);


        // user log 
        $log = new UserLog();
        $log->user_id = Auth::user()->id;
        $log->action = 'Added Exam on ' . $section->grade_level->name . ' - ' . $section->name;
        $log->save();

        return redirect()->back()->with('success', 'Exam Sucessfully Saved!');
        
        return 'error in post add exam ';

    }



    // method use to view exam score
    public function viewExamScore($sectionid = null, $subjectid = null, $assignid = null)
    {

        $students = $this->getMyStudents();

        $school_year = SchoolYear::whereStatus(1)->first();
        $quarter = Quarter::whereStatus(1)->first();
        $semester = Semester::whereStatus(1)->first();

        $section = Section::findorfail($sectionid);
        $subject = Subject::findorfail($subjectid);
        $assign = SubjectAssign::findorfail($assignid);

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
            return view('teacher.no-score-exam', ['students' => $students, 'assign' => $assign]);
        }
    
        // get all scores in the quarter/sem using the id of the written work
        $scores = ExamScore::where('exam_id', $exam->id)->get();

    

        return view('teacher.view-exam-scores', ['scores' => $scores, 'exam' => $exam, 'students' => $students, 'assign' => $assign]);

    }
}
