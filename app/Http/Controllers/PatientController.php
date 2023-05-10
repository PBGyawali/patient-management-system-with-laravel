<?php

namespace App\Http\Controllers;
use App\Models\CompanyInfo;
use App\Models\Patient;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
class PatientController extends Controller
{
    public $companyInfo=array();
    public $query='';

     public function index(Request $request)
    {
        if ($request->ajax()) {
            $data =Patient::leftJoin('users','users.id','patients.patient_user_id')
            ->get();
            return DataTables::of($data)
            ->addColumn('action', function($data){
                $id=$data->getKey();
                // status of the row
                $status=$data->patient_status;
                // data to display on modal, tables
                $prefix="patient";
                // optional button to display
                $buttons=['delete','edit'];
                $actionBtn = view('text_buttons',compact('buttons','id','prefix'))->render();
               return $actionBtn;                    
            })
            ->addColumn('age', function($data){
                if(!$data->birthdate)
                return 0;                
               return Carbon::parse($data->birthdate)->age;                 
            })

            
           ->make(true);
        }
        $info=CompanyInfo::first();
        $page='patient';
        return view('patients',compact('info','page'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'patient_name' => ['required'],
            'email' => ['required','email'],
            'birthdate' => ['required'],
            'contact_no' => ['required'],
            'gender' => ['required'],
        ]);
        $fields['username']= str_replace(' ','_',$request->patient_name).rand(100, 999);
        $fields['name']= $request->patient_name;
        $fields['user_role']= 'patient';
        $field['patient_user_id']=User::create(array_merge($request->all(),$fields))->id;
        Patient::create(array_merge($request->all(),$field));
        return response()->json(array('response'=>__('message.create',['name'=>'patient'])));       
    }
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
    public function update(Request $request, Patient $patient)
    {
        $patient->update($request->all());
        return response()->json(array('response'=>__('message.update',['name'=>'patient'])));
    }


    public function edit(Patient $patient)
    {
        $row =Patient::leftJoin('users','users.id','patients.patient_user_id')
        ->where('patients.patient_id',$patient->getKey())
        ->first();
        return response()->json($row);
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return response()->json(array('response'=>__('message.delete',['name'=>'patient'])));

    }
}
