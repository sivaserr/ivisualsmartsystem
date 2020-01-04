<?php

namespace App\Http\Controllers;

use App\SaturdayDayorders;
use Illuminate\Http\Request;
use App\Academic;

class SaturdayDayordersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateDay(Request $request)
    {
        $aca = Academic::find(1);
        $saturday = SaturdayDayorders::where([
            ['date','=',$request->satudrDaydate],
            ['semester_start','=',$aca->semester_start]
        ])->first();

        if($saturday === null){
            $SaturdayDayorders = new SaturdayDayorders;
            $SaturdayDayorders->date = $request->satudrDaydate;
            $SaturdayDayorders->day = $request->satudrDayOrder;
            $SaturdayDayorders->semester_start = $aca->semester_start;
            $SaturdayDayorders->save();
        }else{
            $saturday->day = $request->satudrDayOrder;
            $saturday->save();
        }

        $succMessage = "Day order updated";
            $message = array('status' => 'success','message'=>$succMessage);
            return response()->json($message);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SaturdayDayorders  $saturdayDayorders
     * @return \Illuminate\Http\Response
     */
    public function show(SaturdayDayorders $saturdayDayorders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SaturdayDayorders  $saturdayDayorders
     * @return \Illuminate\Http\Response
     */
    public function edit(SaturdayDayorders $saturdayDayorders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SaturdayDayorders  $saturdayDayorders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaturdayDayorders $saturdayDayorders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SaturdayDayorders  $saturdayDayorders
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaturdayDayorders $saturdayDayorders)
    {
        //
    }
}
