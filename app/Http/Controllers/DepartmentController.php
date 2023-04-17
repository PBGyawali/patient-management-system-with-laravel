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
            ->select('departments.department_id as id','departments.department_name','departments.department_capacity', DB::raw('group_CONCAT(doctor_name) as doctors'))
            ->groupBy('departments.department_id')
            ->orderBy('departments.department_name')
            ->get();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $actionBtn = '
                    <div class="text-center">
			<button type="button" name="edit_button" data-prefix="Department" class="btn btn-warning btn-sm update" data-id="'.$data->id.'"><i class="fas fa-edit"></i></button>
			&nbsp;
			<button type="button" name="delete_button" class="btn btn-danger btn-sm delete" data-id="'.$data->id.'"><i class="fas fa-times"></i></button>
			</div>';
                    return $actionBtn;
                })
                ->make(true);
        }

        $info=CompanyInfo::first();
        $page='department';
        return view('department',
        compact('info','page' )      );
    }



    public function store(Request $request)
    {
        $this->validate($request, [
            'department_name' => ['required', 'string', 'max:255','unique:departments'],
        ]);
        Department::create($request->all());
       return response()->json(array('response'=>'<div class="alert alert-success">The department was created!</div>'));
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
        return response()->json(array('response'=>'<div class="alert alert-success">The department was updated!</div>'));
    }

    public function destroy(Department $department)
    {
        $department->delete();
		return response()->json('<div class="alert alert-success">Department Deleted</div>');
    }


}
