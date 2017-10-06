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

class TeacherController extends Controller
{

	private function getMyStudents()
	{
		$user = Auth::user();


		$asy = SchoolYear::where('status', 1)->first();

        if(count($asy) != 1) {
            return 'No Active School Year. Report to admin.';
        }

		$students = SubjectAssign::where('teacher_id', $user->id)
								->where('school_year_id', $asy->id)
								->get();

        if(count($students) == 0) {
            return 'Error. Let the admin finished the initialize setup.';
        }

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
            $wwn->save();
            
        }

        // increase the number of the written work number
        $wwn->number = $wwn->number + 1;
        $wwn->save();

        // set array for score together with student id of the student
        foreach($section->students as $std) {
            // 
            $score[] = [
                'student_id' => $std->id,
                'student_number' => $std->user->user_id,
                'written_work_number' => $wwn->number,
                'score' => $request[$std->user_id],
                'total' => $total

            ];
        }

        // insert score in written work scores table
        DB::table('written_work_scores')->insert($score);

        return redirect()->back()->with('success', 'Written Work #' . $wwn->number . ' Sucessfully Saved!');
        
        return 'error in post add written work';
        

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
