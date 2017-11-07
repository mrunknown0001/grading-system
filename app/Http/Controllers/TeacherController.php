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
use App\Message;

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
            'total' => 'required|numeric'
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
            $wwn->number = 1;
            $wwn->save();
            
        }
        else {

            // increase the number of the written work number
            $wwn->number = $wwn->number + 1;
            $wwn->total = $wwn->total + $total;
            $wwn->save();
        }
        // set array for score together with student id of the student
        foreach($section->students as $std) {

            if($total < $request[$std->user_id]) {
                $wwn->number = $wwn->number -  1;
                $wwn->total = $wwn->total - $total;
                $wwn->save();

                return redirect()->back()->with('error_msg', 'The Scores Must NOT Be Greater Than The Total.');
            }

            if($section->grade_level->id == 5 || $section->grade_level->id == 6) {

                $score[] = [
                    'school_year_id' => $active_school_year->id,
                    'semester_id' => $active_sem->id,
                    'section_id' => $section->id,
                    'subject_id' => $subject->id,
                    'student_id' => $std->id,
                    'student_number' => $std->user->user_id,
                    'written_work_number' => $wwn->number,
                    'written_work_id' => $wwn->id,
                    'score' => $request[$std->user_id],
                    'total' => $total

                ];
            }
            else {

                $score[] = [
                    'school_year_id' => $active_school_year->id,
                    'quarter_id' => $active_quarter->id,
                    'section_id' => $section->id,
                    'subject_id' => $subject->id,
                    'student_id' => $std->id,
                    'student_number' => $std->user->user_id,
                    'written_work_number' => $wwn->number,
                    'written_work_id' => $wwn->id,
                    'score' => $request[$std->user_id],
                    'total' => $total

                ];
            }
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

        if($quarter == null || $semester == null) {
            return 'system is initializing by admin';
        }

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



    // method to update written work score
    public function updateWrittenWorkScore($id = null, $user_id = null, $assignid = null)
    {
        $score = WrittenWorkScore::findorfail($id);
        $student = User::where('user_id', $user_id)->first();

        return view('teacher.teacher-update-written-work-score', ['students' => $this->getMyStudents(), 'score' => $score, 'student' => $student, 'assignid' => $assignid]);
    }



    // method use to updat the score of the students
    public function postUpdateWrittenWorkScore(Request $request)
    {
        $this->validate($request, [
            'score' => 'required|numeric'
        ]);

        $score = $request['score'];
        $total = $request['total'];
        $assignid = $request['assignid'];

        $wws = WrittenWorkScore::findorfail($request['id']);
        $wws->score = $score;
        $wws->save();

        // log
        $log = new UserLog();
        $log->user_id = Auth::user()->id;
        $log->action = 'Update Written Work Score';
        $log->save();


        return redirect()->route('view_written_work_score', ['sectionid' => $wws->section_id, 'subjectid' => $wws->subject_id, 'assignid' => $assignid])->with('success', 'Score Updated!');
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
            'total' => 'required|numeric'
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
            $ptn->number = 1;
            $ptn->save();
            
        }
        else {
            // increase the number of the written work number
            $ptn->number = $ptn->number + 1;
            $ptn->total = $total;
            $ptn->save();

        }



        // set array for score together with student id of the student
        foreach($section->students as $std) {
            
            // check if theres an error preventing having an higher score than the total
            if($total < $request[$std->user_id]) {
                $ptn->number = $ptn->number -  1;
                $ptn->total = $ptn->total - $total;
                $ptn->save();

                return redirect()->back()->with('error_msg', 'The Scores Must NOT Be Greater Than The Total.');
            }

            if($section->grade_level->id == 5 || $section->grade_level->id == 6) {

                $score[] = [
                    'school_year_id' => $active_school_year->id,
                    'semester_id' => $active_sem->id,
                    'section_id' => $section->id,
                    'subject_id' => $subject->id,
                    'student_id' => $std->id,
                    'student_number' => $std->user->user_id,
                    'performance_task_number' => $ptn->number,
                    'performance_task_id' => $ptn->id,
                    'score' => $request[$std->user_id],
                    'total' => $total
 
                ];
            }
            else {

                $score[] = [
                    'school_year_id' => $active_school_year->id,
                    'quarter_id' => $active_quarter->id,
                    'section_id' => $section->id,
                    'subject_id' => $subject->id,
                    'student_id' => $std->id,
                    'student_number' => $std->user->user_id,
                    'performance_task_number' => $ptn->number,
                    'performance_task_id' => $ptn->id,
                    'score' => $request[$std->user_id],
                    'total' => $total

                ];
            }


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



    // method use to viwe update performance task
    public function updatePerformanceTaskScore($id = null, $user_id = null, $assignid = null)
    {
        $score = PerformanceTaskScore::findorfail($id);
        $student = User::where('user_id', $user_id)->first();

        return view('teacher.teacher-update-performance-task-score', ['students' => $this->getMyStudents(), 'score' => $score, 'student' => $student, 'assignid' => $assignid]);
    }



    // method post update performance task
    public function postUpdatePerformanceTaskScore(Request $request)
    {
        $this->validate($request, [
            'score' => 'required|numeric'
        ]);

        $score = $request['score'];
        $total = $request['total'];
        $assignid = $request['assignid'];

        $pts = PerformanceTaskScore::findorfail($request['id']);
        $pts->score = $score;
        $pts->save();

        // log
        $log = new UserLog();
        $log->user_id = Auth::user()->id;
        $log->action = 'Update Performance Task Score';
        $log->save();


        return redirect()->route('view_performance_task_score', ['sectionid' => $pts->section_id, 'subjectid' => $pts->subject_id, 'assignid' => $assignid])->with('success', 'Score Updated!');
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
            'total' => 'required|numeric'
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
            $exam->number = 1;
            $exam->save();
            
        }
        else {
            // increase the number of the written work number
            $exam->number = $exam->number + 1;
            $exam->total = $total;
            $exam->save();
        }
        // set array for score together with student id of the student
        foreach($section->students as $std) {

            if($total < $request[$std->user_id]) {
                $exam->number = $exam->number -  1;
                $exam->total = $exam->total - $total;
                $exam->save();

                return redirect()->back()->with('error_msg', 'The Scores Must NOT Be Greater Than The Total.');
            }

            if($section->grade_level->id == 5 || $section->grade_level->id == 6) {

                $score[] = [
                    'school_year_id' => $active_school_year->id,
                    'semester_id' => $active_sem->id,
                    'section_id' => $section->id,
                    'subject_id' => $subject->id,
                    'student_id' => $std->id,
                    'student_number' => $std->user->user_id,
                    'exam_number' => $exam->number,
                    'exam_id' => $exam->id,
                    'score' => $request[$std->user_id],
                    'total' => $total
 
                ];
            }
            else {

                $score[] = [
                    'school_year_id' => $active_school_year->id,
                    'quarter_id' => $active_quarter->id,
                    'section_id' => $section->id,
                    'subject_id' => $subject->id,
                    'student_id' => $std->id,
                    'student_number' => $std->user->user_id,
                    'exam_number' => $exam->number,
                    'exam_id' => $exam->id,
                    'score' => $request[$std->user_id],
                    'total' => $total

                ];

            }
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



    // method use to view exam score
    public function viewSubjectGrades($sectionid = null, $subjectid = null, $assignid = null)
    {

        $asy = SchoolYear::whereStatus(1)->first();
        $first_quarter = Quarter::findorfail(1);
        $second_quarter = Quarter::findorfail(2);
        $third_quarter = Quarter::findorfail(3);
        $fourth_quarter = Quarter::findorfail(4);

        $first_sem = Semester::findorfail(1);
        $second_sem = Semester::findorfail(2);


        $section = Section::find($sectionid);
        $sub = Subject::find($subjectid);


        if($section->level <= 4) {

            // for first quarter
            if($first_quarter->finish == 1) {
               $fqg = [];
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($section->students as $std) {
                    // total subject total in first quarter\
                    $ww_scores_q1[] = [
                                    'student_id' => $std->user_id,
                                    'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 1)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 1)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                    ];


                    $pt_scores_q1[] = [
                                    'student_id' => $std->user_id,
                                    'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 1)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 1)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                ];

                    $exam_scores_q1[] = [
                                    'student_id' => $std->user_id,
                                    'score' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 1)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 1)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                ];

                }



                foreach($section->students as $std) {
                    $ww_percentage = 0;
                    $pt_percentage = 0;
                    $exam_percentage = 0;
                    $grade = 0;

                    foreach ($ww_scores_q1 as $ws) {
                        if($std->user_id == $ws['student_id'] && $ws['score'] != 0) {
                            $ww_percentage = (($ws['score']/$ws['total']) * ($sub->written_work/100)) * 100;
                        }
                    }


                    foreach ($pt_scores_q1 as $pt) {
                        if($std->user_id == $pt['student_id'] && $pt['score'] != 0) {
                            $pt_percentage = (($pt['score']/$pt['total']) * ($sub->performance_task/100)) * 100;
                        }
                    }


                    foreach ($exam_scores_q1 as $es) {
                        if($std->user_id == $es['student_id'] && $es['score'] != 0) {
                            $exam_percentage = (($es['score']/$es['total']) * ($sub->exam/100)) * 100;
                        }
                    }



                    $grade =  $ww_percentage + $pt_percentage + $exam_percentage;

                    $fqg[] = [
                        'student_id' => $std->user_id,
                        'grade' => $grade
                        ];
                }

            } // end of first quarter


            // for second quarter
            if($second_quarter->finish == 1) {
                $sqg = [];
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($section->students as $std) {
                    // total subject total in first quarter\
                    $ww_scores_q2[] = [
                                    'student_id' => $std->user_id,
                                    'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 2)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 2)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                    ];


                    $pt_scores_q2[] = [
                                    'student_id' => $std->user_id,
                                    'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 2)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 2)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                ];

                    $exam_scores_q2[] = [
                                    'student_id' => $std->user_id,
                                    'score' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 2)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 2)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                ];

                }



                foreach($section->students as $std) {
                    $ww_percentage = 0;
                    $pt_percentage = 0;
                    $exam_percentage = 0;

                    foreach ($ww_scores_q2 as $ws) {
                        if($std->user_id == $ws['student_id'] && $ws['score'] != 0) {
                            $ww_percentage = (($ws['score']/$ws['total']) * ($sub->written_work/100)) * 100;
                        }
                    }


                    foreach ($pt_scores_q2 as $pt) {
                        if($std->user_id == $pt['student_id'] && $pt['score'] != 0) {
                            $pt_percentage = (($pt['score']/$pt['total']) * ($sub->performance_task/100)) * 100;
                        }
                    }


                    foreach ($exam_scores_q2 as $es) {
                        if($std->user_id == $es['student_id'] && $es['score'] != 0) {
                            $exam_percentage = (($es['score']/$es['total']) * ($sub->exam/100)) * 100;
                        }
                    }



                    $grade =  $ww_percentage + $pt_percentage + $exam_percentage;

                    $sqg[] = [
                        'student_id' => $std->user_id,
                        'grade' => $grade
                        ];
                }

            } // end of second quarter


            // for third quarter
            if($third_quarter->finish == 1) {
                $tqg = [];
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($section->students as $std) {
                    // total subject total in first quarter\
                    $ww_scores_q3[] = [
                                    'student_id' => $std->user_id,
                                    'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 3)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 3)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                    ];


                    $pt_scores_q3[] = [
                                    'student_id' => $std->user_id,
                                    'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 3)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 3)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                ];

                    $exam_scores_q3[] = [
                                    'student_id' => $std->user_id,
                                    'score' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 3)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 3)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                ];

                }



                foreach($section->students as $std) {
                    $ww_percentage = 0;
                    $pt_percentage = 0;
                    $exam_percentage = 0;

                    foreach ($ww_scores_q3 as $ws) {
                        if($std->user_id == $ws['student_id'] && $ws['score'] != 0) {
                            $ww_percentage = (($ws['score']/$ws['total']) * ($sub->written_work/100)) * 100;
                        }
                    }


                    foreach ($pt_scores_q3 as $pt) {
                        if($std->user_id == $pt['student_id'] && $pt['score'] != 0) {
                            $pt_percentage = (($pt['score']/$pt['total']) * ($sub->performance_task/100)) * 100;
                        }
                    }


                    foreach ($exam_scores_q3 as $es) {
                        if($std->user_id == $es['student_id'] && $es['score'] != 0) {
                            $exam_percentage = (($es['score']/$es['total']) * ($sub->exam/100)) * 100;
                        }
                    }



                    $grade =  $ww_percentage + $pt_percentage + $exam_percentage;

                    $tqg[] = [
                        'student_id' => $std->user_id,
                        'grade' => $grade
                        ];
                }

            } // end of third quarter



            // for fourth quarter
            if($fourth_quarter->status == 1) {
                $foqg = [];
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($section->students as $std) {
                    // total subject total in first quarter\
                    $ww_scores_q4[] = [
                                    'student_id' => $std->user_id,
                                    'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 4)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 4)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                    ];


                    $pt_scores_q4[] = [
                                    'student_id' => $std->user_id,
                                    'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 4)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 4)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                ];

                    $exam_scores_q4[] = [
                                    'student_id' => $std->user_id,
                                    'score' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 4)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 4)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                ];

                }



                foreach($section->students as $std) {
                    $ww_percentage = 0;
                    $pt_percentage = 0;
                    $exam_percentage = 0;

                    foreach ($ww_scores_q4 as $ws) {
                        if($std->user_id == $ws['student_id'] && $ws['score'] != 0) {
                            $ww_percentage = (($ws['score']/$ws['total']) * ($sub->written_work/100)) * 100;
                        }
                    }


                    foreach ($pt_scores_q4 as $pt) {
                        if($std->user_id == $pt['student_id'] && $pt['score'] != 0) {
                            $pt_percentage = (($pt['score']/$pt['total']) * ($sub->performance_task/100)) * 100;
                        }
                    }


                    foreach ($exam_scores_q4 as $es) {
                        if($std->user_id == $es['student_id'] && $es['score'] != 0) {
                            $exam_percentage = (($es['score']/$es['total']) * ($sub->exam/100)) * 100;
                        }
                    }



                    $grade =  $ww_percentage + $pt_percentage + $exam_percentage;

                    $foqg[] = [
                        'student_id' => $std->user_id,
                        'grade' => $grade
                        ];
                }

            } // end of fourht quarter



            return view('teacher.student-subject-grade', ['section' => $section, 'subject' => $sub, 'students' => $this->getMyStudents(), 'fqg' => $fqg, 'sqg' => $sqg, 'tqg' => $tqg, 'foqg' => $foqg, 'first_quarter' => $first_quarter, 'second_quarter' => $second_quarter, 'third_quarter' => $third_quarter, 'fourth_quarter' => $fourth_quarter]);

        }
        else {

            $fsg = [];
            $ssg = [];

            // for first sem
            if($first_sem->finish == 1) {
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($section->students as $std) {
                    // total subject total in first quarter\
                    $ww_scores_s1[] = [
                                    'student_id' => $std->user_id,
                                    'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 1)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 1)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                    ];


                    $pt_scores_s1[] = [
                                    'student_id' => $std->user_id,
                                    'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 1)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 1)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                ];

                    $exam_scores_s1[] = [
                                    'student_id' => $std->user_id,
                                    'score' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 1)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 1)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                ];

                }


                foreach($section->students as $std) {
                    $ww_percentage = 0;
                    $pt_percentage = 0;
                    $exam_percentage = 0;
                    $grade = 0;

                    foreach ($ww_scores_s1 as $ws) {
                        if($std->user_id == $ws['student_id'] && $ws['score'] != 0) {
                            $ww_percentage = ($ws['score']/$ws['total']) * ($sub->written_work/100);
                        }
                    }



                    foreach ($pt_scores_s1 as $pt) {
                        if($std->user_id == $ws['student_id'] && $pt['score'] != 0) {
                            $pt_percentage = ($pt['score']/$pt['total']) * ($sub->performance_task/100);
                        }
                    }

                    foreach ($exam_scores_s1 as $es) {
                        if($std->user_id == $ws['student_id'] && $es['score'] != 0) {
                            $exam_percentage = ($es['score']/$es['total']) * ($sub->exam/100);
                        }
                    }

                    $grade = ($ww_percentage + $pt_percentage + $exam_percentage) * 100;

                    $fsg[] = [
                        'student_id' => $std->user_id,
                        'grade' => $grade
                        ];
                }

            } // end of first sem

            // for first sem
            if($second_sem->status == 1) {
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($section->students as $std) {
                    // total subject total in first quarter\
                    $ww_scores_s2[] = [
                                    'student_id' => $std->user_id,
                                    'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 2)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 2)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                    ];


                    $pt_scores_s2[] = [
                                    'student_id' => $std->user_id,
                                    'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 2)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 2)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                ];

                    $exam_scores_s2[] = [
                                    'student_id' => $std->user_id,
                                    'score' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 2)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('score'),
                                    'total' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 2)
                                            ->where('section_id', $section->id)
                                            ->where('subject_id', $subjectid)
                                            ->where('student_number', $std->user_id)
                                            ->sum('total')
                                ];

                }


                foreach($section->students as $std) {
                    $ww_percentage = 0;
                    $pt_percentage = 0;
                    $exam_percentage = 0;
                    $grade = 0;

                    foreach ($ww_scores_s2 as $ws) {
                        if($std->user_id == $ws['student_id'] && $ws['score'] != 0) {
                            $ww_percentage = ($ws['score']/$ws['total']) * ($sub->written_work/100);
                        }
                    }



                    foreach ($pt_scores_s2 as $pt) {
                        if($std->user_id == $ws['student_id'] && $pt['score'] != 0) {
                            $pt_percentage = ($pt['score']/$pt['total']) * ($sub->performance_task/100);
                        }
                    }

                    foreach ($exam_scores_s2 as $es) {
                        if($std->user_id == $ws['student_id'] && $es['score'] != 0) {
                            $exam_percentage = ($es['score']/$es['total']) * ($sub->exam/100);
                        }
                    }

                    $grade = ($ww_percentage + $pt_percentage + $exam_percentage) * 100;

                    $ssg[] = [
                        'student_id' => $std->user_id,
                        'grade' => $grade
                        ];
                }

            } // end of second sem


            return view('teacher.student-subject-grade2', ['section' => $section, 'subject' => $sub, 'students' => $this->getMyStudents(), 'fsg' => $fsg, 'ssg' => $ssg]);
        }

    }


    public function updateExamScore($id = null, $user_id = null, $assignid = null)
    {
        $score = ExamScore::findorfail($id);
        $student = User::where('user_id', $user_id)->first();

        return view('teacher.teacher-update-exam-score', ['students' => $this->getMyStudents(), 'score' => $score, 'student' => $student, 'assignid' => $assignid]);
    }



    // update exam score
    public function postUpdateExamScore(Request $request)
    {
        $this->validate($request, [
            'score' => 'required|numeric'
        ]);

        $score = $request['score'];
        $total = $request['total'];
        $assignid = $request['assignid'];

        $exam = ExamScore::findorfail($request['id']);
        $exam->score = $score;
        $exam->save();

        // log
        $log = new UserLog();
        $log->user_id = Auth::user()->id;
        $log->action = 'Update Exam Score';
        $log->save();


        return redirect()->route('view_exam_score', ['sectionid' => $exam->section_id, 'subjectid' => $exam->subject_id, 'assignid' => $assignid])->with('success', 'Score Updated!');
    }


    // method to go to messages
    public function getMessages()
    {
        // find all message under the teacher
        $student_messages = Message::where('teacher_id', Auth::user()->id)->distinct()->orderBy('status', 'desc')->get(['student_id']);


        return view('teacher.teacher-messages', ['students' => $this->getMyStudents(), 'student_messages' => $student_messages]);
    }



    // method use to go to thread view
    public function studentMessageThread($student_id = null)
    {

        $student = User::where('user_id', $student_id)->first();

        $messages = Message::where('teacher_id', Auth::user()->id)
                            ->where('student_id', $student->id)
                            ->orderBy('created_at', 'desc')
                            ->get();

        Message::where('teacher_id', Auth::user()->id)
                ->where('student_id', $student->id)
                ->whereSender(3)
                ->update(['status' => 1]);

        return view('teacher.teacher-message-thread', ['students' => $this->getMyStudents(), 'messages' => $messages, 'student' => $student]);
    }


    // method use to send message
    public function teacherSendMessage(Request $request)
    {
        $message = $request['message'];
        $student_id = $request['student_id'];

        $student = User::findorfail($student_id);

        $new = new Message();
        $new->teacher_id = Auth::user()->id;
        $new->student_id = $student_id;
        $new->message = $message;
        $new->sender = 2;
        $new->status = 0;
        $new->save();

        return redirect()->route('teacher_student_message_thread', ['student_id' => $student->user_id]);
    }
}
