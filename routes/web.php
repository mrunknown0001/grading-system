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
	//This route redirected to 404 if url is no id
	Route::get('get/remove/teacher', function () {
		abort(404);
	});


    /*
     * Route to update teacher details
     */
    Route::get('update-teacher-details/{id}', [
    	'uses' => 'AdminController@showTeacherProfileEdit',
    	'as' => 'update_teacher_details'
    	]);
	//This route redirected to 404 if url is no id
	Route::get('update-teacher-details', function () {
		abort(404);
	});

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
	//This route redirected to 404 if url is no id
	Route::get('update-subject-details', function () {
		abort(404);
	});

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
	//This route redirected to 404 if url is no id
	Route::get('get/remove/subject', function () {
		abort(404);
	});


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
	//This route redirected to 404 if url is no id
	Route::get('update-section-details', function () {
		abort(404);
	});


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
	//This route redirected to 404 if url is no id
	Route::get('get/remove/section', function () {
		abort(404);
	});



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
     * Batch import students 
     */
    Route::get('import-students', [
        'uses' => 'AdminController@importStudents',
        'as' => 'import_students'
    ]);
    
    Route::post('import-students', [
        'uses' => 'AdminController@postImportStudents',
        'as' => 'post_import_students'
    ]);


    /*
     * route use to view all students
     */
    Route::get('view-all-students', [
    	'uses' => 'AdminController@getViewAllStudents',
    	'as' => 'get_view_all_students'
    	]);


    /*
     * update student details
     */
    Route::get('update-student-details/{id}', [
    	'uses' => 'AdminController@getUpdateStudentDetails',
    	'as' => 'get_update_student_details'
    	]);
 	//This route redirected to 404 if url is no id
	Route::get('update-student-details', function () {
		abort(404);
	});

    Route::post('update-student-details', [
    	'uses' => 'AdminController@postUpdateStudentDetails',
    	'as' => 'post_update_student_details'
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


    // ADMIN CHANGE PASSWORD
    Route::get('change-password', function () {
        return view('admin.admin-change-password');
    })->name('admin_change_password');

    Route::post('change-password', [
        'uses' => 'UserController@postChangePassword',
        'as' => 'post_admin_change_password'
    ]);


    /*
     * viewProfile view admin profile
     */
    Route::get('profile', [
        'uses' => 'AdminController@viewProfile',
        'as' => 'admin_profile'
        ]);


    /*
     * route to go to, select quarter 
     */
    Route::get('select-quarter', [
        'uses' => 'AdminController@selectQuarter',
        'as' => 'select_quarter'
        ]);

    Route::get('select-active-quarter/{id}',[
        'uses' => 'AdminController@selectActiveQuarter',
        'as' => 'select_active_quarter'
        ]);

    /*
     * route to finish selected quarter
     */
    Route::get('finish-selected-quarter/{id}', [
        'uses' => 'AdminController@finishSelectedQuarter',
        'as' => 'finish_selected_quarter'
        ]);


    // reselect quarter
    // here

    /*
     * route to select semester
     */
    Route::get('select-semster', [
        'uses' => 'AdminController@selectSemester',
        'as' => 'select_semester'
        ]);

    Route::get('select-active-semester/{id}', [
        'uses' => 'AdminController@selectActiveSemester',
        'as' => 'select_active_semester'
        ]);

    // route to finish selected semester
    Route::get('finish-selected-semester/{id}', [
        'uses' => 'AdminController@finishSelectedSemester',
        'as' => 'finish_selected_semester'
        ]);

    

});
/*********************************************
********** END OF ROUTE GROUP ADMIN **********
**********************************************/



/**********************************************
***********************************************
************ TEACHER ROUTE GROUP **************
***********************************************
***********************************************/
Route::group(['prefix' => 'teacher', 'middleware' => ['auth', 'checkteacher']], function () {

    Route::get('dashboard', [
        'uses' => 'TeacherController@teacherDasboard',
        'as' => 'teacher_dashboard'
    ]);
});
/*********************************************
********** END OF ROUTE GROUP TEACHER ********
**********************************************/