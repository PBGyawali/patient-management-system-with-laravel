<?php

namespace App\Http\Controllers;
use App\Models\CompanyInfo;
use App\Models\Patient;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
class PatientController extends Controller
{
    public $companyInfo=array();
    public $query='';

     public function index(Request $request)
    {
        if ($request->ajax()) {
            $data =Patient::leftJoin('departments', 'departments.department_id', 'patient_histories.department_id')
            ->leftJoin('specializations', 'specializations.specialization_id', 'doctors.specialization_id')
            ->leftJoin('users','users.id','doctors.doctor_user_id')
            ->get();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $actionBtn = '
                    <div align="center">
			<button type="button"  class="btn btn-warning btn-sm update" data-prefix="Patient"data-id="'.$data->appointment_id.'"><i class="fas fa-edit"></i></button>
			&nbsp;
			<button type="button"  class="btn btn-danger btn-sm delete" data-id="'.$data->appointment_id.'"><i class="fas fa-times"></i></button>
			</div>';
                    return $actionBtn;
                })

           ->make(true);
        }
        $info=CompanyInfo::first();
        $page='patient';
        return view('patient',compact('info','page'));
    }

    public function store(Request $request)
    {
        $field['patient_user_id']=User::where('email',$request->patient_email)->first()->id;
        Patient::create(array_merge($request->all(),$field));
        return response()->json('<div class="alert alert-success">The patient data was created!</div>');
    }

    public function update(Request $request, Patient $patient)
    {
        $patient->update($request->all());
		return response()->json(array('response'=>'<div class="alert alert-success">Patient data Updated</div>'));
    }


    public function edit(Patient $patient)
    {
        $row =Patient::leftJoin('departments', 'departments.department_id', 'patient_histories.patient_department')
        ->leftJoin('doctors', 'doctors.doctor_id', 'patient_histories.patient_visit_doctor_name')
        ->leftJoin('users','users.id','doctors.doctor_user_id')
        ->where('patient_histories.patient_id',$patient->patient_id)
        ->first();
        $data['patient_name'] = $row->patient_name;
        $data['patient_email'] = $row->patient_email;
        $data['patient_mobile_no'] = $row->patient_contact;
        $data['patient_address'] = $row->patient_address;
        $data['patient_visit_doctor_name'] =$row->patient_visit_doctor_name;
        $data['patient_department'] = $row->patient_department;
        $data['patient_visit_doctor_name_detail'] = $row->doctor_name;
        $data['patient_department_detail'] = $row->department_name;
        $data['patient_reason_to_visit'] = $row->patient_reason_to_visit;
        $data['patient_outing_remark'] = $row->patient_outing_remark;
        return response()->json($data);
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return response()->json('<div class="alert alert-success">The Patient was deleted!</div>');
    }
}
