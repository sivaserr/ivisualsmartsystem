<?php

namespace App\Http\Controllers;

use App\Student;
use App\StudentSubject;
use Maatwebsite\Excel\Facades\Excel;
use App\StudentSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class StudentController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function importView() 
    {
        return view('student.excelimport');
    }

    public function importStudents(Request $request)
    {
            if($request->hasFile('file')){
                $path = $request->file('file')->getRealPath();
                $data = Excel::load($path, function($reader){})->get();
                    if(!empty($data) && $data->count()) {
                        
                        foreach($data as $key => $value){
                            $stud = new Student();
                            $stud->name = $value->name;
                            $stud->email = $value->email;
                            $stud->reg_no = $value->reg_no;
                            $stud->degree = $value->degree;
                            $stud->department = $value->department;
                            $stud->year = $value->year;
                            $stud->semester = $value->semester;
                            $stud->section = $value->section;
                            $stud->status = $value->status;
                            $stud->parent_phone = $value->parent_phone;
                            $stud->address = $value->address;
                            $stud->bloodgroup = $value->bloodgroup;
                            $stud->scholar = $value->scholar;
                            $stud->bus_number = $value->bus_number;
                            $stud->room_number = $value->room_number;
                            $stud->twelveth_or_diploma = $value->twelveth_or_diploma;
                            $stud->tenth_percentage = $value->tenth_percentage;
                            $stud->twelveth_percentage = $value->twelveth_percentage;
                            $stud->twelveth_cutoff = $value->twelveth_cutoff;
                            $stud->joined_date = $value->joined_date;
                            $stud->st_type = $value->st_type;
                            $stud->save();
                        }
                        return back();
                    }
            }else{
                echo 'No file Found';
            }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|unique:student',
            'department' => 'required|max:255',
        ]);

        $stud = new Student;
        $stud->name = $request->name;
        $stud->email = $request->email;
        $stud->reg_no = $request->reg_no;
        $stud->degree = $request->degree;
        $stud->department = $request->department;
        $stud->year = $request->year;
        $stud->semester = $request->semester;
        $stud->section = $request->section;
        $stud->status = $request->status;
        $stud->parent_phone = $request->parent_phone;
        $stud->address = $request->address;
        $stud->bloodgroup = $request->bloodgroup;
        $stud->scholar = $request->scholar;
        $stud->bus_number = $request->bus_number;
        $stud->room_number = $request->room_number;
        $stud->twelveth_or_diploma = $request->twelveth_or_diploma;
        $stud->tenth_percentage = $request->tenth_percentage;
        $stud->twelveth_percentage = $request->twelveth_percentage;
        $stud->twelveth_cutoff = $request->twelveth_cutoff;
        $stud->save();

        if($stud->save()){
            return response()->json('success');
        }else{
            return response()->json('failed');
        }
        

    }
 /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        $students = Student::where('status', 'current')->get();
        
        return response()->json($students);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Requset $request)
    {
        $students = Student::where('status', 'current')->get();
        return response()->json($students);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $stud = Student::findOrFail($request->student_id);
        $stud->name = $request->name;
        $stud->email = $request->email;
        $stud->reg_no = $request->reg_no;
        $stud->degree = $request->degree;
        $stud->department = $request->department;
        $stud->year = $request->year;
        $stud->semester = $request->semester;
        $stud->section = $request->section;
        $stud->status = $request->status;
        $stud->save();

        if($stud->save()){
            return response()->json('success');
        }else{
            return response()->json('failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }

    public function test()
    {
 
        $comments = StudentSubject::find(8)->studentschedule;

foreach ($comments as $comment) {
    print_r($comment->day);
    print_r($comment->hour);
}
    

            $sub = StudentSchedule::find(22)->studentsubject;

            return $sub->subject_id;
}

}



