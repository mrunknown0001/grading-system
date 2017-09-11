<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\UserLog;
use App\GradeLevel;
use App\Subject;

class AdminController extends Controller
{
    /*
     * Admin Dashboard
     */
    public function getAdminDashboard()
    {
    	return view('admin.admin-dashboard');
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
        $add->password = bcrypt('0000'); 
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
     	$teachers = User::where('privilege', 2)->orderBy('user_id', 'asc')->paginate(15);

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
     * getViewAllSubjects
     */
    public function getViewAllSubjects()
    {
        return view('admin.view-all-subjects');
    }



     /*
      * Get All Logs in User_logs table
      * only the admin can only view this logs
      */
     public function getAllLogs()
     {
     	// Get all logs query
     	$logs = UserLog::paginate(15);

     	return $logs;

     }



}
