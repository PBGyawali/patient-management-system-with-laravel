<?php

namespace App\Http\Controllers;
use App\Models\CompanyInfo;
use App\Models\Department;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
class DepartmentController extends Controller
{
    public $companyInfo=array();
    public $query='';

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data =Department::leftJoin('doctors','departments.department_id','doctors.department_id')
            ->select('departments.department_id as id','departments.department_name','departments.department_capacity','department_status', DB::raw('group_CONCAT(doctor_name) as doctors'))
            ->groupBy('departments.department_id')
            ->orderBy('departments.department_name')
            ->get();
            return DataTables::of($data)
            ->editColumn('department_status', function ($data) {               
                    $status =$data->department_status;
                    $class=$data->is_active()?'success':'danger';
                    //render status with css from view
                    return view('custom_badge',compact('status','class'))->render();               
            })
            ->addColumn('action', function($data){
                    $element='department';
                    $id=$data->id;
                    $actionBtn = view('text_buttons',compact('id','element'))->render();            
                    return $actionBtn;
                })
                ->make(true);
        }

        $info=CompanyInfo::first();
        $page='department';
        return view('departments',
        compact('info','page' )      );
    }



    public function store(Request $request)
    {
        $this->validate($request, [
            'department_name' => ['required', 'string', 'max:255','unique:departments'],
        ]);
        Department::create($request->all());
        return response()->json(array('response'=>__('message.create',['name'=>'department'])));

    }

    public function edit(Department $department)
    {
        return response()->json($department);
    }


    public function update(Request $request, Department $department)
    {
        $this->validate($request, [
            'department_name' => ['required', 'string', 'max:255',Rule::unique('departments')->ignore($department)]]);
        $department->update($request->all());
        return response()->json(array('response'=>__('message.update',['name'=>'department'])));
       
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json(array('response'=>__('message.delete',['name'=>'department'])));

    }


}
