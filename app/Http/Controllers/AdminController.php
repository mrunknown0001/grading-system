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

        if(!empty($data)){
            foreach ($data as $value) {
                
                foreach ($value as $row) {
                    if($row->student_number != null) {

                        // check each student number if it is already in database
                        $check_student_number = User::where('user_id', $row->student_number)->first();
                        if(!empty($check_student_number)) {
                            return redirect()->route('import_students')->with('notice', 'Student Number: ' . $check_student_number->user_id . ' is already in record. Please double check your sheet before uploading. Remove any recorded students in sheet.');
                        }


                        // check email
                        $check_email = User::where('email', $row->email)->first();
                        if(!empty($check_email)) {
                            return redirect()->route('import_students')->with('notice', 'Duplicate email found: ' . $row->email .' Emails can be used only once. Please Check your sheet for any duplicate record.');
                        }


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

        // insert data to users
        DB::table('users')->insert($insert);


        // insert import data to studentimport
        DB::table('student_infos')->insert($info);


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
        $sections = Section::where('level', $id)->get();


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
        
        
    }



    // method use to view sections of all grade level
    public function viewSectionsGradeLevel($id = null) // id of the grade level
    {
        $sections = Section::whereLevel($id)->get();
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
        $sy->finish = 1;
        $sy->status = 0;
        $sy->save();

        Quarter::whereFinish(1)->update(['finish' => 0]);
        Quarter::whereStatus(1)->update(['status' => 0]);
        Semester::whereFinish(1)->update(['finish' => 0]);
        Semester::whereStatus(1)->update(['status' => 0]);



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
}