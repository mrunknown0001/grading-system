<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Excel;
use Illuminate\Support\Facades\Input;

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

class AdminController extends Controller
{
    /*
     * Admin Dashboard
     */
    public function getAdminDashboard()
    {
        $school_year = SchoolYear::where('status', 1)->first();
        $quarter = Quarter::where('status', 1)->first();
        $semester = Semester::where('status', 1)->first();

    	return view('admin.admin-dashboard', ['school_year' => $school_year, 'quarter' => $quarter, 'semester' => $semester]);
    }


    /*
     * postAddTeacher
     *
     */
    public function postAddTeacher(Request $request) 
    {

     	/*
     	 * Input Validation
     	 */
     	$this->validate($request, [
     		'id_number' => 'required',
     		'firstname' => 'required',
     		'lastname' => 'required',
     		'birthday' => 'required',
     		'gender' => 'required',
     		'address' => 'required',
     		'email' => 'required|email|unique:users',
     		'mobile' => 'required'
     		]);

     	// ASSIGN INPUT VULES TO VARIABLES
        $user_id = $request['id_number'];
        $firstname = $request['firstname'];
        $lastname = $request['lastname'];
        $birthday = date('Y-m-d', strtotime($request['birthday']));
        $gender = $request['gender'];
        $address = $request['address'];
        $email = $request['email'];
        $mobile = $request['mobile'];


        // Check User ID Availability
        $user_id_check = User::where('user_id', $user_id)->first();

        if(!empty($user_id_check)) {
            return redirect()->route('add_teacher')->with('error_msg', 'This ID Number: ' . $user_id . ' is already assigned to a Teacher.');
        }

        // Check email availability
        $email_check = User::where('email', $email)->first();

        if(!empty($email_check)) {
            return redirect()->route('add_teacher')->with('error_msg', 'This email: ' . $email . ' is registered with different account, please ask to Teacher to privide different active email address.');
        }

        // query to add new teacher
		$add = new User();

        $add->user_id = $user_id;
        $add->firstname = $firstname;
        $add->lastname = $lastname;
        $add->birthday = $birthday;
        $add->gender = $gender;
        $add->address = $address;
        $add->email = $email;
        $add->mobile = $mobile;
        $add->password = bcrypt('concsfaculty2017'); 
        $add->privilege = 2;
        $add->status = 1;

        if($add->save()) {

            // Add log to admin
            $log = new UserLog();

            $log->user_id = Auth::user()->id;
            $log->action = 'Added Teacher with User ID Number: ' . $user_id;

            $log->save();

            return redirect()->route('add_teacher')->with('success', 'Teacher Added: ' . ucwords($firstname) . ' ' . ucwords($lastname));

        }

        // If something is wrong
        return redirect()->route('add_teacher')->with('error_msg', 'Something is Wrong! Please reload this page.');

    }


     /*
      * getAllTeachers
      */
    public function getAllTeachers()
    {

     	// SELECT ALL TEACHERS IN USERS TABLE
     	$teachers = User::where('privilege', 2)->orderBy('user_id', 'asc')->paginate(10);

     	return view('admin.view-all-teachers', [
     		'teachers' => $teachers
     		]);
    }


     /*
      * getRemoveTeacher
      */
    public function getRemoveTeacher($id = null)
    {
        if($id == null) {
            return 'Error Occured! Please Reload this page.';
        }

        // check user is valid and active
        $user = User::findorfail($id);

        $user_id = $user->user_id;

        if($user->privilege != 2 || $user->status != 1) {
            // Abort to 404 page if the user is not co-admin or inactive
            return abort(404);
        }

        if($user->delete()) {

            $log = new UserLog();

            $log->user_id = Auth::user()->id;
            $log->action = 'Removed Teacher with Employee Number: ' . $user_id;

            $log->save();

            // Redirect if the operation is successful
            return redirect()->route('get_all_teachers')->with('success', 'Successfully Remove Teacher.');
        }

    }



    /*
     * showTeacherProfileEdit() methos is use to show co-admin profile to edit
     */
    public function showTeacherProfileEdit($user_id = null)
    {
        $user = User::where('user_id', $user_id)->where('status', 1)->first();

        // if user has no
        if(empty($user)) {
            abort(404);
        }

        /*
         * If user trying to modify is admin, this is not allowed
         * redirect to list of co-admin
         */
        if($user->privilege == 1) {
            return redirect()->route('get_all_teachers')->with('error_msg', 'Oppss! Something Fishy! Please reload this page');
        }

        if(empty($user)) {
            // If user is empty/ not in the database
            return abort(404);
        }

        return view('admin.update-teacher-details', ['user' => $user]);

    }



    /*
     * postUpdateTeacherDetails() method is use to update from edit from
     */
    public function postUpdateTeacherDetails(Request $request)
    {

        /*
         * Input validation
         */
        $this->validate($request, [
            'id_number' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'birthday' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'email' => 'required',
            'mobile' => 'required'
            ]);

        // Assigning Values to variables
        $id = $request['id'];

        $user_id = $request['id_number'];
        $firstname = $request['firstname'];
        $lastname = $request['lastname'];
        $birthday = date('Y-m-d', strtotime($request['birthday']));
        $gender = $request['gender'];
        $address = $request['address'];
        $email = $request['email'];
        $mobile = $request['mobile'];

        if($id == null) {
            // If the script is modified suspiciously
            return 'Error Occured. Please Reload This Page.';
        }

        // get Details of user
        $user = User::findorfail($id);

        /*
         * If user trying to modify is admin, this is not allowed
         * redirect to list of co-admin
         */
        if($user->privilege == 1) {
            return redirect()->route('get_all_teachers')->with('error_msg', 'Oppss! Something Fishy! Please reload this page');
        }

        // Check and Verify availability of user id
        if($user_id != $user->user_id) {
            // check if user id is existing
            $user_id_check = User::where('user_id', $user_id)->first();

            if($user_id_check == True) {
                return redirect()->route('update_teacher_details', $user->user_id)->with('error_msg', 'ID Number already in use.');
            }
        }

        // Check and Verify availability of email address
        if($email != $user->email) {
            // check email is existing
            $email_check = User::where('email', $email)->first();

            if($email_check == True) {
                return redirect()->route('update_teacher_details', $user->user_id)->with('error_msg', 'Email already in use.');
            }

        }

        /*
         * Note: updating without changes can catch here
         */


        // Assign new Values to co-admin details
        $user->user_id = $user_id;
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->birthday = $birthday;
        $user->gender = $gender;
        $user->address = $address;
        $user->email = $email;
        $user->mobile = $mobile;

        if($user->save()) {

            // Log for Admin Action
            $log = new UserLog();

            $log->user_id = Auth::user()->id;
            $log->action = 'Updated Teacher Profile Details Employee Number: ' . $user_id;

            $log->save();

            return redirect()->route('update_teacher_details', $user->user_id)->with('success', 'Teacher\'s Details Successfully Updated');
        }
        else {
            return redirect()->route('update_teacher_details', $user->user_id)->with('error_msg', 'Error Occured! Please try again later.');
        }

    }




    // admin email 
    public function adminEmail()
    {
        return view('admin.admin-email');
    }



    public function postAdminEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email'
        ]);

        $email = $request['email'];

        $admin = User::find(Auth::user()->id);
        $admin->email = $email;
        $admin->save();


        return redirect()->route('admin_email')->with('success', 'Email Updated!');
    }

    /*
     * method use to add subject
     */
    public function getAddSubject()
    {

        // Get all grade levels
        $levels = GradeLevel::get();
        // $levels = GradeLevel::orderBy('name', 'DESC')->get();

        return view('admin.add-subject', ['levels' => $levels]);

    }


    /*
     * postAddSubject()
     */
    public function postAddSubject(Request $request)
    {
        // Validation
        $this->validate($request, [
            'grade_level' => 'required',
            'title' => 'required',
            'description' => 'required',
            'written_work' => 'required|numeric',
            'performance_task' => 'required|numeric',
            'exam' => 'required|numeric'
            ]);

        // Assigning variables
        $level = $request['grade_level'];
        $title = $request['title'];
        $description = $request['description'];
        $written_work = $request['written_work'];
        $performance_task = $request['performance_task'];
        $exam = $request['exam'];

        $total = $written_work + $performance_task + $exam;

        if($total != 100) {
            return redirect()->route('get_add_subject')->with('error_msg', 'Total Criterial Percentage Must be equal to 100');
        }

        $add = new Subject();

        $add->level = $level;
        $add->title = $title;
        $add->description = $description;
        $add->written_work = $written_work;
        $add->performance_task = $performance_task;
        $add->exam = $exam;


        if($add->save()) {

            $log = new UserLog();

            $log->user_id = Auth::user()->id;
            $log->action = 'Added New Subject: ' . ucwords($title);

            $log->save();

            return redirect()->route('get_add_subject')->with('success', ucwords($title) . ' Subject Successfully Added!');

        }
    }

    /*
     * getViewAllSubjects
     */
    public function getViewAllSubjects()
    {

        $subjects = Subject::orderBy('level', 'asc')->orderBy('title', 'asc')->paginate(10);


        return view('admin.view-all-subjects', ['subs' => $subjects]);
    }


    /* 
     * update subject details
     */
    public function showSubjectDetailsUpdate($id = null)
    {
        $subject = Subject::findorfail($id);
        $levels = GradeLevel::get();

        return view('admin.update-subject-details', ['subject' => $subject, 'levels' => $levels]);
    }


    public function postUpdateSubjectDetails(Request $request)
    {
        /*
         * Validate User Input
         */
        $this->validate($request, [
            'grade_level' => 'required',
            'title' => 'required',
            'description' => 'required',
            'written_work' => 'required|numeric',
            'performance_task' => 'required|numeric',
            'exam' => 'required|numeric'
            ]);

        $id = $request['id'];
        $level = $request['grade_level'];
        $title = $request['title'];
        $description = $request['description'];
        $written_work = $request['written_work'];
        $performance_task = $request['performance_task'];
        $exam = $request['exam'];

        // If id is empty
        if(empty($id)) {
            return 'System encountered error. Please reload this page.';
        }

        $total = $written_work + $performance_task + $exam;

        if($total != 100) {
            return redirect()->route('show_subject_details_update', $id)->with('error_msg', 'Total Criterial Percentage Must be equal to 100');
        }

        $subject = Subject::findorfail($id);

        if(empty($subject)) {
            // If id is not on database
            return abort(404);
        }

        $subject->level = $level;
        $subject->title = $title;
        $subject->description = $description;
        $subject->written_work = $written_work;
        $subject->performance_task = $performance_task;
        $subject->exam = $exam;

        if($subject->save()) {

            $log = new UserLog();

            $log->user_id = Auth::user()->id;
            $log->action = 'Updated Subject: ' . ucwords($title);

            $log->save();

            return redirect()->route('show_subject_details_update', $id)->with('success', 'Subject Successfully Updated');


            return 'Error in Saving Update';

        }
    }


    /*
     * remove subject
     */
    public function getRemoveSubject($id = null)
    {

        $subject = Subject::findorfail($id);

        // Check if subject code belongs to a specific subject
        // If not, redirect to 404
        if(empty($subject)) {
            return abort(404);
        }

        if($subject->delete()) {

            $log = new UserLog();

            $log->user_id = Auth::user()->id;
            $log->action = 'Remove Subject';

            $log->save();

            return redirect()->route('get_view_all_subjects')->with('success', 'Subject Removed Successfully.');

        }

        return redirect()->back()->with('notice', 'Some Error Occured, Please Reload this page.');

    }




    /*
     * getAddSection
     */
    public function getAddSection()
    {
        $levels = GradeLevel::get();

        return view('admin.add-section', ['levels' => $levels]);
    }


    /*
     * postAddSection
     */
    public function postAddSection(Request $request)
    {
        /*
         * Input validation
         */
        $this->validate($request, [
            'grade_level' => 'required',
            'name' => 'required'
            ]);

        $level = $request['grade_level'];
        $name = $request['name'];

        // grade level
        $grade_level = GradeLevel::findorfail($level);

        $add = new Section();

        $add->level = $level;
        $add->name = $name;

        if($add->save()) {

            $log = new UserLog();

            $log->user_id = Auth::user()->id;
            $log->action = 'Added Section: ' . ucwords($grade_level->name) . ucwords($name);
            // Saving Log for this activity
            $log->save();

            return redirect()->route('add_section')->with('success', 'Section Successfully Added');
        }

        return 'Error Occured! Please reload this page.';

    }


    /*
     * getAllSections
     */
    public function getAllSections()
    {

        $sections = Section::orderBy('level', 'desc')
                            ->orderBy('name', 'desc')
                            ->where('visible', 1)
                            ->paginate(10);

        return view('admin.view-all-sections', ['sections' => $sections]);
    }


    /*
     * getRemoveSection use to remove section
     */
    public function getRemoveSection($id = null)
    {
        $section = Section::findorfail($id);

        // Check if subject code belongs to a specific subject
        // If not, redirect to 404
        if(empty($section)) {
            return abort(404);
        }

        // check if the section has no students in it
        $check_section_import = StudentImport::where('section_id', $section->id)->first();

        // check if section is assigned
        $check_section_assigned = SubjectAssign::where('section_id', $section->id)->first();

        // student added
        $check_student_added = StudentInfo::where('section', $section->id)->get();


        if(count($check_section_import) > 0) {
            return redirect()->route('get_all_sections')->with('error_msg', 'Section has students. You cant delete it!');
        }

        if(count($check_section_assigned) > 0) {
            return redirect()->route('get_all_sections')->with('error_msg', 'Section already assigned to Teacher. You cant delete it!');
        }
        
        if(count($check_student_added) > 0) {
            return redirect()->route('get_all_sections')->with('error_msg', 'Section has student(s). You cant delete it!');
        }


        if($section->delete()) {

            $log = new UserLog();

            $log->user_id = Auth::user()->id;
            $log->action = 'Remove Section';

            $log->save();

            return redirect()->route('get_all_sections')->with('success', 'Section Removed Successfully.');

        }

        return redirect()->back()->with('notice', 'Some Error Occured, Please Reload this page.');
    }


    /*
     * showSectionUpdateDetails
     */
    public function showSectionUpdateDetails($id = null)
    {

        $section = Section::findorfail($id);
        $levels = GradeLevel::get();

        return view('admin.update-section-details', ['section' => $section, 'levels' => $levels]);

    }


    /*
     * postUpdateSectionDetails use to update section
     */
    public function postUpdateSectionDetails(Request $request)
    {
        /*
         * Inut validation in update section
         */
        $this->validate($request, [
            'grade_level' => 'required',
            'name' => 'required'
            ]);

        // assigning values to variables
        $level = $request['grade_level'];
        $name = $request['name'];
        $id = $request['id'];


                // If id is empty
        if(empty($id)) {
            return 'System encountered error. Please reload this page.';
        }

        $section = Section::findorfail($id);

        if(empty($section)) {
            // If id is not on database
            return abort(404);
        }

        $section->level = $level;
        $section->name = $name;

        if($section->save()) {

            $log = new UserLog();

            $log->user_id = Auth::user()->id;
            $log->action = 'Updated section: ' . ucwords($name);

            $log->save();

            return redirect()->route('get_update_section_details', $id)->with('success', 'Section Successfully Updated');


            return 'Error in Saving Update';

        }

    }



    /*
     * getAddStudent use to add students
     */
    public function getAddStudent()
    {

        /*
         * Check if the there is school year added/selected
         */
        $school_year = SchoolYear::where('status', 1)->first();

        if(count($school_year) == 0) {
            return redirect()->route('admin_dashboard')->with('notice', 'Please Add and Seclect School Year before you can start adding students.');
        }


        $sections = Section::orderBy('level', 'desc')
                            ->orderBy('name', 'desc')
                            ->where('visible', 1)
                            ->get();

        return view('admin.add-student', ['sections' => $sections]);
    }


    /*
     * postAddStudent used to add students
     */
    public function postAddStudent(Request $request)
    {
        // VAlidation
        $this->validate($request, [
            'section' => 'required',
            'student_number' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'birthday' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:users',
            'mobile' => 'required'
            ]);

        $section = $request['section'];
        $student_number = $request['student_number'];
        $firstname = $request['firstname'];
        $lastname = $request['lastname'];
        $birthday = date('Y-m-d', strtotime($request['birthday']));
        $gender = $request['gender'];
        $address = $request['address'];
        $email = $request['email'];
        $mobile = $request['mobile'];

        $active_school_year = SchoolYear::where('status', 1)->first();

        // Check User ID Availability
        $user_id_check = User::where('user_id', $student_number)->where('status', 1)->first();

        if(!empty($user_id_check)) {
            return redirect()->route('add_teacher')->with('error_msg', 'This ID Number: ' . $student_number . ' is already assigned to a Student.');
        }

        // Check email availability
        $email_check = User::where('email', $email)->where('status', 1)->first();

        if(!empty($email_check)) {
            return redirect()->route('add_teacher')->with('error_msg', 'This email: ' . $email . ' is registered with different account, please ask to Teacher to privide different active email address.');
        }

        // query to add new student
        $add = new User();

        $add->user_id = $student_number;
        $add->firstname = $firstname;
        $add->lastname = $lastname;
        $add->birthday = $birthday;
        $add->gender = $gender;
        $add->address = $address;
        $add->email = $email;
        $add->mobile = $mobile;
        $add->password = bcrypt('concs2017'); 
        $add->privilege = 3;
        $add->status = 1;
        $add->school_year = $active_school_year->id;


        if($add->save()) {


            $new =  $add->id;

            $info = new StudentInfo();

            $info->user_id = $student_number;
            $info->section = $section;
            $info->school_year = $active_school_year->id;

            $info->save();

            // Add log to admin
            $log = new UserLog();

            $log->user_id = Auth::user()->id;
            $log->action = 'Added Student with User ID Number: ' . $student_number;

            $log->save();

            return redirect()->route('get_add_student')->with('success', 'Student Added: ' . ucwords($firstname) . ' ' . ucwords($lastname));

        }

        // If something is wrong
        return redirect()->route('get_add_student')->with('error_msg', 'Something is Wrong! Please reload this page.');

    }



    public function adminRemoveStudent(Request $request)
    {
        $student = User::find($request['id']);

        $student_info = StudentInfo::where('user_id', $student->user_id)->first();


        if($student->delete() && $student_info->delete()) {
            return redirect()->route('get_view_all_students')->with('success', 'Successfully Drop Student!');
        }

        return 'Error in Dropping Students';
    }


    /*
     * getViewAllStudents view students
     */
    public function getViewAllStudents()
    {

        $students = User::where('privilege', 3)
                        ->where('status', 1)
                        ->orderBy('lastname', 'asc')
                        ->paginate(15);

        return view('admin.view-all-students', ['students' => $students]);
    }


    /*
     * postUpdateStudentDetails update student details
     */
    public function postUpdateStudentDetails(Request $request)
    {
        // VAlidation
        $this->validate($request, [
            'section' => 'required',
            'student_number' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'birthday' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'email' => 'required',
            'mobile' => 'required'
            ]);

        $section = $request['section'];
        $student_number = $request['student_number'];
        $firstname = $request['firstname'];
        $lastname = $request['lastname'];
        $birthday = date('Y-m-d', strtotime($request['birthday']));
        $gender = $request['gender'];
        $address = $request['address'];
        $email = $request['email'];
        $mobile = $request['mobile'];

        $id = $request['id'];
        $original_user_id = $request['original_user_id'];

        $student = User::findorfail($id);
        $student_info = StudentInfo::where('user_id', $original_user_id)->first();


        // check if the the student number is already belong to others, if inputed new student number
        if($student->user_id != $student_number) {
            $check_student_number = User::where('user_id', $student_number)
                                        ->where('status', 1)
                                        ->first();
            if(!empty($check_student_number)) {
                return redirect()->route('get_update_student_details', $student->id)->with('error_msg', 'Student Number: ' . $student_number .' is already belongs to others!');

            }
        }


        // Check if the email is new or just the same
        // If new, this will check the email is available or not
        if($student->email != $email) {
            // Email check availability if new
            $check_email = User::where('email', $email)->first();

            if(!empty($check_email)) {
                return redirect()->route('get_update_student_details', $student->id)->with('error_msg', 'Email ' . $email .' is already used!');
            }

        }

        $student->user_id = $student_number;
        $student->firstname = $firstname;
        $student->lastname = $lastname;
        $student->email = $email;
        $student->mobile = $mobile;
        $student->birthday = date('Y-m-d', strtotime($birthday));
        $student->address = $address;
        $student->gender = $gender;


        // save the update on students
        if($student->save()) {
            // update user_id in studentinfo if nessesary
            $info = StudentInfo::findorfail($student_info->id);
            $info->user_id = $student_number;
            $info->save();


            // User/admin log in updating student
            $log = new UserLog();
            $log->user_id = Auth::user()->id;
            $log->action = 'Updated Student Details';
            $log->save();

            return redirect()->route('get_update_student_details', $student->id)->with('success', 'Student Detail Updated Successfully');
        }

        return "Error in Saving Updates. Please reload this page";


    }


    /* 
     * update student details
     */
    public function getUpdateStudentDetails($id = null)
    {
        // get all grade levels and section
        $sections = Section::get();

        $student = User::findorfail($id);

        return view('admin.update-student-details', ['sections' => $sections, 'student' => $student]);
    }



    /*
     * Batch import Students
     */
    public function importStudents()
    {

        /*
         * Check if the there is school year added/selected
         */
        $school_year = SchoolYear::where('status', 1)->first();

        if(count($school_year) == 0) {
            return redirect()->route('admin_dashboard')->with('notice', 'Please Add and Seclect School Year before you can start adding students.');
        }


        // grade level and sections
        $sections = Section::orderBy('level', 'desc')
                    ->orderBy('name', 'desc')
                    ->where('visible', 1)
                    ->get();
        

        return view('admin.add-student-import', ['sections' => $sections]);

    }


    /*
     * batch import students
     * 
     */
    public function postImportStudents(Request $request)
    {

        // validations
        $this->validate($request, [
            'grade_section' => 'required',
            'students' => 'required'
        ]);


        $section = $request['grade_section'];
        $section_details = Section::findorfail($section);

        $active_school_year = SchoolYear::where('status', 1)->first();

        $check_student_import = StudentImport::where('section_id', $section)
                                    ->where('school_year_id', $active_school_year->id)
                                    ->first();

        if(!empty($check_student_import)) {
            // message and redirection if the section is already imported
            return redirect()->route('import_students')->with('notice', 'Section Already Imported!');
        }

        if(Input::hasFile('students')){
            $path = Input::file('students')->getRealPath();
            $data[] = Excel::selectSheetsByIndex(0)->load($path, function($reader) {
                    // $reader->get();
                    $reader->skipColumns(1);
                })->get();

        }

        $insert = [];
        $info = [];

        if(!empty($data)){
            foreach ($data as $value) {
                
                foreach ($value as $row) {
                    if($row->student_number != null) {

                        // check each student number if it is already in database
                        $check_student_number = User::where('user_id', $row->student_number)->first();

                        // check email
                        $check_email = User::where('email', $row->email)->first();



                        if(!empty($check_student_number)) {
                            // return redirect()->route('import_students')->with('notice', 'Student Number: ' . $check_student_number->user_id . ' is already in record. Please double check your sheet before uploading. Remove any recorded students in sheet.');
                            // 
                            // update only the student info
                            $std_info = StudentInfo::where('user_id', $row->student_number)->first();
                            $std_info->section = $section;
                            $std_info->school_year = $active_school_year->id;
                            $std_info->save();
                        }

                        // elseif(!empty($check_email)) {
                            // return redirect()->route('import_students')->with('notice', 'Duplicate email found: ' . $row->email .' Emails can be used only once. Please Check your sheet for any duplicate record.');
                        // }

                        else {

                            // for users table
                            $insert[] = [
                                    'user_id' => $row->student_number,
                                    'lastname' => $row->lastname,
                                    'firstname' => $row->firstname,
                                    'gender' => $row->sex,
                                    'birthday' => date('Y-m-d', strtotime($row->birthday)),
                                    'address' => $row->address,
                                    'email' => $row->email,
                                    'mobile' => $row->number,
                                    'privilege' => 3,
                                    'password' => bcrypt('concs2017'), // this is the default password for students
                                    'school_year' => $active_school_year->id
                                ];


                            // for student info table
                            $info[] = [
                                    'user_id' => $row->student_number,
                                    'section' => $section,
                                    'school_year' => $active_school_year->id

                                ];

                        }


                    }
                }

            }
        }


        if(!empty($insert)) {
            // insert data to users
            DB::table('users')->insert($insert);
        }   

        if(!empty($info)) {
            // insert import data to studentimport
            DB::table('student_infos')->insert($info);
        }

        // student import log
        $import = new StudentImport();
        $import->section_id = $section;
        $import->school_year_id = $active_school_year->id;


        if($import->save()) {
            $log = new UserLog();

            $log->user_id = Auth::user()->id;
            $log->action = "Imported Section: " . ucwords($section_details->grade_level->name) . ' - ' . ucwords($section_details->name);

            $log->save();

            return redirect()->route('import_students')->with('success', 'Successfully Imported Students in ' . ucwords($section_details->grade_level->name) . ' - ' . ucwords($section_details->name));
        }


        return "Error Occurred";


    }



    /*
     * viewProfile
     */
    public function viewProfile()
    {
        $admin = Auth::user();

        return view('admin.admin-profile', ['admin' => $admin]);
    }



    /*
     * add school year
     */
    public function postAddSchoolYear(Request $request)
    {
        /*
         * Input validation
         */
        $this->validate($request, [
            'school_year' => 'required'
            ]);

        // Assign Values to Variables
        $from = $request['school_year'];
        $to = $from + 1;

        $check_year = SchoolYear::where('from', $from)
                            ->where('to', $to)
                            ->where('status', 1)
                            ->first();

        if(!empty($check_year)) {
            return redirect()->route('add_school_year')->with('error_msg', 'There is an Active School Year. You Can\'t Add a new one! You can only add another school year if the current school year is finished');
        }

        $check_active = SchoolYear::where('status', 1)->first();
        if(!empty($check_active)) {
            return redirect()->route('add_school_year')->with('error_msg', 'There is an Active School Year. You Can\'t Add a new one! You can only add another school year if the current school year is finished');
        }

        $check_exists = SchoolYear::where('from', $from)->where('to', $to)->first();

        if(!empty($check_exists)) {
            return redirect()->route('add_school_year')->with('error_msg', 'This School year has in our database, please select different school year.');
        }

        $school_year = new SchoolYear();

        $school_year->from = $from;
        $school_year->to = $to;
        $school_year->status = 1;

        if($school_year->save()) {

            Quarter::where('finish', 1)
                        ->update(['status' => 0, 'finish' => 0]);
            Semester::where('finish', 1)
                        ->update(['status' => 0, 'finish' => 0]);



            // Save log for adding school Year
            $log = new UserLog();

            $log->user_id = Auth::user()->id;
            $log->action = 'Created new school year ' . $from . ' - ' . $to;

            $log->save();

            return redirect()->route('admin_dashboard')->with('success', 'School Year Successfully Added! You can now select a First Quarter and First Semester');
        }
    }



     /*
      * Get All Logs in User_logs table
      * only the admin can only view this logs
      */
    public function getAllLogs()
    {
     	// Get all logs query
     	$logs = UserLog::orderBy('created_at','desc')->paginate(10);

     	return view('admin.view-all-activity-logs', ['logs' => $logs]);

    }




    /*
     * select quarter selectQuarter
     */
    public function selectQuarter()
    {
        // Check if there is an active school year 
        $check_school_year = SchoolYear::where('status', 1)->where('finish', 0)->first();

        if(empty($check_school_year)) {

            return redirect()->route('admin_dashboard')->with('notice', 'No active school year. Please add and select school year.');
        }
        $quarter = Quarter::all();

        return view('admin.select-quarter', ['quarter' => $quarter]);
    }



    /*
     * selectActiveQuarter() use to select active quarter in school year
     */
    public function selectActiveQuarter($id = null)
    {
        // Check if there is an active school year 
        $check_school_year = SchoolYear::where('status', 1)->where('finish', 0)->first();

        if(empty($check_school_year)) {

            return redirect()->route('admin_dashboard')->with('notice', 'No active school year. Please add and select school year.');
        }

        $quarter = Quarter::findorfail($id);

        $quarter->status = 1;

        if($quarter->save()) {
            // Add user log for activating quarter
            $log = new UserLog();
            $log->user_id = Auth::user()->id;
            $log->action = 'Activated ' . ucwords($quarter->name) . ' quarter of ' . $check_school_year->from . ' - ' . $check_school_year->to . ' school year';
            $log->save();

            return redirect()->route('select_quarter');
        }
    }




    /*
     * finishSelectedQuarter() use to finsiehd selected quarter
     */
    public function finishSelectedQuarter($id = null)
    {

        // Check if there is an active school year 
        $check_school_year = SchoolYear::where('status', 1)->where('finish', 0)->first();

        if(empty($check_school_year)) {

            return redirect()->route('admin_dashboard')->with('error_msg', 'No active school year.');
        }

        // if($id == 4) {
        //     $end_school_year = SchoolYear::where('status', 1)->where('finish', 0)->first();

        //     $end_school_year->status = 0;
        //     $end_school_year->finish = 1;

        //     $end_school_year->save();
        // }

        $quarter = Quarter::findorfail($id);

        $quarter->status = 0;
        $quarter->finish = 1;

        if($quarter->save()) {
            // Add user log for activating quarter
            $log = new UserLog();
            $log->user_id = Auth::user()->id;
            $log->action = 'Finished ' . ucwords($quarter->name) . ' quarter of ' . $check_school_year->from . ' - ' . $check_school_year->to . ' school year';
            $log->save();

            return redirect()->route('select_quarter');
        }
    }



    // method use to reselect quarter
    public function adminReselectQuarter($id = null)
    {
        $quarter = Quarter::findorfail($id);

        $active_quarter = Quarter::whereStatus(1)->first();
        if(count($active_quarter) > 0) {
            $active_quarter->status = 0;
            $active_quarter->save();
        }

        $quarter->status = 1;
        $quarter->finish = 0;
        $quarter->save();

        // userlog
        $log = new UserLog();
        $log->user_id = Auth::user()->id;
        $log->action = 'admin reselect quarter: ' . $quarter->name;
        $log->save();

        return redirect()->route('select_quarter')->with('success', 'Quarter Reselected');
    }


    /*
     * selectSemester method use to select semester
     */
    public function selectSemester()
    {
        // Check if there is an active school year 
        $check_school_year = SchoolYear::where('status', 1)->where('finish', 0)->first();

        if(empty($check_school_year)) {

            return redirect()->route('admin_dashboard')->with('notice', 'No active school year. Please add and select school year.');
        }

        $semester = Semester::all();

        return view('admin.select-semester', ['semester' => $semester]);
    }



    /*
     * method use to reselect semester
     */
    public function adminReselectSemester($id = null)
    {
        $sem = Semester::findorfail($id);

        $active_sem = Semester::whereStatus(1)->first();
        if(count($active_sem) > 0) {
            $active_sem->status = 0;
            $active_sem->save();
        }

        $sem->status = 1;
        $sem->finish = 0;
        $sem->save();

        $log = new UserLog();
        $log->user_id = Auth::user()->id;
        $log->action = 'admin reselect semester: ' . $sem->name;
        $log->save();

        return redirect()->route('select_semester')->with('success', 'Semester Reselected');

    }



    // select active semester
    public function selectActiveSemester($id = null)
    {
        // Check if there is an active school year 
        $check_school_year = SchoolYear::where('status', 1)->where('finish', 0)->first();

        if(empty($check_school_year)) {

            return redirect()->route('admin_dashboard')->with('notice', 'No active school year. Please add and select school year.');
        }

        $semester = Semester::findorfail($id);

        $semester->status = 1;

        if($semester->save()) {
            // Add user log for activating quarter
            $log = new UserLog();
            $log->user_id = Auth::user()->id;
            $log->action = 'Activated ' . ucwords($semester->name) . ' semester of ' . $check_school_year->from . ' - ' . $check_school_year->to . ' school year';
            $log->save();

            return redirect()->route('select_semester');
        }

    }


    // finish selected semester
    public function finishSelectedSemester($id = null)
    {

        // Check if there is an active school year 
        $check_school_year = SchoolYear::where('status', 1)->where('finish', 0)->first();

        if(empty($check_school_year)) {

            return redirect()->route('admin_dashboard')->with('error_msg', 'No active school year.');
        }

        $semester = Semester::findorfail($id);

        $semester->status = 0;
        $semester->finish = 1;

        if($semester->save()) {
            // Add user log for activating quarter
            $log = new UserLog();
            $log->user_id = Auth::user()->id;
            $log->action = 'Finished ' . ucwords($semester->name) . ' quarter of ' . $check_school_year->from . ' - ' . $check_school_year->to . ' school year';
            $log->save();

            return redirect()->route('select_semester');
        }

    }



    // assign subject per level page
    public function assignSubjectLevel($id = null)
    {

        $active_school_year = SchoolYear::where('status', 1)->first();
        if(count($active_school_year) == 0) {
            return redirect()->route('admin_dashboard')->with('notice', 'No Active School Year. Please Add One');
        }

        if($id == null) {
            abort(404);
        }

        $subjects = Subject::where('level', $id)->get();
        $teachers = User::where('privilege', 2)->where('status', 1)->get();
        $sections = Section::where('level', $id)->where('visible', 1)->get();


        $level = GradeLevel::findorfail($id);

        if(empty($subjects)) {
            abort(404);
        }


        return view('admin.assign-subject', ['subjects' => $subjects, 'teachers' => $teachers, 'level' => $level, 'sections' => $sections]);
    }

    // assign subject per level 
    public function postAssignSubjectLevel(Request $request)
    {
        // validate input
        $this->validate($request, [
            'teacher' => 'required',
            'subject' => 'required',
            'section' => 'required'
            ]);

        
        $level = $request['level'];
        $teacher = $request['teacher'];
        $subject = $request['subject'];
        $section = $request['section'];

        $active_school_year = SchoolYear::where('status', 1)->first();

        $check_assigned_subject = SubjectAssign::where('teacher_id', $teacher)
                                    ->where('subject_id', $subject)
                                    ->where('section_id', $section)
                                    ->where('school_year_id', $active_school_year->id)
                                    ->first();


        // check if the subject is assigned to same teacher
        if(count($check_assigned_subject) != 0) {
            return redirect()->route('assign_subject_level', $level)->with('notice', 'The subject is already assigned to the teacher.');
        }


        // if the subject is already assigned to another teacher
        $check_assigned_subject2 = SubjectAssign::where('subject_id', $subject)
                                    ->where('section_id', $section)
                                    ->where('school_year_id', $active_school_year->id)
                                    ->first();

        if(!empty($check_assigned_subject2)) {
            return redirect()->route('assign_subject_level', $level)->with('notice', 'The subject is already assigned to another teacher.');
        }


        $assign = new SubjectAssign();
        $assign->teacher_id = $teacher;
        $assign->subject_id = $subject;
        $assign->section_id = $section;
        $assign->school_year_id = $active_school_year->id;


        if($assign->save()) {
            $log = new UserLog();
            $log->user_id = Auth::user()->id;
            $log->action = "Assigned Subject to Teacher";
            $log->save();

            return redirect()->route('assign_subject_level', $level)->with('success', 'Subject Successfully Assigned to Teacher.');
        }

        return redirect()->back()->with('notice', 'Error Occurred. Please reload the page.');
    }


    /*
     * method to view all subject assignments to teachers
     */
    public function viewsubjectAssignments()
    {

        $active_school_year = SchoolYear::where('status', 1)->first();
        $assignments = SubjectAssign::where('school_year_id', $active_school_year->id)->get();

        return view('admin.view-all-subject-assignments', ['assignments' => $assignments]);

    }



    // method use to close school year and compute all grades and get the average and ranking of the students
    public function postAdminCloseSchoolYear()
    {
        // check if the quarter is 4th and semester is second
        $quarter = Quarter::whereName('forth')->whereStatus(1)->first();
        $semester = Semester::whereName('second')->whereStatus(1)->first();

        if(count($quarter) == 0 || count($semester) == 0) {
            return redirect()->route('add_school_year')->with('notice', 'It must be on 4th Quarter and 2nd Semester to close the school year.');
        }

        $quarter->finish = 1;

        $semester->finish = 1;


        
        if($quarter->save() && $semester->save()) {
            return redirect()->route('add_school_year')->with('success', 'Successfully Close School Year');
        }

        // compute all grades
        // save grades to final grades
        // reset the quater to 0
        // reset the semester to 0
        // set no activet school year
        // 
        // delete/hide section
        // delete/hide subject assign
        
        
    }



    // method use to view sections of all grade level
    public function viewSectionsGradeLevel($id = null) // id of the grade level
    {
        $sections = Section::whereLevel($id)->where('visible', 1)->get();
        $grade_level = GradeLevel::findorfail($id);


        return view('admin.all-sections-grade-level', [ 'sections' => $sections, 'grade_level' => $grade_level]);


    }



    // method ot view students on sections per grade level
    public function adminViewSectionStudents($levelid = null, $sectionid = null)
    {
        $asy = SchoolYear::whereStatus(1)->first(); // active school year
        $level = GradeLevel::findorfail($levelid);
        $section = Section::findorfail($sectionid);

        // search for students
        $students = StudentInfo::where('section', $sectionid)->where('school_year', $asy->id)->get();
        
        


        // average and final grade of the students if necessary

        return view('admin.admin-view-section-students', ['students' => $students, 'section' => $section]);

    }


    // method use to start new school year
    public function startSchoolYear()
    {
        // make school year finish 1
        $sy = SchoolYear::whereStatus(1)->first();

        // if status is finish
        if(count($sy) < 1) {

            return redirect()->route('add_school_year')->with('notice', 'School Year is Already Finished');
        }

        $sy->finish = 1;
        $sy->status = 0;
        $sy->save();

        Quarter::whereFinish(1)->update(['finish' => 0]);
        Quarter::whereStatus(1)->update(['status' => 0]);
        Semester::whereFinish(1)->update(['finish' => 0]);
        Semester::whereStatus(1)->update(['status' => 0]);

        DB::table('sections')->update(['visible' => 0]);
        DB::table('student_info')->update(['section' => 0, 'school_year' => 0]);

        return redirect()->route('add_school_year')->with('success', 'You Can Now Start New School Year');

    }








    // compute grades of the students
    // per section
    public function adminComputeGrades($section_id = null)
    {

        $asy = SchoolYear::whereStatus(1)->first();
        $quarter = Quarter::whereStatus(1)->first();
        $semester = Semester::whereStatus(1)->first();

        $first_quarter = Quarter::findorfail(1);
        $second_quarter = Quarter::findorfail(2);
        $third_quarter = Quarter::findorfail(3);
        $fourth_quarter = Quarter::findorfail(4);

        $first_sem = Semester::findorfail(1);
        $second_sem = Semester::findorfail(2);




        // check if forth quarter and second sem is finished
        if($fourth_quarter->finish != 1 && $second_sem->finish != 1) {
            return redirect()->back()->with('notice', 'Forth Quarter and Second Semester Must be finished first before you can compute grades');
        }


        $section = Section::findorfail($section_id);

        $level_id = $section->grade_level->id;
        $subjects = $section->grade_level->subjects;


        // get all raw grades per subject per quarter or semester
        // for senior high

        return 'Insert All Grades in Database in this subject.';
 

    }


    public function adminSearchStudent(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'required'
        ]);

        $keyword = $request['keyword'];

        $students = User::where('privilege', 3)
            ->where('status', 'like', "%$keyword%")
            ->orwhere('user_id', 'like', "%$keyword%")
            ->orwhere('firstname', 'like', "%$keyword%")
            ->orwhere('lastname', 'like', "%$keyword%")
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('admin.admin-student-search-result', ['students' => $students]);
    }



    // method use to change profile picture of admin
    public function adminChangeProfilePicture()
    {
        return view('admin.admin-change-profile-picture');
    }


    public function adminPostChangeProfilePicture(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image'
        ]);

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
            $log->action = "Admin Change Profile Picture";
            $log->save();

           return redirect()->route('admin_profile_picture_change')->with('success', 'Sucessfully Change Your Profile Picture!');
        }

        return redirect()->route('admin_profile_picture_change')->with('error_msg', 'No Image File!');
    }







    /*
     * admin ranking
     */
    public function adminViewRanking($sectionid = null)
    {

        $section = Section::findorfail($sectionid);
        $asy = SchoolYear::whereStatus(1)->first();
        $first_quarter = Quarter::findorfail(1);
        $second_quarter = Quarter::findorfail(2);
        $third_quarter = Quarter::findorfail(3);
        $fourth_quarter = Quarter::findorfail(4);

        $first_sem = Semester::findorfail(1);
        $second_sem = Semester::findorfail(2);

        $first_quarter_grades = [];
        $second_quarter_grades = [];
        $third_quarter_grades = [];
        $fourth_quarter_grades = [];

        $first_sem_grades = [];
        $second_sem_grades = [];



        if($section->level <= 4) {

            // for first quarter
            if($first_quarter->finish == 1 || $first_quarter->status == 1) {
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($section->students as $std) {
                    foreach($section->grade_level->subjects as $subject) {
                        // total subject total in first quarter\
                        $ww_scores_q1[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 1)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 1)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                        ];


                        $pt_scores_q1[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 1)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 1)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                    ];

                        $exam_scores_q1[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => ExamScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 1)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => ExamScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 1)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                    ];


                    }

                }



                foreach($section->students as $std) {
                    foreach($section->grade_level->subjects as $subject) {

                        $ww_percentage = 0;
                        $pt_percentage = 0;
                        $exam_percentage = 0;
                        $grade = 0;

                        foreach ($ww_scores_q1 as $ws) {
                            if($std->user_id == $ws['student_id'] && $ws['score'] != 0) {
                                if($subject->id == $ws['subject_id']) {
                                    $ww_percentage = (($ws['score']/$ws['total']) * ($subject->written_work/100)) * 100;
                                }
                            }
                        }


                        foreach ($pt_scores_q1 as $pt) {
                            if($std->user_id == $pt['student_id'] && $pt['score'] != 0) {
                                if($subject->id == $pt['subject_id']) {
                                    $pt_percentage = (($pt['score']/$pt['total']) * ($subject->performance_task/100)) * 100;
                                }
                            }
                        }


                        foreach ($exam_scores_q1 as $es) {
                            if($std->user_id == $es['student_id'] && $es['score'] != 0) {
                                if($subject->id == $es['subject_id']) {
                                    $exam_percentage = (($es['score']/$es['total']) * ($subject->exam/100)) * 100;
                                }
                            }
                        }



                        $grade =  $ww_percentage + $pt_percentage + $exam_percentage;

                        $first_quarter_grades[] = [
                            'student_id' => $std->user_id,
                            'subject_id' => $subject->id,
                            'grade' => $this->getGrade($grade)
                            ];

                    }
                }

            } // end of first quarter


            // for second quarter
            if($second_quarter->finish == 1 || $second_quarter->status == 1) {
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($section->students as $std) {
                    foreach($section->grade_level->subjects as $subject) {
                        // total subject total in first quarter\
                        $ww_scores_q2[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 2)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 2)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                        ];


                        $pt_scores_q2[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 2)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 2)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                    ];

                        $exam_scores_q2[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => ExamScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 2)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => ExamScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 2)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                    ];


                    }

                }



                foreach($section->students as $std) {
                    foreach($section->grade_level->subjects as $subject) {

                        $ww_percentage = 0;
                        $pt_percentage = 0;
                        $exam_percentage = 0;
                        $grade = 0;

                        foreach ($ww_scores_q2 as $ws) {
                            if($std->user_id == $ws['student_id'] && $ws['score'] != 0) {
                                if($subject->id == $ws['subject_id']) {
                                    $ww_percentage = (($ws['score']/$ws['total']) * ($subject->written_work/100)) * 100;
                                }
                            }
                        }


                        foreach ($pt_scores_q2 as $pt) {
                            if($std->user_id == $pt['student_id'] && $pt['score'] != 0) {
                                if($subject->id == $pt['subject_id']) {
                                    $pt_percentage = (($pt['score']/$pt['total']) * ($subject->performance_task/100)) * 100;
                                }
                            }
                        }


                        foreach ($exam_scores_q2 as $es) {
                            if($std->user_id == $es['student_id'] && $es['score'] != 0) {
                                if($subject->id == $es['subject_id']) {
                                    $exam_percentage = (($es['score']/$es['total']) * ($subject->exam/100)) * 100;
                                }
                            }
                        }



                        $grade =  $ww_percentage + $pt_percentage + $exam_percentage;

                        $second_quarter_grades[] = [
                            'student_id' => $std->user_id,
                            'subject_id' => $subject->id,
                            'grade' => $this->getGrade($grade)
                            ];

                    }
                }

            } // end of second quarter



            // for third quarter
            if($third_quarter->finish == 1 || $third_quarter->status == 1) {
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($section->students as $std) {
                    foreach($section->grade_level->subjects as $subject) {
                        // total subject total in first quarter\
                        $ww_scores_q3[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 3)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 3)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                        ];


                        $pt_scores_q3[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 3)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 3)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                    ];

                        $exam_scores_q3[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => ExamScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 3)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => ExamScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 3)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                    ];


                    }

                }



                foreach($section->students as $std) {
                    foreach($section->grade_level->subjects as $subject) {

                        $ww_percentage = 0;
                        $pt_percentage = 0;
                        $exam_percentage = 0;
                        $grade = 0;

                        foreach ($ww_scores_q3 as $ws) {
                            if($std->user_id == $ws['student_id'] && $ws['score'] != 0) {
                                if($subject->id == $ws['subject_id']) {
                                    $ww_percentage = (($ws['score']/$ws['total']) * ($subject->written_work/100)) * 100;
                                }
                            }
                        }


                        foreach ($pt_scores_q3 as $pt) {
                            if($std->user_id == $pt['student_id'] && $pt['score'] != 0) {
                                if($subject->id == $pt['subject_id']) {
                                    $pt_percentage = (($pt['score']/$pt['total']) * ($subject->performance_task/100)) * 100;
                                }
                            }
                        }


                        foreach ($exam_scores_q3 as $es) {
                            if($std->user_id == $es['student_id'] && $es['score'] != 0) {
                                if($subject->id == $es['subject_id']) {
                                    $exam_percentage = (($es['score']/$es['total']) * ($subject->exam/100)) * 100;
                                }
                            }
                        }



                        $grade =  $ww_percentage + $pt_percentage + $exam_percentage;

                        $third_quarter_grades[] = [
                            'student_id' => $std->user_id,
                            'subject_id' => $subject->id,
                            'grade' => $this->getGrade($grade)
                            ];

                    }
                }

            } // end of third quarter



            // for fourth quarter
            if($fourth_quarter->finish == 1 || $fourth_quarter->status == 1) {
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($section->students as $std) {
                    foreach($section->grade_level->subjects as $subject) {
                        // total subject total in first quarter\
                        $ww_scores_q4[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 4)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 4)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                        ];


                        $pt_scores_q4[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 4)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 4)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                    ];

                        $exam_scores_q4[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => ExamScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 4)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => ExamScore::where('school_year_id', $asy->id)
                                                ->where('quarter_id', 4)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                    ];


                    }

                }



                foreach($section->students as $std) {
                    foreach($section->grade_level->subjects as $subject) {

                        $ww_percentage = 0;
                        $pt_percentage = 0;
                        $exam_percentage = 0;
                        $grade = 0;

                        foreach ($ww_scores_q4 as $ws) {
                            if($std->user_id == $ws['student_id'] && $ws['score'] != 0) {
                                if($subject->id == $ws['subject_id']) {
                                    $ww_percentage = (($ws['score']/$ws['total']) * ($subject->written_work/100)) * 100;
                                }
                            }
                        }


                        foreach ($pt_scores_q4 as $pt) {
                            if($std->user_id == $pt['student_id'] && $pt['score'] != 0) {
                                if($subject->id == $pt['subject_id']) {
                                    $pt_percentage = (($pt['score']/$pt['total']) * ($subject->performance_task/100)) * 100;
                                }
                            }
                        }


                        foreach ($exam_scores_q4 as $es) {
                            if($std->user_id == $es['student_id'] && $es['score'] != 0) {
                                if($subject->id == $es['subject_id']) {
                                    $exam_percentage = (($es['score']/$es['total']) * ($subject->exam/100)) * 100;
                                }
                            }
                        }



                        $grade =  $ww_percentage + $pt_percentage + $exam_percentage;

                        $fourth_quarter_grades[] = [
                            'student_id' => $std->user_id,
                            'subject_id' => $subject->id,
                            'grade' => $this->getGrade($grade)
                            ];

                    }
                }

            } // end of fourth quarter

            


        }
        else {
            // for senior highschool
            // 
            // for first semester
            if($first_sem->finish == 1 || $first_sem->status == 1) {
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($section->students as $std) {
                    foreach($section->grade_level->subjects as $subject) {
                        // total subject total in first quarter\
                        $ww_scores_s1[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                                ->where('semester_id', 1)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                                ->where('semester_id', 1)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                        ];


                        $pt_scores_s1[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                                ->where('semester_id', 1)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                                ->where('semester_id', 1)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                    ];

                        $exam_scores_s1[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => ExamScore::where('school_year_id', $asy->id)
                                                ->where('semester_id', 1)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => ExamScore::where('school_year_id', $asy->id)
                                                ->where('semester_id', 1)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                    ];


                    }

                }



                foreach($section->students as $std) {
                    foreach($section->grade_level->subjects as $subject) {

                        $ww_percentage = 0;
                        $pt_percentage = 0;
                        $exam_percentage = 0;
                        $grade = 0;

                        foreach ($ww_scores_s1 as $ws) {
                            if($std->user_id == $ws['student_id'] && $ws['score'] != 0) {
                                if($subject->id == $ws['subject_id']) {
                                    $ww_percentage = (($ws['score']/$ws['total']) * ($subject->written_work/100)) * 100;
                                }
                            }
                        }


                        foreach ($pt_scores_s1 as $pt) {
                            if($std->user_id == $pt['student_id'] && $pt['score'] != 0) {
                                if($subject->id == $pt['subject_id']) {
                                    $pt_percentage = (($pt['score']/$pt['total']) * ($subject->performance_task/100)) * 100;
                                }
                            }
                        }


                        foreach ($exam_scores_s1 as $es) {
                            if($std->user_id == $es['student_id'] && $es['score'] != 0) {
                                if($subject->id == $es['subject_id']) {
                                    $exam_percentage = (($es['score']/$es['total']) * ($subject->exam/100)) * 100;
                                }
                            }
                        }



                        $grade =  $ww_percentage + $pt_percentage + $exam_percentage;

                        $first_sem_grades[] = [
                            'student_id' => $std->user_id,
                            'subject_id' => $subject->id,
                            'grade' => $this->getGrade($grade)
                            ];

                    }
                }

            } // end of first semester

            if($second_sem->finish == 1 || $second_sem->status == 1) {
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($section->students as $std) {
                    foreach($section->grade_level->subjects as $subject) {
                        // total subject total in first quarter\
                        $ww_scores_s2[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                                ->where('semester_id', 2)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                                ->where('semester_id', 2)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                        ];


                        $pt_scores_s2[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                                ->where('semester_id', 2)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                                ->where('semester_id', 2)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                    ];

                        $exam_scores_s2[] = [
                                        'student_id' => $std->user_id,
                                        'subject_id' => $subject->id,
                                        'score' => ExamScore::where('school_year_id', $asy->id)
                                                ->where('semester_id', 2)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('score'),
                                        'total' => ExamScore::where('school_year_id', $asy->id)
                                                ->where('semester_id', 2)
                                                ->where('section_id', $section->id)
                                                ->where('subject_id', $subject->id)
                                                ->where('student_number', $std->user_id)
                                                ->sum('total')
                                    ];


                    }

                }



                foreach($section->students as $std) {
                    foreach($section->grade_level->subjects as $subject) {

                        $ww_percentage = 0;
                        $pt_percentage = 0;
                        $exam_percentage = 0;
                        $grade = 0;

                        foreach ($ww_scores_s2 as $ws) {
                            if($std->user_id == $ws['student_id'] && $ws['score'] != 0) {
                                if($subject->id == $ws['subject_id']) {
                                    $ww_percentage = (($ws['score']/$ws['total']) * ($subject->written_work/100)) * 100;
                                }
                            }
                        }


                        foreach ($pt_scores_s2 as $pt) {
                            if($std->user_id == $pt['student_id'] && $pt['score'] != 0) {
                                if($subject->id == $pt['subject_id']) {
                                    $pt_percentage = (($pt['score']/$pt['total']) * ($subject->performance_task/100)) * 100;
                                }
                            }
                        }


                        foreach ($exam_scores_s2 as $es) {
                            if($std->user_id == $es['student_id'] && $es['score'] != 0) {
                                if($subject->id == $es['subject_id']) {
                                    $exam_percentage = (($es['score']/$es['total']) * ($subject->exam/100)) * 100;
                                }
                            }
                        }



                        $grade =  $ww_percentage + $pt_percentage + $exam_percentage;

                        $second_sem_grades[] = [
                            'student_id' => $std->user_id,
                            'subject_id' => $subject->id,
                            'grade' => $this->getGrade($grade)
                            ];

                    }
                }

            } // end of first semester
        }


        $students_grades = [];


        // get grades for grade 7 to 10 only
        if($section->grade_level->id <= 4) {
            foreach($section->students as $std) {
                $ag = [];
                foreach($section->grade_level->subjects as $subject) {

                    // first quarter grade
                    foreach($first_quarter_grades as $fqg) {
                        if($fqg['student_id'] == $std->user_id && $fqg['subject_id'] == $subject->id) {
                            $ag[] = $fqg['grade'];
                        }
                    }

                    // second quarter grade
                    foreach($second_quarter_grades as $sqg) {
                        if($sqg['student_id'] == $std->user_id && $sqg['subject_id'] == $subject->id) {
                            $ag[] = $sqg['grade'];
                        }
                    }


                    // third quarter grade
                    foreach($third_quarter_grades as $tqg) {
                        if($tqg['student_id'] == $std->user_id && $tqg['subject_id'] == $subject->id) {
                            $ag[] = $tqg['grade'];
                        }
                    }


                    // fourht quarter grade
                    foreach($fourth_quarter_grades as $foqg) {
                        if($foqg['student_id'] == $std->user_id && $foqg['subject_id'] == $subject->id) {
                            $ag[] = $foqg['grade'];
                        }
                    }


                    // compute average per subject here
                    $students_grades [] = ['student_id' => $std->user_id, 'grade' => array_sum($ag)/4];

                    unset($ag);
                }
            }
        }
        else {
            foreach($section->students as $std) {
                $ag = [];
                foreach($section->grade_level->subjects as $subject) {

                    // first quarter grade
                    foreach($first_sem_grades as $fsg) {
                        if($fsg['student_id'] == $std->user_id && $fsg['subject_id'] == $subject->id) {
                            $ag[] = $fsg['grade'];
                        }
                    }

                    // second quarter grade
                    foreach($second_sem_grades as $ssg) {
                        if($ssg['student_id'] == $std->user_id && $ssg['subject_id'] == $subject->id) {
                            $ag[] = $ssg['grade'];
                        }
                    }


                    // compute average per subject here
                    $students_grades [] = ['student_id' => $std->user_id, 'grade' => array_sum($ag)/4];

                    unset($ag);
                }
            } 
        }


        // get final average for all grade levels
        $average_grades = [];


        foreach($section->students as $std) {
            $all = [];
            $count = 0;
            foreach($students_grades as $grade) {
                if($grade['student_id'] == $std->user_id) {
                    $all[] = $grade['grade'];
                    $count++;
                }
            }

            $average_grades[] = ['student_id' => $std->user_id, 'grade' => array_sum($all)/$count];

        }

        return view('admin.admin-view-section-students-ranking', ['section' => $section, 'students' => $section->students, 'subjects' => $section->grade_level->subjects, 'average_grades' => $average_grades]);
    }


   private function getGrade($i)
    {
        switch ($i) {
            case $i >= 0 && $i <= 3.99:
                return 60;
                break;
            
            case $i >= 4 && $i <= 7.99:
                return 61;
                break;
            
            case $i >= 8 && $i <= 11.99:
                return 62;
                break;
                
            case $i >= 12 && $i <= 15.99:
                return 63;
                break;
            
            case $i >= 16 && $i <= 19.99:
                return 64;
                break;

            case $i >= 20 && $i <= 23.99:
                return 65;
                break;
            
            case $i >= 24 && $i <= 27.99:
                return 66;
                break;
            
            case $i >= 28 && $i <= 31.99:
                return 67;
                break;
            
            case $i >= 32 && $i <= 35.99:
                return 68;
                break;

            case $i >= 36 && $i <= 39.99:
                return 69;
                break;

            case $i >= 40 && $i <= 43.99:
                return 70;
                break;

            case $i >= 44 && $i <= 47.99:
                return 71;
                break;

            case $i >= 48 && $i <= 51.99:
                return 72;
                break;

            case $i >= 52 && $i <= 55.99:
                return 73;
                break;
            
            case $i >= 56 && $i <= 59.99:
                return 74;
                break;

            case $i >= 60 && $i <= 61.59:
                return 75;
                break;

            case $i >= 61.6 && $i <= 63.19:
                return 76;
                break;

            case $i >= 63.2 && $i <= 64.79:
                return 77;
                break;

            case $i >= 64.8 && $i <= 66.39:
                return 78;
                break;
            
            case $i >= 66.4 && $i <= 67.99:
                return 79;
                break;
            
            case $i >= 68 && $i <= 69.59:
                return 80;
                break;
            
            case $i >= 69.6 && $i <= 71.19:
                return 81;
                break;
            
            case $i >= 71.2 && $i <= 72.79:
                return 82;
                break;
            
            case $i >= 72.8 && $i <= 74.39:
                return 83;
                break;

            case $i >= 74.4 && $i <= 75.99:
                return 84;
                break;

            case $i >= 76 && $i <= 77.59:
                return 85;
                break;

            case $i >= 77.6 && $i <= 79.19:
                return 86;
                break;

            case $i >= 79.2 && $i <= 80.79:
                return 87;
                break;

            case $i >= 80.8 && $i <= 82.39:
                return 88;
                break;

            case $i >= 82.4 && $i <= 83.99:
                return 89;
                break;   

            case $i >= 84 && $i <= 85.59:
                return 90;
                break;                     

            case $i >= 85.6 && $i <= 87.19:
                return 91;
                break;

            case $i >= 87.2 && $i <= 88.79:
                return 92;
                break;

            case $i >= 88.8 && $i <= 90.39:
                return 93;
                break;

            case $i >= 90.4 && $i <= 91.99:
                return 94;
                break;

            case $i >= 92 && $i <= 93.59:
                return 95;
                break;

            case $i >= 93.6 && $i <= 95.19:
                return 96;
                break;

            case $i >= 95.2 && $i <= 96.79:
                return 97;
                break;

            case $i >= 96.8 && $i <= 98.39:
                return 98;
                break;

            case $i >= 98.4 && $i <= 99.9:
                return 99;
                break;

            case 100:
                return 100;
                break;

            default:
                return 'N/A';
                break;
        }
    }

}