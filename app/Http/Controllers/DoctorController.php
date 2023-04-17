<?php

namespace App\Http\Controllers;
use App\Models\CompanyInfo;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Department;
use App\Helper\Select;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DoctorController extends Controller
{
    public $companyInfo=array();
    public $query='';
    public $fields=array();

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data =Doctor::withdepartment()->withspecialization()->withuserdata()->get();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $actionBtn = '
                        <div class="text-center">
                        <button type="button"  class="btn btn-primary btn-sm view" data-prefix="Doctor" id="'.$data->doctor_id.'" data-id="'.$data->doctor_id.'"><i class="fas fa-eye"></i></button>
                        &nbsp;
                        <button type="button" class="btn btn-warning btn-sm update" data-prefix="Doctor" id="'.$data->doctor_id.'" data-id="'.$data->doctor_id.'"><i class="fas fa-edit"></i></button>
                        &nbsp;
                        <button type="button"  class="btn btn-danger btn-sm delete" id="'.$data->doctor_id.'" data-id="'.$data->doctor_id.'"><i class="fas fa-times"></i></button>
                        <button type="button"  class="btn btn-success btn-sm status p-0" id="'.$data->doctor_id.'" data-prefix="doctor" data-status="'.$data->doctor_status.'" data-id="'.$data->doctor_id.'"><i class="far fa-calendar-check  fa-2x"></i></button>
                        </div>
                        ';
                    return $actionBtn;
                })
            ->editColumn('doctor_status', function ($data) {
                if($data->is_active())
                    $status = '<span  class="badge badge-success btn-sm">'.$data->doctor_status.'</span>';
                else
                    $status = '<span  class="badge badge-danger btn-sm">'.$data->doctor_status.'</span>';
                    return $status;
                 })
           ->make(true);
        }

        $info=CompanyInfo::first();
        $page='doctor';
        $email=Select::instance()->load_email();
        $department=Select::instance()->load_department();
        $specialization=Select::instance()->load_specialization();
        return view('doctor',compact('info','page','email','department','specialization'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'specialization_id' => ['required', 'string', 'max:255'],
            'doctor_email' => ['required', 'email', 'max:255','exists:users,email'],
            'department_id'=>['required', 'numeric'],
        ]);
        $doctoruser=User::where('email',$request->doctor_email)->first();
        if(!Doctor::where('doctor_user_id',$doctoruser->id)->exists()){
            $field['doctor_user_id']=$doctoruser->id;
            $field['doctor_name']=$request->doctor_name??$doctoruser->username;
            Doctor::create(array_merge($request->all(),$field));
        }
        elseif( $doctoruser->is_doctor()){
            return response()->json('<div class="alert alert-warning">The user is already a doctor!</div>');
        }

        Department::where('department_id', $request->department_id)->increment('department_capacity') ;
        $doctoruser->update(['user_type'=>'doctor']);
        return response()->json('<div class="alert alert-success">The doctor data was created!</div>');
    }

    public function update(Request $request, Doctor $doctor)
    {

        if (!$request->hasAny('doctor_status','status')){
            $this->validate($request, [
                'specialization_id' => ['required', 'string', 'max:255'],
                'doctor_email' => ['required', 'email', 'max:255','exists:users,email'],
                'department_id'=>['required', 'numeric'],
            ]);
            $doctoruser=User::where('email',$request->doctor_email)->first();

            if($doctoruser){
                $this->fields['doctor_user_id']=$doctoruser->id;
                /*if the name of the doctor is given then
                use it else use it from the username of the doctor*/
                $this->fields['doctor_name']=$request->doctor_name??$doctoruser->username;
                if( !$doctoruser->is_doctor()){
                    $doctoruser->update(['user_type'=>'doctor']);
                }
            }
        }
        //store the previous department id
        $previousDepartmentId = $doctor->department_id;
        $doctor->update(array_merge($request->all(),$this->fields));

        if (!$request->hasAny('doctor_status','status')){
            //store the newly provided department id by the user
            $newDepartmentId = $request->department_id;

            //store the doctor's department
            //$newDepartment = Department::find($newDepartmentId);
            $newDepartment = $doctor->department;

            //find the total number of doctor in the new department
            $doctorCount = Doctor::where('department_id', $newDepartmentId)->count();
            //update the department capacity with new doctor count
            $newDepartment->update(['department_capacity' => $doctorCount]);

            /*if the old department id is not equal to the new id that
            means department has changed so update the department
            capacity of the old department as well*/
            if ($previousDepartmentId != $newDepartmentId) {
                $previousDepartment = Department::find($previousDepartmentId);
                $previousDepartmentCount = Doctor::where('department_id', $previousDepartmentId)->count();
                $previousDepartment->update(['department_capacity' => $previousDepartmentCount]);
            }
        }
        return response()->json(array(
            'response'=>'<div class="alert alert-success">'.'Doctor Details Updated'.'</div>'
        ));
    }


    public function show(Doctor $doctor)
    {
        $output = '<div class="table-responsive">
        <table class="table table-bordered">
            <tr> <td>Doctor Name</td>
                <td>'.$doctor->doctor_name.'</td>
            </tr> <tr>
                <td>Doctor Email</td>
                <td>'.$doctor->user->email.'</td>
            </tr> <tr>
                <td>Doctor Mobile No.</td>
                <td>'.$doctor->user->contact_no.'</td>
            </tr> <tr>
                <td>Doctor Address</td>
                <td>'.$doctor->user->address.'</td>
            </tr> <tr>
                <td>Department</td>
                <td>'.$doctor->department->department_name.'</td>
            </tr> <tr>
                <td>Specialization</td>
                <td>'.$doctor->specialization->specialization_name.'</td>
            </tr><tr>
                <td>Status</td>
                <td><span class="badge badge-'.($doctor->doctor_status == "inactive"?'danger">Not available':'success">Available').'</span></td>
            </tr></table></div>';
            return response()->json(array('response'=>$output));
        }

    public function edit(Doctor $doctor)
    {
        $row =Doctor::withdepartment()->withspecialization()->withuserdata()
        ->where('doctors.doctor_id',$doctor->doctor_id)
        ->first();
        return response()->json($row);
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->user->update(['user_type'=>'user']);
        $doctor->delete();
        $doctor->department->decrement('department_capacity') ;
        return response()->json(array('response'=>'<div class="alert alert-success">The Doctor was deleted!</div>'));
    }
}
