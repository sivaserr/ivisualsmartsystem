<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use DB;
use App\StudentSchedule;
use App\StudentSubject;
use App\StudentElective;
use App\Student;
use App\User;
use App\Academic;
use App\StudentAttendanceEditPermission;

use App\StudentAttendanceDate;
use App\StudentAttendanceHour;
use App\StudentAttendanceRecord;


class StudentAttendanceController extends Controller
{
  

// INSERT DATE HOUR   
    public function storeDateandHourFirst(Request $request)
    {
        $aca = Academic::find(1);

        $IfDayHour = StudentAttendanceHour::where([
            ['date', '=', $request->date],
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['hour', '=', $request->hour],
        ])->first();

        if($IfDayHour === null || 
        StudentSchedule::find($IfDayHour->schedule_id)->day === 
        StudentSchedule::find($request->schedule_id)->day){
        // $IfDayHour = StudentAttendanceHour::where([
        //             ['date', '=', $request->date],
        //             ['degree', '=', $request->degree],
        //             ['department', '=', $request->department],
        //             ['year', '=', $request->year],
        //             ['semester', '=', $request->semester],
        //             ['section', '=', $request->section],
        //             ['hour', '=', $request->hour],
        //         ])->first();
                
            $CountHour = StudentAttendanceHour::where([
                ['date', '=', $request->date],
                ['degree', '=', $request->degree],
                ['department', '=', $request->department],
                ['year', '=', $request->year],
                ['semester', '=', $request->semester],
                ['section', '=', $request->section],
                ['hour', '=', $request->hour]
            ])->count();
            $CurrentRecords = StudentAttendanceRecord::where([
                ['date', '=', $request->date],
                ['degree', '=', $request->degree],
                ['department', '=', $request->department],
                ['year', '=', $request->year],
                ['semester', '=', $request->semester],
                ['section', '=', $request->section],
                ['hour', '=', $request->hour],
                ['schedule_id', '=', $request->schedule_id]
            ])->get();

            $electivestudents = StudentElective::where([
                ['subject_id', '=', $request->subject_id]
            ])->get();
            $stds = [];
            if(count($electivestudents) > 0){
                $electivestudents = $electivestudents;
                foreach($electivestudents as $electivestudent){
                    $std = Student::find($electivestudent->student_id);
                    $studentStatus = StudentAttendanceRecord::where([
                        ['date', '=', $request->date],
                        ['degree', '=', $request->degree],
                        ['department', '=', $request->department],
                        ['year', '=', $request->year],
                        ['semester', '=', $request->semester],
                        ['section', '=', $request->section],
                        ['hour', '=', $request->hour],
                        ['schedule_id', '=', $request->schedule_id],
                        ['student_id', '=', $std->id]
                    ])->first();
              
                if($studentStatus !== null){
                    $std->attendance_status = $studentStatus->status;
                }else{
                    $std->attendance_status = null;
                }
                if($std->joined_date === null || $std->joined_date <= $request->date){
                    array_push($stds, $std);
                }
                }
               
            }else{
                $ClassStudents = Student::where([
                    ['degree', '=', $request->degree],
                    ['department', '=', $request->department],
                    ['year', '=', $request->year],
                    ['semester', '=', $request->semester],
                    ['section', '=', $request->section],
                    ['status', '=', 'current']
                ])->get();

                foreach($ClassStudents as $ClassStudent){
                    $std = Student::find($ClassStudent->id);
                    $studentStatus = StudentAttendanceRecord::where([
                        ['date', '=', $request->date],
                        ['degree', '=', $request->degree],
                        ['department', '=', $request->department],
                        ['year', '=', $request->year],
                        ['semester', '=', $request->semester],
                        ['section', '=', $request->section],
                        ['hour', '=', $request->hour],
                        ['schedule_id', '=', $request->schedule_id],
                        ['student_id', '=', $std->id]
                    ])->first();
              
                if($studentStatus !== null){
                    $std->attendance_status = $studentStatus->status;
                }else{
                    $std->attendance_status = null;
                }
                if($std->joined_date === null || $std->joined_date <= $request->date){
                    array_push($stds, $std);
                }
                }

            }
    return response()->json(['status'=>'success','CurrentRecords'=>$CurrentRecords,'electivestudents'=>$stds,'test'=>$CountHour]);        
        }else{
//ELSE 

$IfDayHour = StudentAttendanceHour::where([
    ['date', '=', $request->date],
    ['degree', '=', $request->degree],
    ['department', '=', $request->department],
    ['year', '=', $request->year],
    ['semester', '=', $request->semester],
    ['section', '=', $request->section],
    ['hour', '=', $request->hour],
])->first();
if($IfDayHour->alternate_staff !== null){
    $WrUser = $IfDayHour->alternate_staff;
}else{
    $scheduleA = StudentSchedule::find($IfDayHour->schedule_id);
    $WrUser = $scheduleA->staff_id;
}


$userA = User::find($WrUser);
$succMessage = "This Hour Attendance is already made by Faculty ".$userA->name." (".$userA->eid.") Please Send a email to ivisualdd@gmail.com to remove this Hour Data. note: add CC to HOD and Faculty ".$userA->name." (".$userA->eid.")";
$message = array('status' => 'failed','message'=>$succMessage);
return response()->json($message);


}


}
         
    


    public function setStudentStatusEveryHourAll(Request $request){

     $aca = Academic::find(1);
     $IfDate1 = StudentAttendanceDate::where([
         ['date', '=', $request->date],
         ['degree', '=', $request->degree],
         ['department', '=', $request->department],
         ['year', '=', $request->year],
         ['semester', '=', $request->semester],
         ['section', '=', $request->section]
     ])->first();
//CREATE DATE
     if($IfDate1 === null){
         $CreateDate = new StudentAttendanceDate;
         $CreateDate->degree = $request->degree;
         $CreateDate->department = $request->department;
         $CreateDate->year = $request->year;
         $CreateDate->semester = $request->semester;
         $CreateDate->section = $request->section;
         $CreateDate->date = $request->date;
         $CreateDate->total_hours = 0;
         $CreateDate->semester_start = $aca->semester_start;
         $CreateDate->save();
     }
//CREATE HOUR

 $IfHour = StudentAttendanceHour::where([
            ['date', '=', $request->date],
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['hour', '=', $request->hour],
            ['subject_id', '=', $request->subject_id]
        ])->first();


        $subject_type = StudentSubject::find($request->subject_id);
        if($IfHour === null){
            //CHECK FOR DAY
            // $IfDayHour = StudentAttendanceHour::where([
            //     ['date', '=', $request->date],
            //     ['degree', '=', $request->degree],
            //     ['department', '=', $request->department],
            //     ['year', '=', $request->year],
            //     ['semester', '=', $request->semester],
            //     ['section', '=', $request->section],
            //     ['hour', '=', $request->hour],
            // ])->first();

            // if($IfDayHour === null || 
            // StudentSchedule::find($IfDayHour->schedule_id)->day === 
            // StudentSchedule::find($request->schedule_id)->day){
                $CreateHour = new StudentAttendanceHour;
                $CreateHour->degree = $request->degree;
                $CreateHour->department = $request->department;
                $CreateHour->year = $request->year;
                $CreateHour->semester = $request->semester;
                $CreateHour->section = $request->section;
                $CreateHour->subject_id = $request->subject_id;
                $CreateHour->date = $request->date;
                $CreateHour->hour = $request->hour;
                $CreateHour->schedule_id = $request->schedule_id;
                $CreateHour->is_sheduled_staff = 0;
                $CreateHour->subject_id = $request->subject_id;
                $CreateHour->alternate_staff = $request->alternate_staff;
                $CreateHour->semester_start = $aca->semester_start;
                $CreateHour->save();

                $CountHour = StudentAttendanceHour::where([
                    ['date', '=', $request->date],
                    ['degree', '=', $request->degree],
                    ['department', '=', $request->department],
                    ['year', '=', $request->year],
                    ['semester', '=', $request->semester],
                    ['section', '=', $request->section],
                    ['hour', '=', $request->hour]
                ])->count();

                if($CreateHour->save() && $CountHour === 1){
                    $IfDate2 = StudentAttendanceDate::where([
                        ['date', '=', $request->date],
                        ['degree', '=', $request->degree],
                        ['department', '=', $request->department],
                        ['year', '=', $request->year],
                        ['semester', '=', $request->semester],
                        ['section', '=', $request->section]
                    ])->first();
                    $IfDate2->total_hours = $IfDate2->total_hours + 1; // INCREATEMENT
                    $IfDate2->save();
                }
                $exact_count = 0;
                $present_count = 0;

                $Students = $request->students;
                foreach ($Students as $Student) {
                    $exact_count++;
                $IfSTHour = StudentAttendanceRecord::where([
                    ['student_id', '=', $Student['id']],
                    ['date', '=', $request->date],
                    ['degree', '=', $request->degree],
                    ['department', '=', $request->department],
                    ['year', '=', $request->year],
                    ['semester', '=', $request->semester],
                    ['section', '=', $request->section],
                    ['hour', '=', $request->hour],
                ])->first();
                if($Student['attendance_status'] !== null){
                    $CreateHour = new StudentAttendanceRecord;
                    $CreateHour->degree = $request->degree;
                    $CreateHour->department = $request->department;
                    $CreateHour->year = $request->year;
                    $CreateHour->semester = $request->semester;
                    $CreateHour->section = $request->section;
                    $CreateHour->subject_id = $request->subject_id;
                    $CreateHour->schedule_id = $request->schedule_id;
                    $CreateHour->student_id =  $Student['id'];
                    $CreateHour->date = $request->date;
                    $CreateHour->hour = $request->hour;
                
                
                    if($Student['attendance_status'] === "absent" || $Student['attendance_status'] === "leave"){
                        $CreateHour->is_present = false;
                    }else{
                        $CreateHour->is_present = true;
                        $present_count++;
                    }

                    $CreateHour->status = $Student['attendance_status'];
                    $CreateHour->semester_start = $aca->semester_start;
                    $CreateHour->save();

                    // Day Attendance
                    
                    $PreviousDay = DB::table('student_attendance_day_records')->select('*')->where([
                        ['date', '=', $request->date],
                        ['degree', '=', $request->degree],
                        ['department', '=', $request->department],
                        ['year', '=', $request->year],
                        ['semester', '=', $request->semester],
                        ['section', '=', $request->section],
                        ['student_id', '=', $Student['id']],
                    ])->get()->first();

                    
                    if(empty($PreviousDay)){
                        DB::table('student_attendance_day_records')->insert([
                            'degree' => $request->degree,
                            'department' => $request->department,
                            'year' => $request->year,
                            'semester' => $request->semester,
                            'section' => $request->section,
                            'date' => $request->date,
                            'semester_start' => $aca->semester_start,
                            'student_id' => $Student['id'],
                            'hour_status' => json_encode([$request->hour=>$Student['attendance_status']]),
                            'hour_subject' => json_encode([$request->hour=>$request->subject_id]),
                            'hour_staff' => json_encode([$request->hour=>$request->staff_id]),
                        ]);
                    }else{
                        $PStatus = get_object_vars(json_decode($PreviousDay->hour_status));
                        $PStatus[$request->hour] = $Student['attendance_status'];
                        $PSubject = get_object_vars(json_decode($PreviousDay->hour_subject));
                        $PSubject[$request->hour] = $request->subject_id;
                        $PStaff = get_object_vars(json_decode($PreviousDay->hour_staff));
                        $PStaff[$request->hour] = $request->staff_id;
                        DB::table('student_attendance_day_records')->update([
                            'hour_status' => json_encode($PStatus),
                            'hour_subject' => json_encode($PSubject),
                            'hour_staff' => json_encode($PStaff)
                        ]);
                    }

                }
               
            }
            // Class hour Count
                    $PreviousCount = DB::table('class_hour_student_count')->select('*')->where([
                        ['date', '=', $request->date],
                        ['degree', '=', $request->degree],
                        ['department', '=', $request->department],
                        ['year', '=', $request->year],
                        ['semester', '=', $request->semester],
                        ['section', '=', $request->section],
                        ['hour', '=', $request->hour],
                        ['subject_id', '=', $request->subject_id],
                    ])->get()->first();
                    
                    if(empty($PreviousCount)){
                        DB::table('class_hour_student_count')->insert([
                            'degree' => $request->degree,
                            'department' => $request->department,
                            'year' => $request->year,
                            'semester' => $request->semester,
                            'section' => $request->section,
                            'date' => $request->date,
                            'hour' => $request->hour,
                            'subject_id' => $request->subject_id,
                            'schedule_id' => $request->schedule_id,
                            'exact_count' => $exact_count,
                            'present_count' => $present_count,
                            'semester_start' => $aca->semester_start
                        ]);
                    }else{
                        
                        DB::table('class_hour_student_count')->update([
                            'subject_id' => $request->subject_id,
                            'schedule_id' => $request->schedule_id,
                            'exact_count' => $exact_count,
                            'present_count' => $present_count
                        ]);
                    }


            $succMessage = "Attendance Saved";
            $message = array('status' => 'success','message'=>$succMessage);
            return response()->json($message);
            // }else{
            //     $IfDayHour = StudentAttendanceHour::where([
            //         ['date', '=', $request->date],
            //         ['degree', '=', $request->degree],
            //         ['department', '=', $request->department],
            //         ['year', '=', $request->year],
            //         ['semester', '=', $request->semester],
            //         ['section', '=', $request->section],
            //         ['hour', '=', $request->hour],
            //     ])->first();
            //     if($IfDayHour->alternate_staff !== null){
            //         $WrUser = $IfDayHour->alternate_staff;
            //     }else{
            //         $scheduleA = StudentSchedule::find($IfDayHour->schedule_id);
            //         $WrUser = $scheduleA->staff_id;
            //     }
                
               
            //     $userA = User::find($WrUser);
            //     $succMessage = "This Hour Attendance is already made by Faculty ".$userA->name." (".$userA->eid.") Please Send a email to ivisualdd@gmail.com to remove this Hour Data. note: add CC to HOD and Faculty ".$userA->name." (".$userA->eid.")";
            //     $message = array('status' => 'failed','message'=>$succMessage);
            //     return response()->json($message);
            // }
            
            // return response()->json($CurrentRecords);
        }else{
            $Students = $request->students;
            $present_count = 0;
            $exact_count = 0;
foreach ($Students as $Student) {
    $exact_count++;
    $IfSTHour = StudentAttendanceRecord::where([
        ['student_id', '=', $Student['id']],
        ['date', '=', $request->date],
        ['degree', '=', $request->degree],
        ['department', '=', $request->department],
        ['year', '=', $request->year],
        ['semester', '=', $request->semester],
        ['section', '=', $request->section],
        ['hour', '=', $request->hour]
    ])->first();
            if($IfSTHour !== null && StudentAttendanceRecord::find($IfSTHour->id) !== null){
            $STrecord = StudentAttendanceRecord::find($IfSTHour->id);
            if($Student['attendance_status'] === "absent" || $Student['attendance_status'] === "leave"){
                $STrecord->is_present = false;
            }else{
                $STrecord->is_present = true;
                $present_count++;
            }
            $STrecord->status = $Student['attendance_status'];
            $STrecord->save();

            $PreviousDay = DB::table('student_attendance_day_records')->select('*')->where([
                        ['date', '=', $request->date],
                        ['degree', '=', $request->degree],
                        ['department', '=', $request->department],
                        ['year', '=', $request->year],
                        ['semester', '=', $request->semester],
                        ['section', '=', $request->section],
                        ['student_id', '=', $Student['id']],
                    ])->get()->first();
                    
             
                   if(empty($PreviousDay)){
                        DB::table('student_attendance_day_records')->insert([
                            'degree' => $request->degree,
                            'department' => $request->department,
                            'year' => $request->year,
                            'semester' => $request->semester,
                            'section' => $request->section,
                            'date' => $request->date,
                            'semester_start' => $aca->semester_start,
                            'student_id' => $Student['id'],
                            'hour_status' => json_encode([$request->hour=>$Student['attendance_status']]),
                            'hour_subject' => json_encode([$request->hour=>$request->subject_id]),
                            'hour_staff' => json_encode([$request->hour=>$request->staff_id]),
                        ]);
                    }else{
                        $PStatus = get_object_vars(json_decode($PreviousDay->hour_status));
                        $PStatus[$request->hour] = $Student['attendance_status'];
                        $PSubject = get_object_vars(json_decode($PreviousDay->hour_subject));
                        $PSubject[$request->hour] = $request->subject_id;
                        $PStaff = get_object_vars(json_decode($PreviousDay->hour_staff));
                        $PStaff[$request->hour] = $request->staff_id;
                        $PStaff[$request->hour] = $request->staff_id;
                        DB::table('student_attendance_day_records')->update([
                            'hour_status' => json_encode($PStatus),
                            'hour_subject' => json_encode($PSubject),
                            'hour_staff' => json_encode($PStaff)
                        ]);
                    } 

        }else if($IfSTHour === null){
            $CreateHour = new StudentAttendanceRecord;
            $CreateHour->degree = $request->degree;
            $CreateHour->department = $request->department;
            $CreateHour->year = $request->year;
            $CreateHour->semester = $request->semester;
            $CreateHour->section = $request->section;
            $CreateHour->subject_id = $request->subject_id;
            $CreateHour->schedule_id = $request->schedule_id;
            $CreateHour->student_id =  $Student['id'];
            $CreateHour->date = $request->date;
            $CreateHour->hour = $request->hour;
            if($Student['attendance_status'] === "absent" || $Student['attendance_status'] === "leave"){
                $CreateHour->is_present = false;
            }else{
                $CreateHour->is_present = true;
            }
            $CreateHour->status = $Student['attendance_status'];
            $CreateHour->semester_start = $aca->semester_start;
            $CreateHour->save();

                    $PreviousDay = DB::table('student_attendance_day_records')->select('*')->where([
                        ['date', '=', $request->date],
                        ['degree', '=', $request->degree],
                        ['department', '=', $request->department],
                        ['year', '=', $request->year],
                        ['semester', '=', $request->semester],
                        ['section', '=', $request->section],
                        ['student_id', '=', $Student['id']],
                    ])->get()->first();
                    
             
                    if(empty($PreviousDay)){
                        DB::table('student_attendance_day_records')->insert([
                            'degree' => $request->degree,
                            'department' => $request->department,
                            'year' => $request->year,
                            'semester' => $request->semester,
                            'section' => $request->section,
                            'date' => $request->date,
                            'semester_start' => $aca->semester_start,
                            'student_id' => $Student['id'],
                            'hour_status' => json_encode([$request->hour=>$Student['attendance_status']]),
                            'hour_subject' => json_encode([$request->hour=>$request->subject_id]),
                            'hour_staff' => json_encode([$request->hour=>$request->staff_id]),
                        ]);
                    }else{
                        
                        $PStatus = get_object_vars(json_decode($PreviousDay->hour_status));
                        $PStatus[$request->hour] = $Student['attendance_status'];
                        $PSubject = get_object_vars(json_decode($PreviousDay->hour_subject));
                        $PSubject[$request->hour] = $request->subject_id;
                        $PStaff = get_object_vars(json_decode($PreviousDay->hour_staff));
                        $PStaff[$request->hour] = $request->staff_id;
                        DB::table('student_attendance_day_records')->update([
                            'hour_status' => json_encode($PStatus),
                            'hour_subject' => json_encode($PSubject),
                            'hour_staff' => json_encode($PStaff)
                        ]);
                    }
        }

        }
           // Class hour Count
                    $PreviousCount = DB::table('class_hour_student_count')->select('*')->where([
                        ['date', '=', $request->date],
                        ['degree', '=', $request->degree],
                        ['department', '=', $request->department],
                        ['year', '=', $request->year],
                        ['semester', '=', $request->semester],
                        ['section', '=', $request->section],
                        ['hour', '=', $request->hour],
                        ['subject_id', '=', $request->subject_id],
                    ])->get()->first();
                    
                    if(empty($PreviousCount)){
                        DB::table('class_hour_student_count')->insert([
                            'degree' => $request->degree,
                            'department' => $request->department,
                            'year' => $request->year,
                            'semester' => $request->semester,
                            'section' => $request->section,
                            'date' => $request->date,
                            'hour' => $request->hour,
                            'subject_id' => $request->subject_id,
                            'schedule_id' => $request->schedule_id,
                            'exact_count' => $exact_count,
                            'present_count' => $present_count,
                            'semester_start' => $aca->semester_start
                        ]);
                    }else{
                        
                        DB::table('class_hour_student_count')->update([
                            // 'subject_id' => $request->subject_id,
                            // 'schedule_id' => $request->schedule_id,
                            'present_count' => $present_count
                        ]);
                    }
            $succMessage = "Attendance Updated";
            $message = array('status' => 'success','message'=>$succMessage);
            return response()->json($message);
        }
}

    public function studentsSpecialAttendance(Request $request){
        $aca = Academic::find(1);
        $selectedStudents = $request->selectedstudents;
        $selectedhours = $request->selectedhours;
        if(count($selectedStudents) > 0){


            $IfDate1 = StudentAttendanceDate::where([
                ['date', '=', $request->date],
                ['degree', '=', $request->degree],
                ['department', '=', $request->department],
                ['year', '=', $request->year],
                ['semester', '=', $request->semester],
                ['section', '=', $request->section]
            ])->first();
       //CREATE DATE
            if($IfDate1 === null){
                $CreateDate = new StudentAttendanceDate;
                $CreateDate->degree = $request->degree;
                $CreateDate->department = $request->department;
                $CreateDate->year = $request->year;
                $CreateDate->semester = $request->semester;
                $CreateDate->section = $request->section;
                $CreateDate->date = $request->date;
                $CreateDate->total_hours = 0;
                $CreateDate->semester_start = $aca->semester_start;
                $CreateDate->save();
            }
      
                   
foreach ($selectedhours as $selectedhour) {
 //CREATE HOUR
       
 $IfHour = StudentAttendanceHour::where([
    ['date', '=', $request->date],
    ['degree', '=', $request->degree],
    ['department', '=', $request->department],
    ['year', '=', $request->year],
    ['semester', '=', $request->semester],
    ['section', '=', $request->section],
    ['hour', '=', $selectedhour['hour']],
    ['subject_id', '=', $selectedhour['student_subject_id']]
])->first();
// $subject_type = StudentSubject::find($selectedhour['student_subject_id']);
if($IfHour === null){
 //CHECK FOR DAY
 $CreateHour = new StudentAttendanceHour;
 $CreateHour->degree = $request->degree;
 $CreateHour->department = $request->department;
 $CreateHour->year = $request->year;
 $CreateHour->semester = $request->semester;
 $CreateHour->section = $request->section;
 $CreateHour->date = $request->date;
 $CreateHour->hour = $selectedhour['hour'];
 $CreateHour->schedule_id = $selectedhour['id'];
 $CreateHour->is_sheduled_staff = 0;
 $CreateHour->subject_id = $selectedhour['student_subject_id'];
 $CreateHour->alternate_staff = $request->attendancetakingstaff;
 $CreateHour->semester_start = $aca->semester_start;
 $CreateHour->save();
}
$hoursCount = StudentAttendanceHour::where([
    ['date', '=', $request->date],
    ['degree', '=', $request->degree],
    ['department', '=', $request->department],
    ['year', '=', $request->year],
    ['semester', '=', $request->semester],
    ['section', '=', $request->section],
    ['hour', '=', $selectedhour['hour']],
])->get();
if(count($hoursCount) === 1){
    $IfDate1 = StudentAttendanceDate::where([
        ['date', '=', $request->date],
        ['degree', '=', $request->degree],
        ['department', '=', $request->department],
        ['year', '=', $request->year],
        ['semester', '=', $request->semester],
        ['section', '=', $request->section]
    ])->first();
     $IfDate1->total_hours = $IfDate1->total_hours + 1; // INCREATEMENT
     $IfDate1->save();
}
 

            foreach ($selectedStudents as $selectedStudent) {
                    if(StudentSubject::find($selectedhour['student_subject_id'])->subject_type === 2){
                        if(StudentElective::where([['subject_id','=',$selectedhour['student_subject_id']],['student_id','=',$selectedStudent]])->first() === null){
                            //create elective attendance
                            $IfSTHour = StudentAttendanceRecord::where([
                                ['student_id', '=', $selectedStudent],
                                ['date', '=', $request->date],
                                ['degree', '=', $request->degree],
                                ['department', '=', $request->department],
                                ['year', '=', $request->year],
                                ['semester', '=', $request->semester],
                                ['section', '=', $request->section],
                                ['hour', '=', $selectedhour['hour']]
                            ])->first();
                            if($IfSTHour === null){
                                $CreateHour = new StudentAttendanceRecord;
                                $CreateHour->degree = $request->degree;
                                $CreateHour->department = $request->department;
                                $CreateHour->year = $request->year;
                                $CreateHour->semester = $request->semester;
                                $CreateHour->section = $request->section;
                                $CreateHour->subject_id = $selectedhour['student_subject_id'];
                                $CreateHour->schedule_id = $selectedhour['id'];
                                $CreateHour->student_id =  $selectedStudent;
                                $CreateHour->date = $request->date;
                                $CreateHour->hour = $selectedhour['hour'];
                                if($request->selectedatstatus === "absent" || $request->selectedatstatus === "leave"){
                                    $CreateHour->is_present = false;
                                }else{
                                    $CreateHour->is_present = true;
                                }
                                $CreateHour->status = $request->selectedatstatus;
                                $CreateHour->semester_start = $aca->semester_start;
                                $CreateHour->save();
                            }else{
                                if($request->selectedatstatus === "absent" || $request->selectedatstatus === "leave"){
                                    $IfSTHour->is_present = false;
                                }else{
                                    $IfSTHour->is_present = true;
                                }
                                $IfSTHour->status = $request->selectedatstatus;
                                $IfSTHour->save();
                            }
                        }
                    }else{
                            //create theory attendance
                            $IfSTHour = StudentAttendanceRecord::where([
                                ['student_id', '=', $selectedStudent],
                                ['date', '=', $request->date],
                                ['degree', '=', $request->degree],
                                ['department', '=', $request->department],
                                ['year', '=', $request->year],
                                ['semester', '=', $request->semester],
                                ['section', '=', $request->section],
                                ['hour', '=', $selectedhour['hour']]
                            ])->first();
                            if($IfSTHour === null){
                                $CreateHour = new StudentAttendanceRecord;
                                $CreateHour->degree = $request->degree;
                                $CreateHour->department = $request->department;
                                $CreateHour->year = $request->year;
                                $CreateHour->semester = $request->semester;
                                $CreateHour->section = $request->section;
                                $CreateHour->subject_id = $selectedhour['student_subject_id'];
                                $CreateHour->schedule_id = $selectedhour['id'];
                                $CreateHour->student_id =  $selectedStudent;
                                $CreateHour->date = $request->date;
                                $CreateHour->hour = $selectedhour['hour'];
                                if($request->selectedatstatus === "absent" || $request->selectedatstatus === "leave"){
                                    $CreateHour->is_present = false;
                                }else{
                                    $CreateHour->is_present = true;
                                }
                                $CreateHour->status = $request->selectedatstatus;
                                $CreateHour->semester_start = $aca->semester_start;
                                $CreateHour->save();
                            }else{
                                if($request->selectedatstatus === "absent" || $request->selectedatstatus === "leave"){
                                    $IfSTHour->is_present = false;
                                }else{
                                    $IfSTHour->is_present = true;
                                }
                                $IfSTHour->status = $request->selectedatstatus;
                                $IfSTHour->save();
                            }
                    }
                    
                }
            }
            $succMessage = "Attendance Updated";
            $message = array('status' => 'success','message'=>$succMessage);
            return response()->json($message);
        }else{
            $succMessage = "Students not found";
            $message = array('status' => 'success','message'=>$succMessage);
            return response()->json($message);
        }
    }

    public function studentsRemoveAttendance(Request $request){
        $aca = Academic::find(1);
        //Hours
        $hours = StudentAttendanceHour::where([
            ['date','=',$request->date],
            ['hour','=',$request->hour],
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['semester_start', '=', $aca->semester_start]
        ])->get();
        
        if(count($hours) > 0){
        foreach ($hours as $hour) {
            $records = StudentAttendanceRecord::where([
                ['date','=',$request->date],
                ['hour','=',$request->hour],
                ['schedule_id','=',$hour->schedule_id],
                ['semester_start', '=', $aca->semester_start]
            ])->get();
            // return $records;
            foreach ($records as $record) {
                $rec = StudentAttendanceRecord::find($record->id);
                $rec->delete();
            }
            $Hr = StudentAttendanceHour::find($hour->id);
            
            if($Hr->delete()){
                $IfDate1 = StudentAttendanceDate::where([
                    ['date', '=', $request->date],
                    ['degree', '=', $request->degree],
                    ['department', '=', $request->department],
                    ['year', '=', $request->year],
                    ['semester', '=', $request->semester],
                    ['section', '=', $request->section],
                    ['semester_start', '=', $aca->semester_start]
                ])->first();
                if(($IfDate1->total_hours - 1) !== 0){
                    $IfDate1->total_hours = $IfDate1->total_hours - 1;
                    $IfDate1->save();
                }else if(($IfDate1->total_hours - 1) === 0){
                    $IfDate1->delete();
                }
                
            }
        }
        $succMessage = "Hour data Removed!";
            $message = array('status' => 'success','message'=>$succMessage);
            return response()->json($message);
    }else{
        $succMessage = "No Hours were Found!";
        $message = array('status' => 'success','message'=>$succMessage);
        return response()->json($message);
    }
    }


// RETURN RECORDS BY DATES FROM AND TO
    public function StudentStatusByDate(Request $request){
        $aca = Academic::find(1);
        $getRecords = StudentAttendanceRecord::where([
            ['date', '>=', $request->fromdate],
            ['date', '<=', $request->todate],
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['semester_start', '=', $aca->semester_start]
        ])->get();
        
        
        $Dates = StudentAttendanceDate::where([
            ['date', '>=', $request->fromdate],
            ['date', '<=', $request->todate],
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['semester_start', '=', $aca->semester_start]
        ])->get();

        $res = ['dates'=>$Dates,'records'=>$getRecords];

        return response()->json($res);
        
    }

    public function getStudentCurrentSemOverall(Request $request){
        $aca = Academic::find(1);
        $Dates = StudentAttendanceDate::where([
            ['date', '>=', $aca->sem_strt],
            ['date', '<=', $request->todate],
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['semester_start', '=', $aca->semester_start]
        ])->get();
        $allHours = 0;
        $allDays = 0;
        foreach ($Dates as $fdate) {
            if($fdate->total_hours >= 0){
                $allHours = $allHours + $fdate->total_hours;
                $allDays = $allDays + 1;
            }
        }
        $Students = Student::where([
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section]
        ])->get();
        
        $studentRecords = [];
        foreach ($Students as $Student) {
            if($Student->joined_date === null){
                $getRecords = StudentAttendanceRecord::where([
                    ['date', '>=', $aca->sem_strt],
                    ['date', '<=', $request->todate],
                    ['degree', '=', $request->degree],
                    ['department', '=', $request->department],
                    ['year', '=', $request->year],
                    ['semester', '=', $request->semester],
                    ['section', '=', $request->section],
                    ['student_id', '=', $Student->id],
                    ['is_present', '=', 1],
                    ['semester_start', '=', $aca->semester_start]
                ])->get();
                $getRecordsStTotal = $allHours;
            }else{
                $getRecords = StudentAttendanceRecord::where([
                    ['date', '>=', $Student->joined_date],
                    ['date', '<=', $request->todate],
                    ['degree', '=', $request->degree],
                    ['department', '=', $request->department],
                    ['year', '=', $request->year],
                    ['semester', '=', $request->semester],
                    ['section', '=', $request->section],
                    ['student_id', '=', $Student->id],
                    ['is_present', '=', 1],
                    ['semester_start', '=', $aca->semester_start]
                ])->get(); 
                
                $stSdates = StudentAttendanceDate::where([
                    ['date', '>=', $Student->joined_date],
                    ['date', '<=', $request->todate],
                    ['degree', '=', $request->degree],
                    ['department', '=', $request->department],
                    ['year', '=', $request->year],
                    ['semester', '=', $request->semester],
                    ['section', '=', $request->section],
                    ['semester_start', '=', $aca->semester_start]
                ])->get();
                $SallHours = 0;
                foreach ($stSdates as $fdate) {
                    if($fdate->total_hours >= 0){
                        $SallHours = $SallHours + $fdate->total_hours;
                    }
                }
        
                $getRecordsStTotal = $SallHours;

            }
            
            
            array_push($studentRecords,array('student_id'=>$Student->id,'st_overall'=>$getRecordsStTotal,'overall'=>count($getRecords),'total_hours'=>$allHours));
        }
        $arrayName = array('studentrecords' => $studentRecords,'total_count'=>$allHours,'total_days'=>$allDays);
        return response()->json($arrayName);
        
    }

    public function getDepartmentdatas(Request $request){
        $aca = Academic::find(1);
        $allDatas = [];
        foreach($request->getdatas as $getdata){
            switch ($getdata['year']) {
                case '1':if($aca->academic_semester = 'odd'){$semester = 1;
                }else{$semester = 2;}
                break;
                case '2':if($aca->academic_semester = 'odd'){$semester = 3;
                }else{$semester = 4;}
                break;
                case '3':if($aca->academic_semester = 'odd'){$semester = 5;
                }else{$semester = 6;}
                break;
                case '4':if($aca->academic_semester = 'odd'){$semester = 7;
                }else{$semester = 8;}
                break;
            }
            $getRecords = StudentAttendanceRecord::where([
                ['date', '=', $request->date],
                ['degree', '=', $request->degree],
                ['department', '=', $request->department],
                ['year', '=', $getdata['year']],
                ['semester', '=', $semester],
                ['section', '=', $getdata['section']],
                ['semester_start', '=', $aca->semester_start]
            ])->get();
    
    
            $Dates = StudentAttendanceHour::where([
                ['date', '=', $request->date],
                ['degree', '=', $request->degree],
                ['department', '=', $request->department],
                ['year', '=', $getdata['year']],
                ['semester', '=', $semester],
                ['section', '=', $getdata['section']],
                ['semester_start', '=', $aca->semester_start]
            ])->get();
            $students = Student::where([
                ['degree', '=', $request->degree],
                ['department', '=', $request->department],
                ['year', '=', $getdata['year']],
                ['semester', '=', $semester],
                ['section', '=', $getdata['section']],
                ['status', '=', 'current'],
            ])->get();
           
            if(count($Dates) > 0) {   
            foreach($Dates as $datee){
                if(StudentSubject::find($datee->subject_id)){
                    $datee->subject_name = StudentSubject::find($datee->subject_id)->subject_name;
                    $datee->subject_identity = StudentSubject::find($datee->subject_id)->subject_id;
                }
                
            }
        }
    
            array_push($allDatas,[
                                'date'=> $request->date,
                                'degree'=> $request->degree,
                                'department'=> $request->department,
                                'year'=> $getdata['year'],
                                'semester'=> $semester,
                                'section'=> $getdata['section'],
                                'hours'=>$Dates,
                                'records'=>$getRecords,
                                'students'=>$students
                                ]);
        }
        
        return response()->json($allDatas);
    }


// RETURN RECORDS BY DAY
    public function StudentStatusDay(Request $request){
        $aca = Academic::find(1);
        $getRecords = StudentAttendanceRecord::where([
            ['date', '=', $request->date],
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['semester_start', '=', $aca->semester_start]
        ])->get();

        $Dates = StudentAttendanceHour::where([
            ['date', '=', $request->date],
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['semester_start', '=', $aca->semester_start]
        ])->get();

        foreach($Dates as $datee){
            if(StudentSubject::find($datee->subject_id)){
                $datee->subject_name = StudentSubject::find($datee->subject_id)->subject_name;
            }

        }

        $res = ['hours'=>$Dates,'records'=>$getRecords];

        return response()->json($res);
        
    }


//RETURN OVERALL RECORDS
    public function StudentStatusOverall(Request $request){
        $aca = Academic::find(1);
        $getRecords = StudentAttendanceRecord::where([
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['semester_start', '=', $aca->semester_start]
        ])->get();

        $Dates = StudentAttendanceDate::where([
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['semester_start', '=', $aca->semester_start]
        ])->get();

        $totalHours = 0;

        foreach ($Dates as $Date) {
            $totalHours = $totalHours + $Date->total_hours;
        }

        $students = StudentAttendanceHour::where([
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['semester_start', '=', $aca->semester_start]
        ])->get();
        $studentRecords = [];
        foreach ($students as $student) {
            $StRecords = StudentAttendanceRecord::where([
                ['degree', '=', $request->degree],
                ['department', '=', $request->department],
                ['year', '=', $request->year],
                ['semester', '=', $request->semester],
                ['section', '=', $request->section],
                ['student_id', '=', $student->id],
                ['semester_start', '=', $aca->semester_start]
            ])->get();

            array_push($studentRecords,['student_id'=>$student->id,'overall'=>count($StRecords)]);

        }

        $hours = StudentAttendanceHour::where([
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['semester_start', '=', $aca->semester_start]
        ])->get();

        $res = ['hours'=>$hours,'records'=>$getRecords, 'total'=>$totalHours, 'studentrecords'=>$studentRecords];
        return response()->json($res); 
    }
//RETURN SUBJECT RECORDS



public function StudentStatusSubject(Request $request){
    $aca = Academic::find(1);
    $SChedules = StudentSubject::find($request->subject_id)->studentschedule;
    $THours = [];
    // foreach ($SChedules as $SChedule) {
    //    $Hours = StudentAttendanceHour::where([
    //         ['subject_id', '=', NULL],
    //         ['degree', '=', $request->degree],
    //         ['department', '=', $request->department],
    //         ['year', '=', $request->year],
    //         ['semester', '=', $request->semester],
    //         ['section', '=', $request->section],
    //         ['semester_start', '=', $aca->semester_start],
    //         ['schedule_id', '=', $SChedule->id]
    //     ])->get();
    //     foreach ($Hours as $Hour) {
    //         array_push($THours, $Hour);
    //     }
    // }

    $Hours2 = StudentAttendanceHour::where([
        ['degree', '=', $request->degree],
        ['department', '=', $request->department],
        ['year', '=', $request->year],
        ['semester', '=', $request->semester],
        ['section', '=', $request->section],
        ['semester_start', '=', $aca->semester_start],
        ['subject_id', '=', $request->subject_id]
    ])->get();
    foreach ($Hours2 as $Hour) {
        array_push($THours, $Hour);
    }

    $records = [];
    foreach ($SChedules as $SChedule) {
        $Hours3 = StudentAttendanceRecord::where([
             ['degree', '=', $request->degree],
             ['department', '=', $request->department],
             ['year', '=', $request->year],
             ['semester', '=', $request->semester],
             ['section', '=', $request->section],
             ['semester_start', '=', $aca->semester_start],
             ['schedule_id', '=', $SChedule->id],
             ['is_present', '=', 1]
         ])->get();
         foreach ($Hours3 as $Hour) {
             array_push($records, $Hour);
         }
     }


    //  $Hours4 = StudentAttendanceRecord::where([
    //     ['degree', '=', $request->degree],
    //     ['department', '=', $request->department],
    //     ['year', '=', $request->year],
    //     ['semester', '=', $request->semester],
    //     ['section', '=', $request->section],
    //     ['semester_start', '=', $aca->semester_start],
    //     ['subject_id', '=', $request->subject_id],
    //     ['is_present', '=', 1]
    // ])->get();

    // foreach ($Hours4 as $Hour) {
    //     array_push($records, $Hour);
    // }

    $electivestudents = StudentElective::where([
        ['subject_id', '=', $request->subject_id]
    ])->get();

    $stds = [];

    foreach($electivestudents as $electivestudent){
        $std = Student::find($electivestudent->student_id);
        array_push($stds, $std);
    }




$res = ['total_hours'=>$THours,'records'=>$records, 'electivestudents'=>$stds];
    return response()->json($res); 
}

    public function subjectstudents($subject_id){
        $subjectStudents = StudentElective::where('subject_id', '=', $subject_id)->get();

        $studArray = [];

        foreach ($subjectStudents as $substud) {
        $stud = Student::find($substud->student_id);
         array_push($studArray, $stud);
        }

        // $object = (object) $studArray;

        return response()->json($studArray);
    }

    public function EditPermissions2(){

        $aca = Academic::find(1);

        $editPermissions = StudentAttendanceEditPermission::all();

        return response()->json($editPermissions);
    }

    public function EditPermissions(){

        $aca = Academic::find(1);

        $editPermissions = StudentAttendanceEditPermission::all();

        return response()->json($editPermissions);
    }

    public function getDayMonth(){

        $aca = Academic::find(1);

          $Dates = StudentAttendanceHour::where([
            ['date', '=', $request->date],
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['semester_start', '=', $aca->semester_start]
        ])->get();
        $DateAts = [];
        foreach($Dates as $datee){
           $dateat = DB::table('student_attendance_day_records')->select('*')->where([
                        ['date', '=', $request->date],
                        ['degree', '=', $request->degree],
                        ['department', '=', $request->department],
                        ['year', '=', $request->year],
                        ['semester', '=', $request->semester],
                        ['section', '=', $request->section],
                    ])->get();
           array_push($DateAts,$dateat);

        }
        

        return response()->json($DateAts);
    }

    public function updatePermissions(Request $request){
        $aca = Academic::find(1);

        $id = $request->permission_id;
        $status = $request->status;

        $permission = StudentAttendanceEditPermission::find($id);

        $permission->status = $status;
        $permission->schedule_edit_status = $request->Schestatus;
        $permission->save();

        if($permission->save()){
           $status = array('status'=>'success');
        }else{
            $status = array('status'=>'failed');
        }

        return response()->json($status);



    }



}