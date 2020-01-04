<?php

namespace App\Http\Controllers\Schedule;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Academic;
use App\StudentSubject;
use App\StudentElective;
use App\StudentSchedule;
use App\User;
use App\Student;


class SubjectController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $aca = Academic::findOrFail(1);

        $ifExists = StudentSubject::where([
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['subject_id', '=', $request->subject_id],
            ['semester_start', '=', $aca->semester_start]
        ])->first();
        if($ifExists === null){

        $subject = new StudentSubject;
        $subject->subject_name = $request->subject_name;
        $subject->degree = $request->degree;
        $subject->department = $request->department;
        $subject->year = $request->year;
        $subject->semester = $request->semester;
        $subject->section = $request->section;
        $subject->subject_id = $request->subject_id;
        
        if($request->subject_type){
        $subject->subject_type = $request->subject_type;
        }else{
            $subject->subject_type = "0";
        }

        // if(empty(User::where('eid',$request->staff_id)->first())){
        //     $errors['staff_id'] = 'Staff Id not matching';
        // }else{
            if($request->staff_id && User::where('eid',$request->staff_id)->first()){
                $subject->user_id = User::where('eid',$request->staff_id)->first()->id;
            }
            if($request->staff_id2 && User::where('eid',$request->staff_id2)->first()){
                $subject->user_id2 = User::where('eid',$request->staff_id2)->first()->id;
            }
            if($request->staff_id3 && User::where('eid',$request->staff_id3)->first()){
                $subject->user_id3 = User::where('eid',$request->staff_id3)->first()->id;
            } 
        // }
        
        $subject->semester_start = $aca->semester_start;

        $subject->save();


        if($request->subject_type === '1' || $request->subject_type === '2'){

            $sUB = StudentSubject::where([
                ['department', '=', $request->department],
                ['year', '=', $request->year],
                ['semester', '=', $request->semester],
                ['section', '=', $request->section],
                ['semester_start', '=', $aca->semester_start],
                ['subject_id', '=', $request->subject_id]
            ])->first();

            $studentS = $request->selected_stds; 

            foreach($studentS as $student){

            $std = new StudentElective;
            $std->student_id = $student;
            $std->subject_id = $sUB->id;
            $std->department = $request->department;
            $std->year = $request->year;
            $std->semester = $request->semester;
            $std->semester_start = $aca->semester_start;
            $std->save();

            }

        }

        if($subject->save()){
            $stat['status'] = 'success';
        }else{
            $stat['status'] = 'failed';
        }


        return response()->json($stat);

        // return response()->json($request);

    }else{
        $stat['status'] = 'exists';
        return response()->json($stat);
    }
            // return response()->json($request);
    }

    public function Update(Request $request)
    {
        $aca = Academic::findOrFail(1);

        $ifExists = StudentSubject::where([
            ['degree', '=', $request->degree],
            ['department', '=', $request->department],
            ['year', '=', $request->year],
            ['semester', '=', $request->semester],
            ['section', '=', $request->section],
            ['subject_id', '=', $request->subject_id],
            ['semester_start', '=', $aca->semester_start],
            ['subject_type', '=', $aca->subject_type]
        ])->first();
    if($ifExists === null){
        $subject = StudentSubject::find($request->Edit_sub_id);
        $subject->subject_id = $request->Edit_subject_id;
        $subject->subject_name = $request->Edit_subject_name;
        $subject->subject_type = $request->Edit_subject_type;

        if($request->Edit_staff_id !== ""  && User::where('eid',$request->Edit_staff_id)->first() !== null){
            $subject->user_id = User::where('eid',trim($request->Edit_staff_id))->first()->id;
            $schedules = StudentSubject::find($request->Edit_sub_id)->studentschedule;
            foreach ($schedules as $schedule) {
                if(StudentSchedule::find($schedule->id)){
                    $SCHE = StudentSchedule::find($schedule->id);
                    $SCHE->staff_id = $subject->user_id;
                    $SCHE->save();
                } 
            }
        }
        
        if($request->Edit_staff_id2 !== ""  && User::where('eid',$request->Edit_staff_id2)->first() !== null){
            $subject->user_id2 = User::where('eid',trim($request->Edit_staff_id2))->first()->id;
            $schedules = StudentSubject::find($request->Edit_sub_id)->studentschedule;
            foreach ($schedules as $schedule) {
                if(StudentSchedule::find($schedule->id)){
                    $SCHE = StudentSchedule::find($schedule->id);
                    $SCHE->staff_id2 = $subject->user_id2;
                    $SCHE->save();
                } 
            }
        }else if($request->Edit_staff_id2 == ""){
            $subject->user_id2 = null;
            $schedules = StudentSubject::find($request->Edit_sub_id)->studentschedule;
            foreach ($schedules as $schedule) {
                if(StudentSchedule::find($schedule->id)){
                    $SCHE = StudentSchedule::find($schedule->id);
                    $SCHE->staff_id2 = null;
                    $SCHE->save();
                } 
            }
        }
        if($request->Edit_staff_id3 !== ""  && User::where('eid',$request->Edit_staff_id3)->first() !== null){
            $subject->user_id3 = User::where('eid',trim($request->Edit_staff_id3))->first()->id;
            $schedules = StudentSubject::find($request->Edit_sub_id)->studentschedule;
            foreach ($schedules as $schedule) {
                if(StudentSchedule::find($schedule->id)){
                    $SCHE = StudentSchedule::find($schedule->id);
                    $SCHE->staff_id3 = $subject->user_id3;
                    $SCHE->save();
                } 
            }
        }else if($request->Edit_staff_id3 == ""){
            $subject->user_id3 = null;
            $schedules = StudentSubject::find($request->Edit_sub_id)->studentschedule;
            foreach ($schedules as $schedule) {
                if(StudentSchedule::find($schedule->id)){
                    $SCHE = StudentSchedule::find($schedule->id);
                    $SCHE->staff_id3 = null;
                    $SCHE->save();
                } 
            }
        } 

        $subject->save();
    }else{
        return response()->json('exists');
    }
            return response()->json(['status'=>'success']);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $aca = Academic::findOrFail(1);

        $subjects = StudentSubject::where('semester_start', $aca->semester_start)->get();

        foreach ($subjects as $subject) {
            $subject->staff_name = User::find($subject->user_id)->name;
            $subject->students = StudentElective::where('subject_id',$subject->id)->get();
            if($subject->user_id2){$subject->staff_name2 = User::find($subject->user_id2)->name;}
            if($subject->user_id3){$subject->staff_name3 = User::find($subject->user_id3)->name;}
        }

        return response()->json($subjects);
    }


    public function updateSubjectStudent(Request $request){

        $aca = Academic::first();

        if($request->remove === true){
            $student = StudentElective::where([
                ['subject_id', '=', $request->subject_id],
                ['student_id', '=', $request->student_id]
            ])->first();
            $student->delete();
            return response()->json(['status'=>'success','message'=>'Student Removed!']);
        }else if($request->remove === false){
            $student = new StudentElective;
            $sub = StudentSubject::findOrFail($request->subject_id);
            $student->student_id = $request->student_id;
            $student->subject_id = $request->subject_id;
            $student->department = $sub->department;
            $student->year = $sub->year;
            $student->semester = $sub->semester;
            $student->semester_start = $aca->semester_start;
            $student->save();
            return response()->json(['status'=>'success','message'=>'Student Added Successfully!']);
        }
        
        return response()->json($request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function StaffSubjectsByid($id)
    {
        $aca = Academic::findOrFail(1);
        
        $subjects = StudentSubject::where([
            ['semester_start', $aca->semester_start],
            ['user_id', $id],
        ])->get();

        return response()->json($subjects);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
