<?php
/*
 * Landing Page of the App
 */
Route::get('/', function () {
    return view('landing-page');
})->name('landing_page');

Route::get('login', function () {
	return redirect()->route('landing_page');
});

/*
 * Admin Login
 */
Route::get('admin/login', function () {
	return view('admin-login');
})->name('admin_login');


/*
 * Teacher Login
 */
Route::get('teacher/login', function () {
	return view('teacher-login');
})->name('teacher_login');


/*
 * Student Login
 */
Route::get('student/login', function () {
	return view('admin-login');
})->name('student_login');


/*
 * Login for all users with filtering/segregation
 */
Route::post('login', [
	'uses' => 'UserController@postLogin',
	'as' => 'post_login'
	]);


/*
 * Logout Route for all users
 */
Route::get('logout', [
	'uses' => 'UserController@getLogout',
	'as' => 'logout'
	]);



/********************************************
*********************************************
************ ADMIN ROUTE GROUP **************
*********************************************
*********************************************/
Route::group(['prefix' => 'admin', 'middleware' => ['auth','checkadmin']], function () {

	Route::get('/', function () {
		return redirect()->route('admin_dashboard');
	});

	Route::get('dashboard', [
		'uses' => 'AdminController@getAdminDashboard',
		'as' => 'admin_dashboard'
		]);


	/*
	 * Route to go to Add Teacher Page
	 */
	Route::get('add-teacher', function () {
		return view('admin.add-teacher');
	})->name('add_teacher');

	Route::post('add-teacher', [
		'uses' => 'AdminController@postAddTeacher',
		'as' => 'post_add_teacher'
		]);


	/*
	 * Rout to getAllTeachers() method to view all teachers in users table
	 */
	Route::get('all-teachers', [
		'uses' => 'AdminController@getAllTeachers',
		'as' => 'get_all_teachers'
		]);


	/*
	 * This route is use to remove/delete teachers
	 */
	Route::get('get/remove/teacher/{id}', [
		'uses' => 'AdminController@getRemoveTeacher',
		'as' => 'get_remove_teacher'
		]);


    /*
     * Route to update teacher details
     */
    Route::get('update-teacher-details/{id}', [
    	'uses' => 'AdminController@showTeacherProfileEdit',
    	'as' => 'update_teacher_details'
    	]);


    Route::post('update-teacher-details', [
    	'uses' => 'AdminController@postUpdateTeacherDetails',
    	'as' => 'post_update_teacher_details'
    	]);



    /*
     * route to add new subjects
     */
    Route::get('add-subject', [
    	'uses' => 'AdminController@getAddSubject',
    	'as' => 'get_add_subject'
    	]);




    Route::get('view-all-subjects', [
    	'uses' => 'AdminController@getViewAllSubjects',
    	'as' => 'get_view_all_subjects'
    	]);


	// Get all logs route
	Route::get('users-logs', [
		'uses' => 'AdminController@getAllLogs',
		'as' => 'get_all_users_logs'
		]);


});
/*********************************************
********** END OF ROUTE GROUP ADMIN **********
**********************************************/