<?php

namespace App\Http\Controllers\Schedule;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\StudentSchedule;
use App\Academic;
use App\User;
use App\StudentSubject;
use App\StudentElective;
use App\StaffApproval;
use Auth;
use App\StudentAttendanceHour;

class ScheduleController extends Controller
{
    
    public function store(Request $request)
    {
        $aca = Academic::findOrFail(1);

        // if($request->is_elective === 0){
            $ifExists = StudentSchedule::where([
                ['degree', '=', $request->degree],
                ['department', '=', $request->department],
                ['year', '=', $request->year],
                ['semester', '=', $request->semester],
                ['section', '=', $request->section],
                ['day', '=', $request->Day_selected],
                ['hour', '=', $request->Hour_selected],
                ['semester_start', '=', $aca->semester_start]
            ])->first();
            if($ifExists === null){
    
            $schedule = new StudentSchedule;
            $schedule->degree = $request->degree;
            $schedule->department = $request->department;
            $schedule->year = $request->year;
            $schedule->semester = $request->semester;
            $schedule->section = $request->section;
            $schedule->staff_id = StudentSubject::find($request->Subject_selected)->user_id;
            
            if(StudentSubject::find($request->Subject_selected)->user_id2){
                $schedule->staff_id2 = StudentSubject::find($request->Subject_selected)->user_id2;
            }
            if(StudentSubject::find($request->Subject_selected)->user_id3){
                $schedule->staff_id3 = StudentSubject::find($request->Subject_selected)->user_id3;
            }
            

            $schedule->day = $request->Day_selected;
            $schedule->hour = $request->Hour_selected;
            $schedule->is_elective = StudentSubject::find($request->Subject_selected)->subject_type;
            $schedule->student_subject_id = $request->Subject_selected;
            $schedule->semester_start = $aca->semester_start;
            $schedule->save();
    
            return response()->json('created');
            }
            else{
                if($request->addmore === true){
                        $schedule = new StudentSchedule;
                        $schedule->degree = $request->degree;
                        $schedule->department = $request->department;
                        $schedule->year = $request->year;
                        $schedule->semester = $request->semester;
                        $schedule->section = $request->section;
                        $schedule->staff_id = StudentSubject::find($request->Subject_selected)->user_id;
                        $schedule->is_elective = StudentSubject::find($request->Subject_selected)->subject_type;
                        if(StudentSubject::find($request->Subject_selected)->user_id2){
                            $schedule->staff_id2 = StudentSubject::find($request->Subject_selected)->user_id2;
                        }
                        if(StudentSubject::find($request->Subject_selected)->user_id3){
                            $schedule->staff_id3 = StudentSubject::find($request->Subject_selected)->user_id3;
                        }
                        $schedule->day = $request->Day_selected;
                        $schedule->hour = $request->Hour_selected;
                        $schedule->student_subject_id = $request->Subject_selected;
                        $schedule->semester_start = $aca->semester_start;
                        $schedule->save();
                }else{
                    if($request->update_one === true && $request->oldsubject !== null){
                        $schedule = StudentSchedule::where([
                            ['degree', '=', $request->degree],
                        ['department', '=', $request->department],
                        ['year', '=', $request->year],
                        ['semester', '=', $request->semester],
                        ['section', '=', $request->section],
                        ['day', '=', $request->Day_selected],
                        ['hour', '=', $request->Hour_selected],
                        ['student_subject_id', '=', $request->oldsubject],
                        ['semester_start', '=', $aca->semester_start]
                        ])->first();
                    $schedule->is_changed_inbetween = true;
                    $schedule->changed_date = date("Y-m-d");        
                    $schedule->save();

                    $schedule = new StudentSchedule;
                        $schedule->degree = $request->degree;
                        $schedule->department = $request->department;
                        $schedule->year = $request->year;
                        $schedule->semester = $request->semester;
                        $schedule->section = $request->section;
                        $schedule->staff_id = StudentSubject::find($request->Subject_selected)->user_id;
                        $schedule->is_elective = StudentSubject::find($request->Subject_selected)->subject_type;
                        if(StudentSubject::find($request->Subject_selected)->user_id2){
                            $schedule->staff_id2 = StudentSubject::find($request->Subject_selected)->user_id2;
                        }
                        if(StudentSubject::find($request->Subject_selected)->user_id3){
                            $schedule->staff_id3 = StudentSubject::find($request->Subject_selected)->user_id3;
                        }
                        $schedule->day = $request->Day_selected;
                        $schedule->hour = $request->Hour_selected;
                        $schedule->student_subject_id = $request->Subject_selected;
                        $schedule->semester_start = $aca->semester_start;
                        $schedule->save();

                    }  

                    
                return response()->json($request);
                }
                
            }
        // }else{

        //     $schedule = new StudentSchedule;
        //     $schedule->degree = $request->degree;
        //     $schedule->department = $request->department;
        //     $schedule->year = $request->year;
        //     $schedule->semester = $request->semester;
        //     $schedule->section = $request->section;
        //     $schedule->staff_id = StudentSubject::find($request->Subject_selected)->user_id;
        //     $schedule->is_elective = StudentSubject::find($request->Subject_selected)->subject_type;
        //     if(StudentSubject::find($request->Subject_selected)->user_id2){
        //         $schedule->staff_id2 = StudentSubject::find($request->Subject_selected)->user_id2;
        //     }
        //     if(StudentSubject::find($request->Subject_selected)->user_id3){
        //         $schedule->staff_id3 = StudentSubject::find($request->Subject_selected)->user_id3;
        //     }
        //     $schedule->day = $request->Day_selected;
        //     $schedule->hour = $request->Hour_selected;
        //     $schedule->student_subject_id = $request->Subject_selected;
        //     $schedule->semester_start = $aca->semester_start;
        //     $schedule->save();
    
        //     return response()->json('created');
        // }
    }
    

    public function reorderSchedules(Request $request){

        $schedules = $request->schedules;

        foreach ($schedules as $schedule) {
            if(StudentSchedule::find($schedule['id']) && isset($schedule['dayChange']) && isset($schedule['hourChange'])){
                $sch = StudentSchedule::find($schedule['id']);
                $sch->day = $schedule['dayChange'];
                $sch->hour = $schedule['hourChange'];
                $sch->save();
            }
        }
        $succMessage = "Schedules Updated";
            $message = array('status' => 'success','message'=>$succMessage);
            return response()->json($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showAllSchedules()
    {
        $aca = Academic::findOrFail(1);

        $schedules = StudentSchedule::where('semester_start', $aca->semester_start)->orderBy('day', 'ASC')->orderBy('hour', 'ASC')->get();

        foreach ($schedules as $schedule) {

            $schedule->subject_name = StudentSubject::find($schedule->student_subject_id)->subject_name;

        }

        return response()->json($schedules);

    }

    public function staffSchedules($id){

        $aca = Academic::findOrFail(1);

        $staffSchedules = StudentSchedule::where([
            ['staff_id', '=', $id],
            ['semester_start','=',$aca->semester_start]
            ])->orWhere([
                ['staff_id2', '=', $id],
                ['semester_start','=',$aca->semester_start]
            ])->orWhere([
                    ['staff_id3', '=', $id],
                    ['semester_start','=',$aca->semester_start]
            ])->get();

        foreach ($staffSchedules as $staffSchedule) {
            $Hour = StudentAttendanceHour::where([
                ['schedule_id','=',$staffSchedule->id],
                ['date','=',date("Y-m-d")],
                ['semester_start','=',$aca->semester_start],
            ])->first();
            if($Hour !== null){
                $staffSchedule->attendance_made = true;
            }else{
                $staffSchedule->attendance_made = false;            
            }
            $staffSchedule->subject_name = StudentSubject::find($staffSchedule->student_subject_id)->subject_name;
            
        }

        return response()->json($staffSchedules);
    }

    public function myalternatives($authid){

        $aca = Academic::findOrFail(1);

        $todayalters = StaffApproval::where([
            ['date','=', date("Y-m-d")],
            ['semester_start', '=',$aca->semester_start]]
        )->get();

      $AuthUser = User::find($authid);
        
        $hours = [];
        foreach ($todayalters as $alter) {
            
            $split = explode(',', $alter->staff_alternatives);

            
            foreach ($split as $key) {
                $key = explode('-', $key);
                
                    $hour = StudentSchedule::where([
                        ['staff_id', '=', $alter->staff_id],
                        ['day', '=', $key[0]],
                        ['hour', '=', $key[1]],
                        ['semester_start', '=',$aca->semester_start]
                    ])->first();
                    $hour->subject_name = StudentSubject::find($hour->student_subject_id)->subject_name;
                    if($AuthUser->eid == $key[2]){
                        array_push($hours, $hour); 
                    }
                    
                
              }
        }
        return $hours;
    }
   
    public function deleteSchedules(Request $request){

        $aca = Academic::findOrFail(1);

        $schedules = StudentSchedule::where([
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['day', '=', $request->Day_selected],
            ['hour', '=', $request->Hour_selected],
            ['semester_start', '=', $aca->semester_start]
        ])->get();

        foreach ($schedules as $schedule) {
            StudentSchedule::find($schedule->id)->delete();
        }
        
        return response()->json('deleted');

    }


}
