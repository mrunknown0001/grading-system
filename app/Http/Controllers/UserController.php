<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Auth;

use App\UserLog;
use App\User;
use App\SchoolYear;
use App\Quarter;
use App\Semester;

class UserController extends Controller
{

	/*
	 * postLogin() method use to login admin and co-admins
	 */ 
	public function postLogin(Request $request)
	{
		/*
		 * Validating Input from Admin and Co-Admin Login form
		 */
		$this->validate($request, [
			'id' => 'required',
			'password' => 'required'
			]);

		/*
		 * Assigning values to variables
		 */
		$id = $request['id'];
		$password = $request['password'];


		$school_year = SchoolYear::whereStatus(1)->first();
		$quater = Quarter::whereStatus(1)->first();
		$semester = Semester::whereStatus(1)->first();


		/*
		 * Authentication Login attemp
		 */
		if(Auth::attempt(['user_id' => $id, 'password' => $password], True)) {
            
			/*
			 * Check if the user is inactive
			 * the user will not login and redirect to login with error message
			 */
			if(Auth::user()->status != 1) {
				Auth::logout();
				return redirect()->back()->with('error_msg', 'Your Accout is Inactive! Please Report to Admin.');
			}

            /*
             * Redirect to Admin Panel if privilege is admin
             */
            if(Auth::user()->privilege == 1) {

            	/*
            	 * User Log
            	 */
            	$user_log = new UserLog();

            	$user_log->user_id = Auth::user()->id;
            	$user_log->action = 'Admin Login';

            	$user_log->save();

				return redirect()->route('admin_dashboard');
			}

			/*
			 * Redirect to Co-Admin Panel if privilege is co-admin
			 */
			if(Auth::user()->privilege == 2) {

				if(count($school_year) == 0) {
					Auth::logout();
					return 'System is initializing by admin';
				}

				if(count($quater) == 0) {
					Auth::logout();
					return 'System is initializing by admin';
				}

				if(count($semester) == 0) {
					Auth::logout();
					return 'System is initializing by admin';
				}


				/*
            	 * User Log
            	 */
            	$user_log = new UserLog();

            	$user_log->user_id = Auth::user()->id;
            	$user_log->action = 'Teacher\'s Login: ' . Auth::user()->user_id;

            	$user_log->save();

				return redirect()->route('teacher_dashboard');;
			}

			if(Auth::user()->privilege == 3) {


				if(count($school_year) == 0) {
					Auth::logout();
					return 'System is initializing by admin';
				}

				if(count($quater) == 0) {
					Auth::logout();
					return 'System is initializing by admin';
				}

				if(count($semester) == 0) {
					Auth::logout();
					return 'System is initializing by admin';
				}

				/*
            	 * User Log
            	 */
            	$user_log = new UserLog();

            	$user_log->user_id = Auth::user()->id;
            	$user_log->action = 'Student Login: ' . Auth::user()->user_id;

            	$user_log->save();

				return redirect()->route('get_student_dashboard');;
			}



			/*
			 * redirect to home page and will login if stuents
			 */
			if(Auth::user()->privilege == 3) {
				Auth::logout();
				return redirect()->route('student_login')->with('error_msg', 'Use this login form');
			}


			// if there is something error
			Auth::logout();
			return redirect()->route('home')->with('error_msg', 'App has encountered error. Please reload this page.');

    	}

    	/*
    	 * Redirect to Login form if the user id or password is incorrect or if not in database
    	 */
    	return redirect()->back()->with('error_msg', 'ID or Password Incorrect!');
	}



	/*
	 * This method is use to logout users
	 */
	public function getLogout()
	{

		if(empty(Auth::user())) {
			return redirect()->route('landing_page')->with('notice', 'Login first!');
		}


		/*
		 * UserLog 
		 */

		$user_log = new UserLog();

		$user_log->user_id = Auth::user()->id;
		if(Auth::user()->privilege == 1) {
			$user_log->action = 'Admin Logout';
		}
		elseif(Auth::user()->privilege == 2) {
			$user_log->action = 'Teacher Logout: ' . Auth::user()->user_id;
		}
		else {
			$user_log->action = 'Student Logout: ' . Auth::user()->user_id;
		}
		$user_log->save();


		/*
		 * Script to Logout a logged in user
		 */
		 Auth::logout();

		return redirect()->route('landing_page');
	}



	/*
     * postChangePassword() method to cnhange password for students
     */
    public function postChangePassword(Request $request)
    {
    	/*
    	 * input validation
    	 */
    	$this->validate($request, [
    		'old_password' => 'required',
    		'password' => 'required| min:8 | max:64 | confirmed',
    		'password_confirmation' => 'required'
    		]);

    	
    	// Assign values to variable
    	$old_pass = $request['old_password'];
    	$password = $request['password'];


    	// Check if old password and new password is the same, this is not permitted
    	if($old_pass == $password) {
    		return redirect()->back()->with('error_msg', 'Choose different password from your current password.');    	}

    	// bcrypt (hashed) password of students
    	$hashed_password = Auth::user()->password;

    	// id of the student
    	$user_id = Auth::user()->id;

    	// verify the entered old password
    	$password_compare = password_verify($old_pass, $hashed_password);
    	if($password_compare == True) {
    		$user = User::findorfail($user_id);

    		$user->password = bcrypt($password);

    		$user->save();

    		/*
			 * Save students log
			 */
    		if(Auth::user()->privilege == 3) {
				$students_log = new UserLog();

				$students_log->user_id = Auth::user()->id;
				$students_log->action = 'Student Password Change: ' . Auth::user()->user_id;

				$students_log->save();
			}
			elseif(Auth::user()->privilege == 2 ) {
				$teacher_log = new UserLog();

				$teacher_log->user_id = Auth::user()->id;
				$teacher_log->action = 'Teacher Password Change: ' . Auth::user()->user_id;
				
				$teacher_log->save();
			}
			else {
				$user_log = new UserLog();
					
				$user_log->user_id = $user_id;
				$user_log->action = 'Admin Password Change';

				$user_log->save();
			}


	    	if(Auth::user()->privilege == 1) {
	    		return redirect()->route('admin_dashboard')->with('success', 'Your Password Has Been Successfully Changed!');
	    	}
	    	elseif(Auth::user()->privilege == 2) {
	    		return redirect()->route('teacher_dashboard')->with('success', 'Your Password Has Been Successfully Changed!');
	    	}
	    	// if error occured
	    	else {
	    		return 'Error Occured! Please Reload this page';
	    	}
    	}
    	else {
    		// Wrong Password
    		return redirect()->back()->with('error_msg', 'Your Password is Incorrect! Please Try Again.');
    	}
    
    }

}
