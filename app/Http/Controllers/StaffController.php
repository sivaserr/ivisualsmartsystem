<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\User;
use App\Role;
use App\StudentAttendanceEditPermission;

class StaffController extends Controller
{

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
    
    public function importView() 
    {
        return view('staff.excelimport');
    }



    public function import(Request $request) 
    {
        if($request->hasFile('file')){
            $path = $request->file('file')->getRealPath();
            $data = Excel::load($path, function($reader){})->get();
                if(!empty($data) && $data->count()) {
                    
                    foreach($data as $key => $value){
                        $user = new User();
                        $user->eid = $value->eid; // Unique
                        $user->email = $value->email; // Unique
                        $user->name = $value->name;
                        $user->occupation = $value->occupation;
                        $user->working_department = $value->working_department;
                        $user->status = $value->status;
                        $user->dashboard_role = $value->dashboard_role;
                        $user->password = Hash::make($value->password);
                        $user->save();
                        $user->roles()->attach(Role::where('name', $value->dashboard_role)->first());
                    }
                    return back();
                }
        }else{
            echo 'No file Found';
        }
    }
    


    public function showAll(Request $request)
    {
        $staffs = User::where('status', 'current')->get();
        return response()->json($staffs);
    }

    public function ProfileUpdate(Request $request){

        $user = User::find($request->user_id);
        $user->password = Hash::make($request->password);
        $user->email = $request->email; //user can only update password and email
        $user->save();

        return response()->json($request);
    }

    public function updateAdvisor(Request $request){
        //  UPDATE ADVISOR EID
        //$aca = Academic::find(1);

        // $id = $request->permission_id;
        
        $permission = StudentAttendanceEditPermission::find($request->permission_id);

    
        if($request->class_advisor){
            if($class_advisor = User::where('eid', $request->class_advisor)->first() === null){
                $status = array('status'=>'failed', 'message'=>'Staff ID '.$request->class_advisor.' not Found');

            return response()->json($status);
            }
            $class_advisor = User::where('eid', $request->class_advisor)->first()->id;
            $permission->class_advisor = $class_advisor;
        }else{
            $permission->class_advisor = $permission->class_advisor;
        }
        if($request->class_advisor2){
            if($class_advisor2 = User::where('eid', $request->class_advisor2)->first() === null){
                $status = array('status'=>'failed', 'message'=>'Staff ID '.$request->class_advisor2.' not Found');

            return response()->json($status);
            }
            $class_advisor2 = User::where('eid', $request->class_advisor2)->first()->id;
            $permission->class_advisor2 = $class_advisor2;
        }else{
            $permission->class_advisor2 = $permission->class_advisor2;
        }

        $permission->save();

       
        if($permission->save()){
            $status = array('status'=>'success', 'message'=>'Class Advisors updated');

            return response()->json($status);
        }else{
            $status = array('status'=>'failed', 'message'=>'Somthing went wrong Try Later');

            return response()->json($status);
        }
        

    }

    public function store(Request $request)
    {

        $staff = new User;
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->eid = $request->eid;
        $staff->working_department = $request->working_department;
        $staff->dashboard_role = $request->dashboard_role;
        $staff->occupation = $request->occupation;
        $staff->status = 'current';
        $staff->password = Hash::make($request->password);
        $staff->save();

        $staff
       ->roles()
       ->attach(Role::where('name', $request->dashboard_role)->first());

        if($staff->save()){
            return response()->json($staff);
        }else{
            return response()->json('failed');
        }
    }

    public function update(Request $request)
    {
        $staff = User::find($request->staff_id);
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->eid = $request->eid;
        $staff->working_department = $request->working_department;
        $staff->dashboard_role = $request->dashboard_role;
        $staff->occupation = $request->occupation;
        $staff->status = $request->status;
        $staff->password = Hash::make($request->password);
        $staff->save();

        $staff->roles()
       ->attach(Role::where('name', $request->dashboard_role)->first());

        
    return response()->json($request);
    }

    public function GetAdvisorClasses($id){
       
        $advisors = StudentAttendanceEditPermission::where([
            ['class_advisor','=',$id]
            ])->orWhere('class_advisor2',$id)->get();

        return response()->json($advisors);

    }

    

}



