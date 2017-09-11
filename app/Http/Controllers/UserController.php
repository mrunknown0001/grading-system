<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Auth;

use App\UserLog;

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

				/*
            	 * User Log
            	 */
            	$user_log = new UserLog();

            	$user_log->user_id = Auth::user()->id;
            	$user_log->action = 'Teacher\'s Login';

            	$user_log->save();

				return "Redirect to Teacher Dashboard";
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
		$user_log->action = 'Admin Logout';

		$user_log->save();


		/*
		 * Script to Logout a logged in user
		 */
		 Auth::logout();

		return redirect()->route('landing_page');
	}

}
