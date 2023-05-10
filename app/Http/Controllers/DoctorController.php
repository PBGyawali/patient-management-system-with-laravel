<?php

namespace App\Http\Controllers;
use App\Models\CompanyInfo;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Department;
use App\Helper\Select;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class DoctorController extends Controller
{
    public $companyInfo=array();
    public $query='';
    public $fields=array();

    public function index(Request $request)
    {
       
        $doctors=$data =Doctor::withdepartment()
        ->withspecialization()
        ->withuserdata()
        ->paginate(4); 
        if ($request->ajax()) {
            return view('doctor_list',compact('doctors'))->render();
        }
        $info=CompanyInfo::first();
        $page='doctor';
        $email=Select::instance()->load_email();
        $department=Select::instance()->load_department();
        $specialization=Select::instance()->load_specialization();
        return view('doctors',compact('info','page','email','department','specialization','doctors'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'specialization_id' => ['required', 'numeric'],
            'doctor_email' => ['nullable', 'email', 'max:255','exists:users,email'],
            'email' => ['nullable', 'email', 'max:255','unique:users'],
            'doctor_name' => ['required_if:doctor_email,null','max:255'],
            'address' => ['required_if:doctor_email,null','max:255'],
            'department_id'=>['required', 'numeric'],
        ],[],['department_id'=>'department name','specialization_id' =>'specialization name']);
        if($request->doctor_email){            
            $doctoruser=User::where('email',$request->doctor_email)->first();
            if( $doctoruser->is_doctor()){
                return response()->json(['warning'=>'The user is already a doctor!']);
            }
            else{
                $doctoruser->update(['user_role'=>'doctor']);
            }           
        }
        else{
            $fields['username']= str_replace(' ','_',$request->doctor_name).rand(100, 999);
            $fields['name']= $request->doctor_name;
            $fields['address']= $request->address;
            $fields['user_role']= 'doctor';
            $fields['email']= $request->email??$fields['username'].rand(100, 9999).'@'.str_replace(' ','',CompanyInfo::first()->facility_name).'.com';
            $fields['user_status']= 'active';
            $fields['password']= $fields['username'];
            if($request->hasFile('profile_image')){
                $fields['profile']=basename($request->file('profile_image')->store('public/images'));              
            }
            $doctoruser=User::create(array_merge($request->all(),$fields));
        }        
        if(!Doctor::where('doctor_user_id',$doctoruser->id)->exists()){
            $field['doctor_user_id']=$doctoruser->id;
            $field['doctor_name']=$request->doctor_name??$doctoruser->username;
            Doctor::create(array_merge($request->all(),$field));
        }        
        Department::where('department_id', $request->department_id)->increment('department_capacity') ;        
        return response()->json(array('response'=>__('message.create',['name'=>'doctor'])));
    }

    public function update(Request $request, Doctor $doctor)
    {  
        if (!$request->hasAny('doctor_status','status')){
            $this->validate($request, [
                'specialization_id' => ['required', 'numeric'],
                'doctor_email' => ['required_if:email,null', 'email', 'max:255','exists:users,email'],
                'email' => ['required_if:doctor_email,null','nullable', 'email', 'max:255','unique:users'],
                'doctor_name' => ['required_if:doctor_email,null','max:255'],
                'address' => ['required_if:doctor_email,null','max:255'],
                'department_id'=>['required', 'numeric'],
            ],[],['department_id'=>'department name','specialization_id' =>'specialization name']);
        }
        DB::beginTransaction();
        try {
            if($request->doctor_email||$request->email){            
                    $doctoruser=User::where('email',$request->doctor_email??$request->email)->first(); 
                if(!Doctor::where('doctor_user_id',$doctoruser->id)->exists()){
                    $this->fields['doctor_user_id']=$doctoruser->id;
                    $this->fields['doctor_name']=$request->doctor_name??$doctoruser->username;                
                }
                else{
                    $this->fields['address']= $request->address??$doctoruser->address;
                    $this->fields['name']= $doctoruser->name??$request->doctor_name;
                    $this->fields['user_type']= 'doctor';
                    if($request->hasFile('profile_image')){
                        $this->fields['profile']=basename($request->file('profile_image')->store('public/images'));              
                    }
                    $doctoruser->update($this->fields);
                }
            }
            //store the previous department id
            $previousDepartmentId = $doctor->department_id;
            $doctor->update(array_merge($request->all(),$this->fields));
            
            if (!$request->hasAny('doctor_status','status')){
                //store the newly provided department id by the user
                $newDepartmentId = $request->department_id;

                //store the doctor's department
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
            // Commit the transaction if everything is good
            DB::commit();
            return response()->json(array('response'=>__('message.update',['name'=>'doctor'])));
        } catch (\Exception $e) {
            DB::rollBack();
              // Handle the exception and return an error response
              return response()->json(['error'=>__('message.error.update',['reason'=>$e->getMessage()])]);
        }
    }


    public function show(Doctor $doctor)
    {
        $viewdatas=[
                    'Doctor Name'=>$doctor->doctor_name,
                   ' Doctor Email'=> $doctor->user->email,
                    'Doctor Mobile No.'=>$doctor->user->contact_no,
                    'Doctor Address'=>$doctor->user->address,
                    'Department'=>$doctor->department->department_name,
                    'Specialization'=>$doctor->specialization->specialization_name,
                    'Status'=>['class'=>$doctor->doctor_status == "inactive"?'danger':'success',
                    'value'=>$doctor->doctor_status == "inactive"?'Not available':'Available']
        ];
        $output=view('view-modal',compact('viewdatas'));
            return response()->json($output);
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
        return response()->json(array('response'=>__('message.delete',['name'=>'doctor'])));
    }
}
