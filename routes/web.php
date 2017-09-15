<?php
/*
 * Landing Page of the App
 */
Route::get('/', function () {

	/*
	 * Checking if there are authenticated user and redirecting
	 * to the corrent path
	 */
	if(Auth::check()) {
		if(Auth::user()->privilege == 1) {
			return redirect()->route('admin_dashboard');
		}
		else {
			return view('landing_page');
		}
	}

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

    Route::post('add-subject', [
    	'uses' => 'AdminController@postAddSubject',
    	'as' => 'post_add_subject'
		]);



    /*
     * View all subjects
     */
    Route::get('view-all-subjects', [
    	'uses' => 'AdminController@getViewAllSubjects',
    	'as' => 'get_view_all_subjects'
    	]);


    /*
     * show subject details to edit
     */
    Route::get('update-subject-details/{id}', [
    	'uses' => 'AdminController@showSubjectDetailsUpdate',
    	'as' => 'show_subject_details_update'
    	]);

    Route::post('update-subject-details', [
    	'uses' => 'AdminController@postUpdateSubjectDetails',
    	'as' => 'post_update_subject_details'
    	]);


    /*
     * Remove Subject
     */
    Route::get('get/remove/subject/{id}', [
    	'uses' => 'AdminController@getRemoveSubject',
    	'as' => 'get_remove_subject'
    	]);


    /*
     * add section
     */
    Route::get('add-section', [
    	'uses' => 'AdminController@getAddSection',
    	'as' => 'add_section'
    	]);

    Route::post('add-section', [
    	'uses' => 'AdminController@postAddSection',
    	'as' => 'post_add_section'
    	]);


    /*
     * get all section / view all sections
     */
    Route::get('all-sections', [
    	'uses' => 'AdminController@getAllSections',
    	'as' => 'get_all_sections'
    	]);


    /*
     * update section details
     */
    Route::get('update-section-details/{id}', [
    	'uses' => 'AdminController@showSectionUpdateDetails',
    	'as' => 'get_update_section_details'
    	]);


    Route::post('update-section-details', [
    	'uses' => 'AdminController@postUpdateSectionDetails',
    	'as' => 'post_update_section_details'
    	]);


    /*
     * route to remvoe all sections
     */
    Route::get('get/remove/section/{id}', [
    	'uses' => 'AdminController@getRemoveSection',
    	'as' => 'get_remove_section'
    	]);



    /*
     * route to go to add student page
     */
    Route::get('add-student', [
    	'uses' => 'AdminController@getAddStudent',
    	'as' => 'get_add_student'
    	]);

    Route::post('add-student', [
    	'uses' => 'AdminController@postAddStudent',
    	'as' => 'post_add_student'
    	]);


    /*
     * add school year
     */
    Route::get('add-school-year', function () {
    	return view('admin.add-school-year');
    })->name('add_school_year');

    Route::post('add-school-year', [
    	'uses' => 'AdminController@postAddSchoolYear',
    	'as' => 'post_add_school_year'
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