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
        elseif(Auth::user()->privilege == 2) {
            return redirect()->route('teacher_dashboard');
        }
		else {
			return view('landing-page');
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
	return view('student-login');
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



Route::get('mission-vision', function () {
    return view('mission-vision');
})->name('mission_vision');


Route::get('developers', function () {
    return view('developers');
})->name('about_developers');


Route::get('contact-us', function () {
    return view('contact-us');
})->name('contact_us');


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



    // route to add/update view email of the admin
    Route::get('email/update', [
        'uses' => 'AdminController@adminEmail',
        'as' => 'admin_email'
    ]);


    // rout to update email of the admin
    Route::post('email/update', [
        'uses' => 'AdminController@postAdminEmail',
        'as' => 'post_admin_email'
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
    Route::get('update-student-details/{student_id}', [
    	'uses' => 'AdminController@getUpdateStudentDetails',
    	'as' => 'get_update_student_details'
    	]);
 	// This route redirected to 404 if url is no id
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


    // route to change profile picture of admin
    Route::get('profile/picture/change', [
        'uses' => 'AdminController@adminChangeProfilePicture',
        'as' => 'admin_profile_picture_change'
    ]); 


    // route to post change profile picture of admin
    Route::post('profile/picture/change', [
        'uses' => 'AdminController@adminPostChangeProfilePicture',
        'as' => 'post_admin_profile_picture_change'
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
    Route::get('reselect-quarter/{id}', [
        'uses' => 'AdminController@adminReselectQuarter',
        'as' => 'admin_reselect_quater'
    ]);

    /*
     * route to select semester
     */
    Route::get('select-semster', [
        'uses' => 'AdminController@selectSemester',
        'as' => 'select_semester'
        ]);



    // route to reselect semester
    Route::get('reselect-semester/{id}', [
        'uses' => 'AdminController@adminReselectSemester',
        'as' => 'admin_reselect_semester'
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


    /*
     * route to assign subject in teacher
     */
    Route::get('assign/subject/level/{id}', [
        'uses' => 'AdminController@assignSubjectLevel',
        'as' => 'assign_subject_level'
    ]);
    
    Route::get('assign/subject/level', function () {
        abort(404);
    })
    ;
    Route::post('assign/subject/level', [
        'uses' => 'AdminController@postAssignSubjectLevel',
        'as' => 'post_assign_subject_level'
    ]);


    // route to view all assigned subjects to teachers
    Route::get('view/subject/assignments', [
        'uses' => 'AdminController@viewsubjectAssignments',
        'as' => 'view_subject_assignments'
        ]);


    // route to update view subject assign
    Route::get('update/subject/assignment/{id}', [
        'uses' => 'AdminController@updateSubjectAssignment',
        'as' => 'update_subject_assigment'
    ]);

    Route::post('update/subject/assignment', [
        'uses' => 'AdminController@postUpdateSubjectAssign',
        'as' => 'post_update_subject_assignment'
    ]);


    // route to view all sections in grade level
    Route::get('view/grade-level/{id}/sections', [
        'uses' => 'AdminController@viewSectionsGradeLevel',
        'as' => 'view_sections_grade_level'
    ]);





    // route to finished or close school year
    Route::post('school-year/close', [
        'uses' => 'AdminController@postAdminCloseSchoolYear',
        'as' => 'post_admin_close_school_year'
    ]);



    // route students per section in grade level by admin
    Route::get('view/grade-level/{levelid}/section/{sectionid}/students', [
        'uses' => 'AdminController@adminViewSectionStudents',
        'as' => 'admin_view_section_students'
    ]);
    

    // route to start new school year
    Route::post('start/schoo-year', [
        'uses' => 'AdminController@startSchoolYear',
        'as' => 'admin_start_school_year'
    ]);



    // route to compute grade of students per section
    Route::get('compute/{section_id}', [
        'uses' => 'AdminController@adminComputeGrades',
        'as' => 'admin_compute_grades'
    ]);


    // ranking
    Route::get('ranking/section/{sectionid}/view', [
        'uses' => 'AdminController@adminViewRanking',
        'as' => 'admin_view_ranking'
    ]);


    // rout to search students
    Route::post('student/search', [
        'uses' => 'AdminController@adminSearchStudent',
        'as' => 'post_admin_search_student'
    ]);
    Route::get('student/search', function () {
        return redirect()->route('get_view_all_students');
    });


    // route to remove students in the database
    Route::post('student/remove', [
        'uses' => 'AdminController@adminRemoveStudent',
        'as' => 'post_admin_remove_student'
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



    // route to change password of the teacher
    Route::get('password/change', [
        'uses' => 'TeacherController@teacherPasswordChange',
        'as' => 'teacher_password_change'
    ]);

    Route::post('password', [
        'uses' => 'UserController@postChangePassword',
        'as' => 'post_teacher_password_change'
    ]);



    // route to view profile of the teacher
    Route::get('profile/view', [
        'uses' => 'TeacherController@viewTeacherProfile',
        'as' => 'view_teacher_profile'
    ]);



    // route to change profile picture of the teacher
    Route::get('profile/picture/change', [
        'uses' => 'TeacherController@teacherChangeProfilePicture',
        'as' => 'teacher_change_profile_picture'
    ]);

    Route::post('profile/picture/change', [
        'uses' => 'TeacherController@postTeacherChangeProfilePicture',
        'as' => 'post_teacher_change_profile_picture'
    ]);


    Route::get('students/subject/{id}/all', [
        'uses' => 'TeacherController@getStudentClassSubject',
        'as' => 'get_student_class_subject'
    ]);


    // route to add written work in record of the students
    Route::get('add/written-work/section/{section_id}/subject/{subject_id}/{assign_id}/get', [
        'uses' => 'TeacherController@addWrittenWorkScore',
        'as' => 'add_written_work_score'
    ]);

    // route to add writen work post
    Route::post('add/written-work', [
        'uses' => 'TeacherController@postAddWrittenWork',
        'as' => 'post_add_written_work_score'
    ]);
    Route::get('add/written-work', function () {
        abort(404);
    });


    // route to add performance task in record of the students
    Route::get('add/performance-task/section/{section_id}/subject/{subject_id}/{assign_id}/get', [
        'uses' => 'TeacherController@addPerformanceTask',
        'as' => 'add_performance_task_score'
    ]);



    // route to add performance task
    Route::post('add/performance-task', [
        'uses' => 'TeacherController@postAddPerformanceTask',
        'as' => 'post_add_performance_task'
    ]);


    // route update performance task
    Route::get('update/performance-task/score/{sectionid}/{subjectid}/{assignid}/get', [
        'uses' => 'TeacherController@updatePerformanceTaskScore',
        'as' => 'update_performance_task_score'
    ]);


    // route post update performance task
    Route::post('update/performance-task/score', [
        'uses' => 'TeacherController@postUpdatePerformanceTaskScore',
        'as' => 'post_update_performance_task_score'
    ]);


    // route to add exam in record of the students
    Route::get('add/exam/section/{section_id}/subject/{subject_id}/{assign_id}/get', [
        'uses' => 'TeacherController@addExam',
        'as' => 'add_exam_score'
    ]);


    // route to add exam
    Route::post('add/exam', [
        'uses' => 'TeacherController@postAddExam',
        'as' => 'post_add_exam_score'
    ]);



    // route to view written work scores
    Route::get('view/written-work/score/{sectionid}/{subjectid}/{assignid}/get', [
        'uses' => 'TeacherController@viewWrittenWorkScore',
        'as' => 'view_written_work_score'
    ]);


    // route to update individual score of student in written work
    Route::get('update/written-work/score/{id}/{user_id}/{assignid}', [
        'uses' => 'TeacherController@updateWrittenWorkScore',
        'as' => 'update_written_work_score'
    ]);


    // route to post update writen work score
    Route::post('update/written-work/score', [
        'uses' => 'TeacherController@postUpdateWrittenWorkScore',
        'as' => 'post_update_written_work_score'
    ]);


    // route to view performance task scores
    Route::get('view/performance-task/score/{sectionid}/{subjectid}/{assignid}/get', [
        'uses' => 'TeacherController@viwePerformanceTask',
        'as' => 'view_performance_task_score'
    ]);


    // route to view exam scores
    Route::get('view/exam/score/{sectionid}/{subjectid}/{assignid}/get', [
        'uses' => 'TeacherController@viewExamScore',
        'as' => 'view_exam_score'
    ]);


    // route to view grades per subject
    Route::get('grade/{sectionid}/{subjectid}/{assignid}/view', [
        'uses' => 'TeacherController@viewSubjectGrades',
        'as' => 'teacher_view_subject_grades'
    ]);


    // route to view raw score percentage
    Route::get('percentage/{sectionid}/{subjectid}/{assignid}/score/view', [
        'uses' => 'TeacherController@viewPercentageScores',
        'as' => 'teacher_view_percentange_scores'
    ]);


    // route to edit exam score
    Route::get('update/exam/score/{id}/{user_id}/{assignid}', [
        'uses' => 'TeacherController@updateExamScore',
        'as' => 'update_exam_score'
    ]);
    
    // route to POST edit exam score
    Route::post('update/exam/score', [
        'uses' => 'TeacherController@postUpdateExamScore',
        'as' => 'post_update_exam_score'
    ]);
    

    // route to messages
    Route::get('messages', [
        'uses' => 'TeacherController@getMessages',
        'as' => 'teacher_get_messages'
    ]);





    // route to message thread 
    Route::get('message/student/{student_id}/thread', [
        'uses' => 'TeacherController@studentMessageThread',
        'as' => 'teacher_student_message_thread'
    ]);


    // route to send message
    Route::post('message/send', [
        'uses' => 'TeacherController@teacherSendMessage',
        'as' => 'teacher_send_message'
    ]);
    Route::get('message/send', function () {
        return redirect()->route('teacher_student_message_thread');
    });
});
/*********************************************
********** END OF ROUTE GROUP TEACHER ********
**********************************************/




/**********************************************
***********************************************
************ STUDENT ROUTE GROUP **************
***********************************************
***********************************************/
Route::group(['prefix' => 'student', 'middleware' => ['auth', 'checkstudent']], function () {

    // route to student dashbaord
    Route::get('/', [
        'uses' => 'StudentController@getStudentDashboard',
        'as' => 'get_student_dashboard'
    ]);



    // route to show option for each subject
    Route::get('subject/{id}/view', [
        'uses' => 'StudentController@studentSubjectView',
        'as' => 'student_subject_view'
    ]);


    // route to view profile of the stuent
    Route::get('profile', [
        'uses' => 'StudentController@viewProfile',
        'as' => 'student_view_profile'
    ]);


    // route to change password student
    Route::get('password/change', [
        'uses' => 'StudentController@studentChangePassword',
        'as' => 'student_change_password'
    ]);


    // post route change password
    Route::post('password/change', [
        'uses' => 'UserController@postChangePassword',
        'as' => 'post_student_change_password'
    ]);



    // route to change profile picture of students
    Route::get('profile/picture/change', [
        'uses' => 'StudentController@studentProfilePictureChange',
        'as' => 'student_profile_picture_change'
    ]);

    Route::post('profile/picture/change', [
        'uses' => 'StudentController@postProfilePictureChange',
        'as' => 'post_profile_picture_change'
    ]);


    // route to show written scores of the student
    Route::get('score/written-works/{year_id}/{section}/{subject}/{student_number}/view', [
        'uses' => 'StudentController@viewWrittenWorkScores',
        'as' => 'student_view_written_works'
    ]);


    // route to show performance task scores of student
    Route::get('score/performance-task/{year_id}/{section}/{subject}/{student_number}/view', [
        'uses' => 'StudentController@viwePerformanceTask',
        'as' => 'student_view_performance_tasks'
    ]);


    // route to show exam score of the student
    Route::get('score/exam/{year_id}/{section}/{subject}/{student_number}/view', [
        'uses' => 'StudentController@viewExamScore',
        'as' => 'student_view_exams'
    ]);


    // route to view grade of the student
    Route::get('my-grades/view', [
        'uses' => 'StudentController@studentViewGrades',
        'as' => 'student_view_grades'
    ]);



    // route to view old student grades
    Route::get('old-grades/{id}/view', [
        'uses' => 'StudentController@studentViewOldGrades',
        'as' => 'student_view_old_grades'
    ]);



    // route to view old grades
    Route::get('previews-grades/view', [
        'uses' => 'StudentController@viewPreviewsGrades',
        'as' => 'student_view_previews_grades'
    ]);


    // route to messages of student
    Route::get('messages', [
        'uses' => 'StudentController@studentMessages',
        'as' => 'student_messages'
    ]);


    // route to show conversation
    Route::get('message/{teacher_id}/thread', [
        'uses' => 'StudentController@studentMessageThread',
        'as' => 'student_message_thread'
    ]);


    // route to send message
    Route::post('message/send', [
        'uses' => 'StudentController@studentSendMessage',
        'as' => 'student_send_message'
    ]);

});
/*********************************************
********** END OF ROUTE GROUP TEACHER ********
**********************************************/


// route to reset password
Route::get('password/reset', [
    'uses' => 'UserController@passwordReset',
    'as' => 'password_reset'
]);

Route::post('password/reset', [
    'uses' => 'UserController@postPasswordReset',
    'as' => 'post_password_reset'
]);


// route use to reset password with the given reset code
Route::get('password/reset/code/{code}', [
    'uses' => 'UserController@passwordResetCode'
]);


Route::post('password/reset/last', [
    'uses' => 'UserController@passwordResetLast',
    'as' => 'password_reset_last'
]);