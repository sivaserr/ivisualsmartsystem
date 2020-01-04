<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('revalidate'); 
        $this->middleware('auth');
    }

    /**
     * Home page
     */
    public function index()
    {
        return view('home.index');
    }

    /**
     * Student pages
     */
    public function studentsIndex()
    {
	$students = Student::where('status','current')->get();
        return view('student.index', ['students' => $students]);
    }

    public function Studentcreate()
    {
        return view('student.create');
    }


    public function studentslist(){
	$students = Student::where('status','current')->get();
        return view('studentlist.index', ['students' => $students]);
    }

    public function createstudent(){
	return view('studentlist.create');
    }

    public function editstudent(){
	return view('studentlist.edit');
    }


    public function userslist(){
	$users = User::where('status','current')->get();
        return view('userlist.index', ['users' => $users]);
    }

    public function createuser(){
	return view('userlist.create');
    }

    public function edituser(){
	return view('userlist.edit');
    }



    /**
     * Staff pages
     */
    public function StaffMyProfile(){
        return view('staff.myprofile');
    }

    public function Staffindex()
    {
        return view('staff.index');
    }

    public function Staffcreate()
    {
        return view('staff.create');
    }

    public function ClassAdvisor(){
        return view('staff.classadvisors');
    }

    /**
     * Schedule pages
     */
    public function Scheduleindex()
    {
        return view('schedule.index');
    }
    public function ScheduleassignShedule()
    {
        return view('schedule.assign');
    }

    public function Scheduleview()
    {
        return view('schedule.view');
    }

    /**
     * Academic pages
     */
    public function Academicindex(){
        return view('academic.index');
    }

    /**
     * StudentAttendance pages
     */
    public function StudentAttendanceindex()
    {
        return view('studentattendance.make');
    }

    public function StudentAttendancemakeSpecial()
    {
        return view('studentattendance.makespecial');
    }
    
    public function StudentAttendanceremoveSpecial()
    {
        return view('studentattendance.removespecial');
    }

    
    public function StudentAttendanceEditAt()
    {
        return view('studentattendance.edit');
    }
    
    public function StudentAttendanceEditPermissions()
    {
        return view('studentattendance.editpermissions');
    }

    
    public function StudentAttendanceviewByDay(){
        return view('studentattendance.viewbyday');
    }
    public function StudentAttendanceviewByMonth(){
        return view('studentattendance.viewbymonth');
    }
    public function StudentAttendanceviewByMonthCount(){
        return view('studentattendance.viewbymonthcount');
    }
    public function StudentAttendanceviewByOverall(){
        return view('studentattendance.viewbyoverall');
    }
    public function StudentAttendanceviewBySubject(){
        return view('studentattendance.viewbysubject');
    }

    public function StudentAttendanceviewByPrincipal(){
        return view('studentattendance.viewforprincipal');
    }

    /**
     * StaffAttendance pages
     */

    public function StaffAttendanceindex(){
        return view('staffattendance.make');
    }

    public function StaffAttendanceEditAt()
    {
        return view('staffattendance.edit');
    }

    public function StaffAttendanceviewByDay(){
        return view('staffattendance.viewbyday');
    }
    public function StaffAttendanceviewByMonth(){
        return view('staffattendance.viewbymonth');
    }
    public function StaffAttendanceviewByOverall(){
        return view('staffattendance.viewbyoverall');
    }

    /**
     * StaffApproval pages
     */

    public function StaffApprovalmain(){
        return view('staffapprovals.index');
    }

    public function StaffApprovalshow(){
        return view('staffapprovals.show');
    }

    public function StaffApprovalAdmin(){
        return view('staffapprovals.adminview');
    }

}
