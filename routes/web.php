<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

//home and pages
Route::get('/', 'HomeController@index')->name('home');

//Students
Route::get('/students', 'HomeController@studentsIndex')->name('students');
Route::get('/student/create', 'HomeController@Studentcreate')->name('student.create');


//Students
Route::get('/studentslist', 'HomeController@studentslist')->name('studentslist');
Route::get('/student/add_student', 'HomeController@createstudent')->name('studentslist.create');

//Users
Route::get('/userslist', 'HomeController@userslist')->name('userslist');
Route::get('/user/add_user', 'HomeController@createuser')->name('userslist.create');



//Staffs
Route::get('/staffs', 'HomeController@Staffindex')->name('staffs');
Route::get('/staff/create', 'HomeController@Staffcreate')->name('staff.create');
Route::get('/staff/classadvisor', 'HomeController@ClassAdvisor')->name('staff.classadvisor');



//Schedule
Route::get('/subject', 'HomeController@Scheduleindex')->name('subject-home');
Route::get('/schedule', 'HomeController@ScheduleassignShedule')->name('schedule-assign');
Route::get('/schedule/view', 'HomeController@Scheduleview')->name('schedule-view');


//student attendance
Route::get('/student/attendance/make', 'HomeController@StudentAttendanceindex')->name('student-attendance');
Route::get('/student/attendance/edit', 'HomeController@StudentAttendanceEditAt')->name('student-attendance-edit');
Route::get('/student/specialattendance', 'HomeController@StudentAttendancemakeSpecial')->name('student-attendance-special');

Route::get('/student/removeattendance', 'HomeController@StudentAttendanceremoveSpecial')->name('student-attendance-remove');




Route::get('/student/attendance/view/day', 'HomeController@StudentAttendanceviewByDay')->name('student-attendance-view-day');
Route::get('/student/attendance/view/month', 'HomeController@StudentAttendanceviewByMonth')->name('student-attendance-view-month');
Route::get('/student/attendance/view/month-count', 'HomeController@StudentAttendanceviewByMonthCount')->name('student-attendance-view-month-count');
Route::get('/student/attendance/view/overall', 'HomeController@StudentAttendanceviewByOverall')->name('student-attendance-view-overall');
Route::get('/student/attendance/view/subject', 'HomeController@StudentAttendanceviewBySubject')->name('student-attendance-view-subject');


Route::get('/student/attendance/view/principal', 'HomeController@StudentAttendanceviewByPrincipal')->name('superadmin-view-records');


Route::get('/student/attendance/edit/permissions', 'HomeController@StudentAttendanceEditPermissions')->name('student-attendance-edit-permissions');



//staffattendance
Route::get('/staff/attendance/make', 'HomeController@StaffAttendanceindex')->name('staff-attendance');
Route::get('/staff/attendance/edit', 'HomeController@StaffAttendanceEditAt')->name('staff-attendance-edit');

Route::get('/staff/attendance/view/day', 'HomeController@StaffAttendanceviewByDay')->name('staff-attendance-view-day');
Route::get('/staff/attendance/view/month', 'HomeController@StaffAttendanceviewByMonth')->name('staff-attendance-view-month');
Route::get('/staff/attendance/view/overall', 'HomeController@StaffAttendanceviewByOverall')->name('staff-attendance-view-overall');

//staffprofile
Route::get('/staff/profile', 'HomeController@StaffMyProfile')->name('my-profile');

//staffapprovals
Route::get('/staff/approval/main', 'HomeController@StaffApprovalmain')->name('staff-approvals');

//staffmyapproval
Route::get('/staff/approval', 'HomeController@StaffApprovalshow')->name('my-approvals');

//update semester confidential
Route::get('/update/semester', 'HomeController@Academicindex')->name('update-semester');

Route::get('test', 'StudentController@test');

// Route::get('excelexport', 'StaffController@export');

Route::get('/staff/excelimport', 'StaffController@importView');

Route::post('/staff/import', 'StaffController@import')->name('import-staffs');

Route::get('/student/excelimport', 'StudentController@importView');

Route::post('/student/import', 'StudentController@importStudents')->name('import-students');

//Users page
Route::get('/users','UsersController@index');
Route::post('/users','UsersController@store');

