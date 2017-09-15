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

class AdminController extends Controller
{
    /*
     * Admin Dashboard
     */
    public function getAdminDashboard()
    {
        $school_year = SchoolYear::where('status', 1)->first();

    	return view('admin.admin-dashboard', ['school_year' => $school_year]);
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
     		'id_number' => 'required|unique:users',
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
        $user = User::where('user_id', $user_id)->first();

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
        $levels = GradeLevel::all();
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
            'description' => 'required'
            ]);

        // Assigning variables
        $level = $request['grade_level'];
        $title = $request['title'];
        $description = $request['description'];

        $add = new Subject();

        $add->level = $level;
        $add->title = $title;
        $add->description = $description;


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
        $levels = GradeLevel::all();

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
            'description' => 'required'
            ]);

        $id = $request['id'];
        $level = $request['grade_level'];
        $title = $request['title'];
        $description = $request['description'];

        // If id is empty
        if(empty($id)) {
            return 'System encountered error. Please reload this page.';
        }

        $subject = Subject::findorfail($id);

        if(empty($subject)) {
            // If id is not on database
            return abort(404);
        }

        $subject->level = $level;
        $subject->title = $title;
        $subject->description = $description;

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
        $levels = GradeLevel::all();

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

        $add = new Section();

        $add->level = $level;
        $add->name = $name;

        if($add->save()) {

            $log = new UserLog();

            $log->user_id = Auth::user()->id;
            $log->action = 'Added Section: ' . ucwords($name);
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
        $levels = GradeLevel::all();

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
            'student_number' => 'required|unique:users',
            'firstname' => 'required',
            'lastname' => 'required',
            'birthday' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:users',
            'mobile' => 'required'
            ]);

        $section = $request['section'];
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
        $add->password = bcrypt('concs2017'); 
        $add->privilege = 3;
        $add->status = 1;

        if($add->save()) {

            // Add log to admin
            $log = new UserLog();

            $log->user_id = Auth::user()->id;
            $log->action = 'Added Teacher with User ID Number: ' . $user_id;

            $log->save();

            return redirect()->route('get_add_student')->with('success', 'Student Added: ' . ucwords($firstname) . ' ' . ucwords($lastname));

        }

        // If something is wrong
        return redirect()->route('get_add_student')->with('error_msg', 'Something is Wrong! Please reload this page.');

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

            return redirect()->route('add_school_year')->with('success', 'School Year Successfully Added! You can now select a First Quarter and First Semester');
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



}
