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
                    $id=$data->getKey();
                    // status of the row
                    $status=$data->patient_status;
                    // data to display on modal, tables
                    $prefix="patient";
                    // optional button to display
                    $buttons=['delete','edit','status','view'];
                    $actionBtn = view('control-buttons',compact('buttons','id','status','prefix'))->render();
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
                    $status =in_array($data->patient_status,array( 'In','active'))?'in':'out';
                    $class=$status == 'in'?'success':'danger';
                    return view('custom_badge',compact('status','class'))->render(); 
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
        return view('patients', compact('info','page','department' )      );
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'patient_name' => ['required'],
            'patient_visit_doctor_name' => ['required'],
            'patient_department' => ['required'],
        ]);
        PatientHistory::create($request->all());
        return response()->json(array('response'=>__('message.create',['name'=>'patient activity'])));
      
    }

    //live patient status in each departments

    public function create()
    {
        $result = Department::leftJoin('doctors','doctors.department_id','departments.department_id')
        ->leftJoin('patient_histories','patient_department','departments.department_id')       
         ->select('department_name')
         ->selectRaw("SUM(CASE WHEN doctor_status = 'active' THEN 1 ELSE 0 END ) AS doctors,
                     SUM( CASE WHEN patient_status = 'active' THEN 1 ELSE 0 END ) AS patients")
        ->groupBy('department_name')
        ->get();
        $html = '';
        foreach($result as $row)
        {
            $patient_count=$row->patients;
            $available_doctors=$row->doctors;
            $department_name=$row->department_name;
            $html.= view('live_department_status',
            compact('patient_count','available_doctors','department_name'))->render();            
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
            $this->fields['patient_out_time']=now();
        }

        $patientHistory->update(array_merge(array_filter($request->all()),$this->fields));
        return response()->json(array('response'=>__('message.update',['name'=>'patient history'])));
		
    }
    public function show(PatientHistory $patientHistory)
    {
        $viewdatas =[
            'Patient Name'=>$patientHistory->patient_name,
            'Patient Email'=>$patientHistory->patient->patient_email,
            'Patient Mobile No.'=>$patientHistory->patient->patient_contact,
            'Patient Address'=>$patientHistory->patient->patient_address,
            'Department'=>$patientHistory->department->department_name,
            'Doctor To visit'=>$patientHistory->doctor->doctor_name,
            'Reason to Visit'=>$patientHistory->patient_reason_to_visit,
            'patient remarks'=>$patientHistory->patient_remarks,
            'Status'=>['class'=>$patientHistory->appointment_status == "inactive"?'danger':'success',
                        'value'=>$patientHistory->appointment_status == "inactive"?'waiting':'confirmed'                      ]
        ];
        $output=view('view-modal',compact('viewdatas'))->render();
        return response()->json($output);   
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
        $data['doctor_id'] =Select::instance()->load_doctor($d);
        $data['patient_department'] = $patientHistory->patient_department;
        $data['patient_reason_to_visit'] = $patientHistory->patient_reason_to_visit;
        $data['patient_remarks'] = $patientHistory->patient_remarks;
        $data['patient_source'] = $patientHistory->patient_source;
        return response()->json($data);
    }

    public function destroy(PatientHistory $patientHistory)
    {
        $patientHistory->delete();
        return response()->json(array('response'=>__('message.delete',['name'=>'patient history'])));        
    }
}
