<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/* Setup CORS */
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('login', 'Auth\LoginController@ApiLogin');
Route::post('login', function (Request $request) {
    
    if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
        // Authentication passed...
        $user = auth()->user();
        $user->api_token = str_random(60);
        $user->save();
        return $user;
    }
    
    return response()->json([
        'error' => 'Unauthenticated user',
        'code' => 401,
    ], 401);
});

Route::middleware('auth:api')->post('logout', function (Request $request) {
    
    if (auth()->user()) {
        $user = auth()->user();
        $user->api_token = null; // clear api token
        $user->save();

        return response()->json([
            'message' => 'Thank you for using our application',
        ]);
    }
    
    return response()->json([
        'error' => 'Unable to logout user',
        'code' => 401,
    ], 401);
});

Route::get('test', 'StudentController@test');

Route::get('fetchsemsaturdays', 'AcademicController@fetchSemSaturdays');
Route::get('fetchsemsaturdaysFive', 'AcademicController@fetchSemSaturdaysFive');

Route::get('students', 'StudentController@showAll');
Route::post('student/store', 'StudentController@store');
Route::put('student/update', 'StudentController@update');

Route::get('staffs', 'StaffController@showAll');
Route::post('staff/store', 'StaffController@store');
Route::put('staff/update', 'StaffController@update');


Route::get('schedule/subject/show', 'Schedule\SubjectController@show');
Route::post('schedule/subject/store', 'Schedule\SubjectController@store');

Route::post('updatesubjectstudent', 'Schedule\SubjectController@updateSubjectStudent');

Route::post('schedule/subject/update', 'Schedule\SubjectController@update');

Route::get('schedule/show', 'Schedule\ScheduleController@showAllSchedules');
Route::post('schedule/store', 'Schedule\ScheduleController@store');

Route::post('schedule/remove', 'Schedule\ScheduleController@deleteSchedules');

Route::post('schedule/reorderschedules', 'Schedule\ScheduleController@reorderSchedules');

Route::post('updatesaturdayorder', 'SaturdayDayordersController@updateDay');


// Store Date And Hour for overall count
Route::post('student/attendance/dateandhour', 'StudentAttendanceController@storeDateandHourFirst');

Route::post('student/attendance/hour', 'StudentAttendanceController@setStudentStatusEveryHour');

Route::post('student/attendance/hour/all', 'StudentAttendanceController@setStudentStatusEveryHourAll');

Route::post('student/attendance/view/bydate', 'StudentAttendanceController@StudentStatusByDate');

Route::post('student/attendance/view/byday', 'StudentAttendanceController@StudentStatusDay');

Route::post('student/attendance/view/byoverall', 'StudentAttendanceController@StudentStatusOverall');

Route::post('student/attendance/view/bysubject', 'StudentAttendanceController@StudentStatusSubject');

Route::post('student/attendance/view/overallstudent', 'StudentAttendanceController@getStudentCurrentSemOverall');

Route::post('student/specialattendance', 'StudentAttendanceController@studentsSpecialAttendance');

Route::post('student/removeattendance', 'StudentAttendanceController@studentsRemoveAttendance');


Route::post('staff/attendance/date', 'StaffAttendanceController@storeDate');
Route::post('staff/attendance/individual', 'StaffAttendanceController@makeStaffAt');
Route::post('staff/attendance/allat', 'StaffAttendanceController@makeAllStaffAt');
Route::post('staff/attendance/getdate', 'StaffAttendanceController@getAllatbydate');

Route::post('staff/attendance/withdates', 'StaffAttendanceController@getAttendancesbydates');

Route::get('staffs/attendances', 'StaffAttendanceController@AllAts');

Route::post('staff/profile/update', 'StaffController@ProfileUpdate');

Route::post('staff/approval/create', 'StaffApprovalController@store');

Route::post('staff/approval/getmyapprovals', 'StaffApprovalController@getMyApprovals');

Route::get('staff/approval/getallapprovals', 'StaffApprovalController@getAllApprovals');

Route::delete('staff/approval/removemyapprovals', 'StaffApprovalController@destroy');

Route::put('staff/approval/getmyapprovals', 'StaffApprovalController@updateApproval');

Route::get('staff/limitscount/{id}', 'StaffAttendanceController@getStafflimits');

Route::get('fetchsemester', 'AcademicController@fetchSemester');
Route::post('academicyear', 'AcademicController@postAcademic');
// Update Semester
Route::post('semester', 'AcademicController@closeSemester');

Route::post('academic_permissions', 'AcademicController@setPermissions');

Route::get('staff/myschedules/{id}', 'Schedule\ScheduleController@staffSchedules');
Route::get('staff/myalternatives/{id}', 'Schedule\ScheduleController@myalternatives');

Route::get('student/subjectstudents/{subject_id}', 'StudentAttendanceController@subjectstudents');

Route::get('studentEditPermissions', 'StudentAttendanceController@EditPermissions');

Route::get('studentEditPermissions2', 'StudentAttendanceController@EditPermissions2');

Route::post('studentEditPermissions/update', 'StudentAttendanceController@updatePermissions');

Route::post('staff/updateadvisor', 'StaffController@updateAdvisor');

Route::get('studentEditPermissions/staff/{id}', 'StaffController@GetAdvisorClasses');

Route::get('StaffSubjects/staff/{id}', 'Schedule\SubjectController@StaffSubjectsByid');


Route::post('getalldepartmentdatas', 'StudentAttendanceController@getDepartmentdatas');