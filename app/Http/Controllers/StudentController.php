<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use DB;

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
use App\Message;

class StudentController extends Controller
{
    // student dashboard
    public function getStudentDashboard()
    {
    	return view('student.student-dashboard');
    }



    // method to view subject
    public function studentSubjectView($id = null)
    {
        $subject = Subject::findorfail($id);

        $std_info = StudentInfo::where('user_id', Auth::user()->user_id)->first();
        $year = SchoolYear::whereStatus(1)->first();

        return view('student.student-subject-view', ['subject' => $subject, 'info' => $std_info, 'year' => $year]);
    }



    // method use to view written works of the students
    public function viewWrittenWorkScores($year_id = null, $section = null, $subject = null, $student_number)
    {
    	// return 'view';
        $subject = Subject::findorfail($subject);
        $section = Section::findorfail($section);

        $school_year = SchoolYear::findorfail($year_id);
        $quarter = Quarter::whereStatus(1)->first();
        $semester = Semester::whereStatus(1)->first();

        // check how many written works has taken
        // check also if junior or senior high
        if($section->grade_level->id == 1 || $section->grade_level->id == 2 || $section->grade_level->id == 3 || $section->grade_level->id == 4) {

            $ww_number = WrittenWorkNumber::where('school_year_id', $school_year->id)
                                        ->where('quarter_id', $quarter->id)
                                        ->where('section_id', $section->id)
                                        ->where('subject_id', $subject->id)
                                        ->first();
        }
        else {
            $ww_number = WrittenWorkNumber::where('school_year_id', $school_year->id)
                                        ->where('semester_id', $semester->id)
                                        ->where('section_id', $section->id)
                                        ->where('subject_id', $subject->id)
                                        ->first();
        }


        if(count($ww_number) == 0) {
            return view('student.includes.no-scores-for-the-subject');
        }

        // get all scores in the quarter/sem using the id of the written work
        $scores = WrittenWorkScore::where('written_work_id', $ww_number->id)->where('student_number', Auth::user()->user_id)->get();

        if(count($scores) == 0) {
            return 'No Scores Yet for this Subject';
        }

        return view('student.student-view-written-work-score', ['subject' => $subject, 'section' => $section, 'ww_number' => $ww_number, 'scores' => $scores]);
    }



    // method use to view performance task of the student
    public function viwePerformanceTask($year_id = null, $section = null, $subject = null, $student_number)
    {
        // return 'view';
        $subject = Subject::findorfail($subject);
        $section = Section::findorfail($section);

        $school_year = SchoolYear::findorfail($year_id);
        $quarter = Quarter::whereStatus(1)->first();
        $semester = Semester::whereStatus(1)->first();

        // check how many written works has taken
        // check also if junior or senior high
        if($section->grade_level->id == 1 || $section->grade_level->id == 2 || $section->grade_level->id == 3 || $section->grade_level->id == 4) {

            $ptn = PerformanceTaskNumber::where('school_year_id', $school_year->id)
                                        ->where('quarter_id', $quarter->id)
                                        ->where('section_id', $section->id)
                                        ->where('subject_id', $subject->id)
                                        ->first();
        }
        else {
            $ptn = PerformanceTaskNumber::where('school_year_id', $school_year->id)
                                        ->where('semester_id', $semester->id)
                                        ->where('section_id', $section->id)
                                        ->where('subject_id', $subject->id)
                                        ->first();
        }


        if(count($ptn) == 0) {
            return view('student.includes.no-scores-for-the-subject');
        }

        // get all scores in the quarter/sem using the id of the written work
        $scores = PerformanceTaskScore::where('performance_task_id', $ptn->id)->where('student_number', Auth::user()->user_id)->get();

        if(count($scores) == 0) {
            return 'No Scores Yet for this Subject';
        }

        return view('student.student-view-performance-task-score', ['subject' => $subject, 'section' => $section, 'ptn' => $ptn, 'scores' => $scores]);
    }



    // method use to view exam
    public function viewExamScore($year_id = null, $section = null, $subject = null, $student_number)
    {
        // return 'view';
        $subject = Subject::findorfail($subject);
        $section = Section::findorfail($section);

        $school_year = SchoolYear::findorfail($year_id);
        $quarter = Quarter::whereStatus(1)->first();
        $semester = Semester::whereStatus(1)->first();

        // check how many written works has taken
        // check also if junior or senior high
        if($section->grade_level->id == 1 || $section->grade_level->id == 2 || $section->grade_level->id == 3 || $section->grade_level->id == 4) {

            $exam = ExamScoreNumber::where('school_year_id', $school_year->id)
                                        ->where('quarter_id', $quarter->id)
                                        ->where('section_id', $section->id)
                                        ->where('subject_id', $subject->id)
                                        ->first();
        }
        else {
            $exam = ExamScoreNumber::where('school_year_id', $school_year->id)
                                        ->where('semester_id', $semester->id)
                                        ->where('section_id', $section->id)
                                        ->where('subject_id', $subject->id)
                                        ->first();
        }


        if(count($exam) == 0) {
            return view('student.includes.no-scores-for-the-subject');
        }

        // get all scores in the quarter/sem using the id of the written work
        $scores = ExamScore::where('exam_id', $exam->id)->where('student_number', Auth::user()->user_id)->get();

        if(count($scores) == 0) {
            return 'No Scores Yet for this Subject';
        }

        return view('student.student-view-exam-score', ['subject' => $subject, 'section' => $section, 'exam' => $exam, 'scores' => $scores]);
    }



    // method use to view grades of the student
    public function studentViewGrades()
    {

        $asy = SchoolYear::whereStatus(1)->first();
        $quarter = Quarter::whereStatus(1)->first();
        $semester = Semester::whereStatus(1)->first();

        $finished_quarter = Quarter::whereFinish(1)->first();
        $finished_sem = Semester::whereFinish(1)->first();

        $first_quarter = Quarter::findorfail(1);
        $second_quarter = Quarter::findorfail(2);
        $third_quarter = Quarter::findorfail(3);
        $fourth_quarter = Quarter::findorfail(4);

        $first_sem = Semester::findorfail(1);
        $second_sem = Semester::findorfail(2);


        $section_id = Auth::user()->info->section1->id;
        $level_id = Auth::user()->info->section1->grade_level->id;
        $subjects = Auth::user()->info->section1->grade_level->subjects;


        $section = Section::find($section_id);

        // get all raw grades per subject per quarter or semester
        // for senior high
        if($level_id == 5 || $level_id ==6) {
            $fsg = [];
            $ssg = [];

            if($first_sem->finish == 1) {
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($subjects as $sub) {
                    // total subject total in first quarter\
                    $ww_scores_s1[] = [
                                    'subject_id' => $sub->id,
                                    'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 1)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 1)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                    ];


                    $pt_scores_s1[] = [
                                    'subject_id' => $sub->id,
                                    'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 1)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 1)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                ];

                    $exam_scores_s1[] = [
                                    'subject_id' => $sub->id,
                                    'score' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 1)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 1)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                ];

                }



                foreach($subjects as $sub) {
                    $ww_percentage = 0;
                    $pt_percentage = 0;
                    $exam_percentage = 0;

                    foreach ($ww_scores_s1 as $ws) {
                        if($sub->id == $ws['subject_id'] && $ws['score'] != 0) {
                            $ww_percentage = ($ws['score']/$ws['total']) * ($sub->written_work/100);
                        }
                    }

                    foreach ($pt_scores_s1 as $pt) {
                        if($sub->id == $pt['subject_id'] && $pt['score'] != 0) {
                            $pt_percentage = ($pt['score']/$pt['total']) * ($sub->performance_task/100);
                        }
                    }

                    foreach ($exam_scores_s1 as $es) {
                        if($sub->id == $es['subject_id'] && $es['score'] != 0) {
                            $exam_percentage = ($es['score']/$es['total']) * ($sub->exam/100);
                        }
                    }

                    $grade = ($ww_percentage + $pt_percentage + $exam_percentage) * 100;

                    $fsg[] = [
                        'subject_id' => $sub->id,
                        'grade' => $grade
                        ];
                }



            }

            if($second_sem->finish == 1) {
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($subjects as $sub) {
                    // total subject total in first quarter\
                    $ww_scores_s2[] = [
                                    'subject_id' => $sub->id,
                                    'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 2)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 2)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                    ];


                    $pt_scores_s2[] = [
                                    'subject_id' => $sub->id,
                                    'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 2)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 2)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                ];

                    $exam_scores_s2[] = [
                                    'subject_id' => $sub->id,
                                    'score' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 2)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('semester_id', 2)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                ];

                }



                foreach($subjects as $sub) {
                    $ww_percentage = 0;
                    $pt_percentage = 0;
                    $exam_percentage = 0;

                    foreach ($ww_scores_s2 as $ws) {
                        if($sub->id == $ws['subject_id'] && $ws['score'] != 0) {
                            $ww_percentage = ($ws['score']/$ws['total']) * ($sub->written_work/100);
                        }
                    }

                    foreach ($pt_scores_s2 as $pt) {
                        if($sub->id == $pt['subject_id'] && $pt['score'] != 0) {
                            $pt_percentage = ($pt['score']/$pt['total']) * ($sub->performance_task/100);
                        }
                    }

                    foreach ($exam_scores_s2 as $es) {
                        if($sub->id == $es['subject_id'] && $es['score'] != 0) {
                            $exam_percentage = ($es['score']/$es['total']) * ($sub->exam/100);
                        }
                    }

                    $grade = ($ww_percentage + $pt_percentage + $exam_percentage) * 100;

                    $ssg[] = [
                        'subject_id' => $sub->id,
                        'grade' => $grade
                        ];
                }


                

            }


            return view('student.student-view-grades2', ['subjects' => $subjects, 'fsg' => $fsg, 'ssg' => $ssg, 'section' => $section, 'asy' => $asy]);


        }
        // for junior high
        else {
            $fqg = [];
            $sqg = [];
            $tqg = [];
            $foqg = [];
            // for first quarter
            if($first_quarter->finish == 1) {
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($subjects as $sub) {
                    // total subject total in first quarter\
                    $ww_scores_q1[] = [
                                    'subject_id' => $sub->id,
                                    'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 1)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 1)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                    ];


                    $pt_scores_q1[] = [
                                    'subject_id' => $sub->id,
                                    'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 1)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 1)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                ];

                    $exam_scores_q1[] = [
                                    'subject_id' => $sub->id,
                                    'score' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 1)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 1)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                ];

                }



                foreach($subjects as $sub) {
                    $ww_percentage = 0;
                    $pt_percentage = 0;
                    $exam_percentage = 0;

                    foreach ($ww_scores_q1 as $ws) {
                        if($sub->id == $ws['subject_id'] && $ws['score'] != 0) {
                            $ww_percentage = (($ws['score']/$ws['total']) * ($sub->written_work/100)) * 100;
                        }
                    }

                    foreach ($pt_scores_q1 as $pt) {
                        if($sub->id == $pt['subject_id'] && $pt['score'] != 0) {
                            $pt_percentage = (($pt['score']/$pt['total']) * ($sub->performance_task/100)) * 100;
                        }
                    }

                    foreach ($exam_scores_q1 as $es) {
                        if($sub->id == $es['subject_id'] && $es['score'] != 0) {
                            $exam_percentage = (($es['score']/$es['total']) * ($sub->exam/100)) * 100;
                        }
                    }

                    $grade = $ww_percentage + $pt_percentage + $exam_percentage;

                    $fqg[] = [
                        'subject_id' => $sub->id,
                        'grade' => number_format($grade, 0)
                        ];

                }

            }
            
            if($second_quarter->finish == 1) {
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($subjects as $sub) {
                    // total subject total in first quarter\
                    $ww_scores_q2[] = [
                                    'subject_id' => $sub->id,
                                    'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 2)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 2)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                    ];


                    $pt_scores_q2[] = [
                                    'subject_id' => $sub->id,
                                    'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 2)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 2)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                ];

                    $exam_scores_q2[] = [
                                    'subject_id' => $sub->id,
                                    'score' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 2)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 2)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                ];

                }



                foreach($subjects as $sub) {
                    $ww_percentage = 0;
                    $pt_percentage = 0;
                    $exam_percentage = 0;

                    foreach ($ww_scores_q2 as $ws) {
                        if($sub->id == $ws['subject_id'] && $ws['score'] != 0) {
                            $ww_percentage = (($ws['score']/$ws['total']) * ($sub->written_work/100)) * 100;
                        }
                    }

                    foreach ($pt_scores_q2 as $pt) {
                        if($sub->id == $pt['subject_id'] && $pt['score'] != 0) {
                            $pt_percentage = (($pt['score']/$pt['total']) * ($sub->performance_task/100)) * 100;
                        }
                    }

                    foreach ($exam_scores_q2 as $es) {
                        if($sub->id == $es['subject_id'] && $es['score'] != 0) {
                            $exam_percentage = (($es['score']/$es['total']) * ($sub->exam/100)) * 100;
                        }
                    }

                    $grade = $ww_percentage + $pt_percentage + $exam_percentage;

                    $sqg[] = [
                        'subject_id' => $sub->id,
                        'grade' => $grade
                        ];
                }


                

            }

            if($third_quarter->finish == 1) {
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($subjects as $sub) {
                    // total subject total in first quarter\
                    $ww_scores_q3[] = [
                                    'subject_id' => $sub->id,
                                    'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 3)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 3)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                    ];


                    $pt_scores_q3[] = [
                                    'subject_id' => $sub->id,
                                    'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 3)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 3)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                ];

                    $exam_scores_q3[] = [
                                    'subject_id' => $sub->id,
                                    'score' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 3)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 3)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                ];

                }


                foreach($subjects as $sub) {
                    $ww_percentage = 0;
                    $pt_percentage = 0;
                    $exam_percentage = 0;

                    foreach ($ww_scores_q3 as $ws) {
                        if($sub->id == $ws['subject_id'] && $ws['score'] != 0) {
                            $ww_percentage = (($ws['score']/$ws['total']) * ($sub->written_work/100)) * 100;
                        }
                    }

                    foreach ($pt_scores_q3 as $pt) {
                        if($sub->id == $pt['subject_id'] && $pt['score'] != 0) {
                            $pt_percentage = (($pt['score']/$pt['total']) * ($sub->performance_task/100)) * 100;
                        }
                    }

                    foreach ($exam_scores_q3 as $es) {
                        if($sub->id == $es['subject_id'] && $es['score'] != 0) {
                            $exam_percentage = (($es['score']/$es['total']) * ($sub->exam/100)) * 100;
                        }
                    }

                    $grade = $ww_percentage + $pt_percentage + $exam_percentage;

                    $tqg[] = [
                        'subject_id' => $sub->id,
                        'grade' => $grade
                        ];
                }
            }

            if($fourth_quarter->finish == 1) {
                // compute grade here
                // get all raw scores and compute
                // get all written work in first quarter
                foreach($subjects as $sub) {
                    // total subject total in first quarter\
                    $ww_scores_q4[] = [
                                    'subject_id' => $sub->id,
                                    'score' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 4)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => WrittenWorkScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 4)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                    ];


                    $pt_scores_q4[] = [
                                    'subject_id' => $sub->id,
                                    'score' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 4)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => PerformanceTaskScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 4)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                ];

                    $exam_scores_q4[] = [
                                    'subject_id' => $sub->id,
                                    'score' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 4)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('score'),
                                    'total' => ExamScore::where('school_year_id', $asy->id)
                                            ->where('quarter_id', 4)
                                            ->where('section_id', $section_id)
                                            ->where('subject_id', $sub->id)
                                            ->where('student_number', Auth::user()->user_id)
                                            ->sum('total')
                                ];

                }



                foreach($subjects as $sub) {
                    $ww_percentage = 0;
                    $pt_percentage = 0;
                    $exam_percentage = 0;

                    foreach ($ww_scores_q4 as $ws) {
                        if($sub->id == $ws['subject_id'] && $ws['score'] != 0) {
                            $ww_percentage = (($ws['score']/$ws['total']) * ($sub->written_work/100)) * 100;
                        }
                    }

                    foreach ($pt_scores_q4 as $pt) {
                        if($sub->id == $pt['subject_id'] && $pt['score'] != 0) {
                            $pt_percentage = (($pt['score']/$pt['total']) * ($sub->performance_task/100)) * 100;
                        }
                    }

                    foreach ($exam_scores_q4 as $es) {
                        if($sub->id == $es['subject_id'] && $es['score'] != 0) {
                            $exam_percentage = (($es['score']/$es['total']) * ($sub->exam/100)) * 100;
                        }
                    }

                    $grade = $ww_percentage + $pt_percentage + $exam_percentage;

                    $foqg[] = [
                        'subject_id' => $sub->id,
                        'grade' => $grade
                        ];
                }

            }



            return view('student.student-view-grades', ['subjects' => $subjects, 'fqg' => $fqg, 'sqg' => $sqg, 'tqg' => $tqg, 'foqg' => $foqg, 'section' => $section, 'asy' => $asy]);
            
            
        }




    }



    // method to view profile of students
    public function viewProfile()
    {
        return view('student.student-view-profile');
    }




    // method use to view change password
    public function studentChangePassword()
    {
        return view('student.student-change-password');
    }





    // method use to change profile picture of students
    public function studentProfilePictureChange()
    {
        return view('student.student-change-profile-picture');
    }



    // method use to change password of the student
    public function postProfilePictureChange(Request $request)
    {
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
            $log->action = "Teacher Change Profile Picture";
            $log->save();

           return redirect()->route('student_profile_picture_change')->with('success', 'Sucessfully Change Your Profile Picture!');

        }
    }



    // method use to go to messages
    public function studentMessages()
    {

        $asy = SchoolYear::whereStatus(1)->first();
        $info = StudentInfo::where('user_id', Auth::user()->user_id)->first();

        // get all teacher with the subject
        $teachers = SubjectAssign::where('school_year_id', $asy->id)
                                ->where('section_id', $info->section)
                                ->get();

        return view('student.student-messages', ['teachers' => $teachers]);
    }


    // method in messages between teacher and students
    public function studentMessageThread($teacher_id = null)
    {

        $teacher = User::findorfail($teacher_id);

        $messages = Message::where('teacher_id', $teacher->id)
                            ->where('student_id', Auth::user()->id)
                            ->orderBy('created_at', 'desc')
                            ->get();

        Message::where('teacher_id', $teacher->id)
                ->where('student_id', Auth::user()->id)
                ->whereSender(2)
                ->update(['status' => 1]);


        return view('student.student-message-thread', ['teacher' => $teacher, 'messages' => $messages]);
    }


    // method to send message
    public function studentSendMessage(Request $request)
    {
        $teacher_id = $request['teacher_id'];


        // insert message to the database
        $message = $request['message'];

        $new = new Message();
        $new->teacher_id = $teacher_id;
        $new->student_id = Auth::user()->id;
        $new->message = $message;
        $new->sender = 3;
        $new->status = 0;
        $new->save();

        return redirect()->route('student_message_thread', ['teacher_id' => $teacher_id]);
    }



    public static function getGrade($i)
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
                return $i;
                break;
        }
    }

    


    // method use to view old grades of students
    public function viewPreviewsGrades()
    {
        $sy = SchoolYear::get();

        return view('student.student-view-previews-grades', ['sy' => $sy]);
    }



}
