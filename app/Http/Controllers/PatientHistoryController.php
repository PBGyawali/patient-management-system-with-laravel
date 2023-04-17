<?php

namespace App\Http\Controllers;
use App\Models\CompanyInfo;
use App\Models\PatientHistory;
use App\Models\Department;
use App\Models\Doctor;
use App\Helper\Select;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Helper\Helper;
class PatientHistoryController extends Controller
{
    public $fields=array();

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query =PatientHistory::withdepartment()->withdoctors()
            ->leftJoin('users as u1','u1.id','patient_histories.patient_enter_by')
            ->leftJoin('users as u2','u2.id','doctors.doctor_user_id')
            ->select('*','u1.username as patient_enter_by');
            if($request->from_date!=''&& $request->to_date!='')
                $query->whereBetween('patient_enter_time',[$request->from_date, $request->to_date]);
            $data=$query->get();
            return DataTables::of($data)
            ->addColumn('action', function($data){
                $actionBtn = '
                <div class="text-center">
                    <button type="button"class="btn btn-primary btn-sm view" data-id="'.$data->patient_history_id.'"><i class="fas fa-eye"></i></button>
                    &nbsp;
                    <button type="button" data-prefix="Patient History"class="btn btn-warning btn-sm update" data-id="'.$data->patient_history_id.'"><i class="fas fa-edit"></i></button>
                    &nbsp;
                    <button type="button"  class="btn btn-danger btn-sm delete" data-id="'.$data->patient_history_id.'"><i class="fas fa-times"></i></button>
                    <button type="button"  class="btn btn-success btn-sm status p-0" data-prefix="patient" data-status="'.$data->patient_status.'" data-id="'.$data->patient_history_id.'"><i class="far fa-calendar-check  fa-2x"></i></button>
                </div>';
                    return $actionBtn;
                })
            ->editColumn('patient_enter_time', function ($data) {
               return  $data->patient_enter_time->format('Y-m-d H:s');
                     })
            ->editColumn('patient_out_time', function ($data) {
                if($data->patient_out_time==NULL)
                 return ;
                return $data->patient_out_time->format('Y-m-d H:s');
                })
            ->editColumn('patient_status', function ($data) {
                if(in_array($data->patient_status,array( 'In','active')))
                    $status = '<span  class="badge badge-success btn-sm">In</span>';
                else
                $status ='<span  class="badge badge-danger btn-sm">Out</span>';
                    return $status;
                })
                ->editColumn('patient_enter_by', function ($data) {
                    if (auth()->user()->is_admin())
                        return $data->patient_enter_by;
                    return '';
                    })
                ->make(true);

        }
        $info=CompanyInfo::first();
        $page='patient';
        $department=Select::instance()->load_department();
        return view('patient', compact('info','page','department' )      );
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'patient_name' => ['required'],
            'patient_visit_doctor_name' => ['required'],
            'patient_department' => ['required'],
        ]);
        PatientHistory::create($request->all());
        return response()->json(array('response'=>'<div class="alert alert-success">Patient Activity Record Added</div>'));
    }


    public function create()
    {
        $result = Department::all();
        $html = '<div class="row">';
        foreach($result as $row)
        {
            $count=PatientHistory:: whereIn('patient_status', ['In','active'])->where('patient_department',$row->getKey())->count();
            $availabledoctors=Doctor:: where('doctor_status','active')->where('department_id',$row->getKey())->count();
            if($count)
            {
                $html .= '
                <div class="col-lg-2 mb-3">
                    <div class="card bg-info text-white shadow">
                        <div class="card-body">
                            '.$row->department_name.'
                            <div class="mt-1 text-white-50 small">'.(($availabledoctors<=$count)?'Full': $availabledoctors-$count.' Place left' ).'</div>
                        </div>
                    </div>
                </div>
                ';
            }
            else
            {
                $html .= '
                <div class="col-lg-2 mb-3">
                    <div class="card bg-light text-black shadow">
                        <div class="card-body">
                            '.$row->department_name.'
                            <div class="mt-1 text-black-50 small">'.$availabledoctors.' Places Free</div>
                        </div>
                    </div>
                </div>
                ';
            }
        }
        return response()->json($html);
    }

    public function update(Request $request, PatientHistory $patientHistory)
    {
        if (!$request->hasAny('patient_status','patient_remarks')){
            $this->validate($request, [
                        'patient_name' => ['required'],
                        'patient_visit_doctor_name' => ['required'],
                        'patient_department' => ['required'],
             ]);
        }
        if ($request->hasAny('patient_status')){
            $this->fields['patient_out_time']=Helper::get_datetime();
        }

        $patientHistory->update(array_merge(array_filter($request->all()),$this->fields));
		return response()->json(array('response'=>'<div class="alert alert-success">Patient History Updated</div>'));
    }
    public function show(PatientHistory $patientHistory)
    {
        $output = '<div class="table-responsive">
        <table class="table table-bordered">
            <tr> <td>Patient Name</td>
                <td>'.$patientHistory->patient_name.'</td>
            </tr>';
            if($patientHistory->patient)
            $output .='
            <tr>
            <td>Patient Email</td>
            <td>'.$patientHistory->patient->patient_email.'</td>
            </tr>
            <tr>
                <td>Patient Mobile No.</td>
                <td>'.$patientHistory->patient->patient_contact.'</td>
            </tr>
             <tr>
                <td>Patient Address</td>
                <td>'.$patientHistory->patient->patient_address.'</td>
            </tr>';
            $output .='
             <tr>
                <td>Department</td>
                <td>'.$patientHistory->department->department_name.'</td>
            </tr>
            <tr>
                <td>Doctor To visit</td>
                <td>'.$patientHistory->doctor->doctor_name.'</td>
            </tr> <tr>
                <td>Reason to Visit</td>
                <td>'.$patientHistory->patient_reason_to_visit.'</td>
            </tr> <tr>
                <td>Patient_remarks</td><td>
                <textarea name="patient_remarks" id="patient_remarks" class="form-control">'.$patientHistory->patient_remarks.'</textarea></td>
            </tr> <tr>
                <td>Status</td>
                <td><span class="badge badge-'.($patientHistory->appointment_status == "inactive"?'danger">Waiting':'success">Confirmed').'</span></td>
            </tr></table></div>';
            return response()->json(array('response'=>$output));
        }

    public function edit(PatientHistory $patientHistory)
    {
        $d=$patientHistory->patient_visit_doctor_name;
        $data['patient_name'] = $patientHistory->patient_name;
        if($patientHistory->patient){
            $data['patient_email'] = $patientHistory->patient->patient_email;
            $data['patient_mobile_no'] = $patientHistory->patient->patient_contact;
            $data['patient_address'] = $patientHistory->patient->patient_address;
        }
        $data['patient_visit_doctor_name'] =Select::instance()->load_doctor($d);
        $data['patient_department'] = $patientHistory->patient_department;
        $data['patient_reason_to_visit'] = $patientHistory->patient_reason_to_visit;
        $data['patient_remarks'] = $patientHistory->patient_remarks;
        $data['patient_source'] = $patientHistory->patient_source;
        return response()->json($data);
    }

    public function destroy(PatientHistory $patientHistory)
    {
        $patientHistory->delete();
        return response()->json(array('response'=>'<div class="alert alert-success">The Patient History was deleted!</div>'));
    }
}
