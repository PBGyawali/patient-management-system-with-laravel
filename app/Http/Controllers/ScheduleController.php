<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\CompanyInfo;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Helper\Select;
class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Schedule::get();
            return DataTables::of($data)                
                ->addColumn('action', function($data){
                    $element='schedule';
                    $id=$data->schedule_id;
                    $actionBtn = view('text_buttons',compact('id','element'))->render();            
                    return $actionBtn;
                })
                ->editColumn('schedule_status', function ($data) {
                    $status =$data->schedule_status;
                    $class=$status == 'active'?'success':'danger';
                    //render status with css from view
                    return view('custom_badge',compact('status','class'))->render(); 
                })  
                ->addColumn('available_time', function ($data) {
                    return $data->available_time;                     
                })
                ->addColumn('doctor_name', function ($data) {
                    return '<img width="28" height="28" src="'.$data->doctor->user->profile.'" class="rounded-circle m-r-5" alt="">'. $data->doctor->doctor_name.'</td>';
                    //return $data->doctor->doctor_name;                     
                })
                ->addColumn('department_name', function ($data) {
                    return $data->department->department_name;                 
                }) 
                ->rawcolumns(['doctor_name','schedule_status','action'])           
                ->make(true);
        }
        $info=CompanyInfo::first();
        $doctor_list=Select::instance()->load_doctor();
        $department_list=Select::instance()->load_department();
        $page='schedule';
        return view('schedule',
        compact('info','page','doctor_list','department_list' ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'schedule_start_time' => ['required'],
            'schedule_end_time' => ['required'],
            'doctor_id' => ['required'],
            'department_id' => ['required'],
            'available_days' => ['required'],
            ]);
        Schedule::create($request->all());
        return response()->json(['response'=>__('message.create',['name'=>'schedule'])]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {        
        return response()->json($schedule);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $this->validate($request, [
            'schedule_start_time' => ['required'],
            'schedule_end_time' => ['required'],
            'doctor_id' => ['required'],
            'department_id' => ['required'],
            'available_days' => ['required'],
            ]);
        $schedule->update($request->all());
        return response()->json(['response'=>__('message.update',['name'=>'schedule'])]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->destroy();
        return response()->json(['response'=>__('message.delete',['name'=>'schedule'])]);
    }
}
