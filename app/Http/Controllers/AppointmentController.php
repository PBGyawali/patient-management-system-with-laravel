<?php

namespace App\Http\Controllers;
use App\Models\CompanyInfo;
use App\Models\Appointment;
use App\Helper\Select;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Doctor;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query =Appointment::withdepartment()->withdoctors()->withpatientdata()
               ->withdoctordata('u1')
            ->leftJoin('users as u2','u2.id','appointments.appointment_enter_by')
            ->select('*','u2.username as appointment_enter_by','appointments.patient_name as patient_name');
            if($request->from_date!=''&& $request->to_date!='')
                $query->whereBetween('appointment_start_time',[$request->from_date, $request->to_date]);
            $data=$query->get();
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $actionBtn = '
                    <div class="text-center">
                        <button type="button" class="btn btn-warning btn-sm border border-white update" data-prefix="Appointment" data-id="'.$data->appointment_id.'"><i class="fas fa-edit"></i></button>
                       <button type="button" class="btn btn-danger btn-sm border border-white delete" data-id="'.$data->appointment_id.'"><i class="fas fa-times"></i></button>
                        <button type="button" class="btn btn-primary btn-sm view border border-white" data-prefix="Appointment" data-id="'.$data->appointment_id.'"><i class="fas fa-eye"></i></button>
                        <button type="button" class="btn btn-success btn-sm status p-0 border border-white" data-prefix="appointment" data-status="'.strtolower($data->appointment_status).'" data-id="'.$data->appointment_id.'"><i class="far fa-calendar-check  fa-2x"></i></button>
                    </div>';
                    return $actionBtn;
                })
            ->editColumn('appointment_start_time', function ($data) {
                    return $data->appointment_start_time->format('Y-m-d H:i');
                     })
            ->editColumn('appointment_end_time', function ($data) {
                    if($data->appointment_end_time!==NULL)
                    return $data->appointment_end_time->format('Y-m-d H:i');
                })
                //edit background and status text according to if the appointment was in past or future
                //if the appointment status is active or not active
            ->editColumn('appointment_status', function ($data) {
                    $start = $data->appointment_start_time;
                    $status = $data->is_active() ? ($start < today() ? 'Finished' : 'Confirmed') : ($start > today() ?'Waiting':ucfirst($data->appointment_status));
                    $class = $data->is_active() ? ($start < today() ?'primary':'success') : ($start < today() ? 'warning' : 'danger');
                    return '<span class="badge border border-white badge-' . $class . ' btn-sm">' . $status . '</span>';
                })
            //adjust the default background of table row
            ->setRowClass(function ($data) {
                $start=$data->appointment_start_time;
                $end=$data->appointment_end_time;
                /*set background blue if the appointment is today but time has not yet reached
                and appointment end time is not entered
                adjust for 10 minutes delay of patient arrival or appointment start time */
                if($start->isToday()&& $start->gt(now()->subMinutes(10)) && $end==null)
                    return  'bg-info text-white';
                /* set background red if the appointment has already started
                and appointment end time is not entered
                adjust for 20 minutes as appointment time */
                if($start->isToday()&& $start->lt(now()->subMinutes(20)) && $end==null)
                    return  'bg-danger text-white';
                })
                ->editColumn('appointment_enter_by', function ($data) {
                    return auth()->user()->is_admin() ? $data->appointment_enter_by : '';
                    })
           ->make(true);
        }
        $info=CompanyInfo::first();
        //load deprtment for select list
        $department=Select::instance()->load_department();
        $page='appointment';
        return view('appointment',compact('info','page','department'));
    }

    //send doctor list for select menu
    public function create()
    {
        $doctor=Doctor::all();
        return response()->json($doctor);
    }

    public function show(Appointment $appointment)
    {
        $output = '<div class="table-responsive">
        <table class="table table-bordered">';

    if($appointment->patient)
        $output .='
            <tr>
                <td>Patient Name</td><td>'.$appointment->patient->patient_name.'</td>
            </tr>
            <tr>
                <td>Patient Email</td><td>'.$appointment->patient->patient_email.'</td>
            </tr>
            <tr>
                <td>Patient Mobile No.</td><td>'.$appointment->patient->patient_contact.'</td>
            </tr>
            <tr>
                <td>Patient Address</td><td>'.$appointment->patient->patient_address.'</td>
            </tr>';
            $output .='
            <tr>
                <td>Department</td><td>'.$appointment->department->department_name.'</td>
            </tr>
            <tr>
                <td>Doctor To visit</td><td>'.$appointment->doctor->doctor_name.'</td>
            </tr>
            <tr>
                <td>Reason to Visit</td><td>'.$appointment->appointment_reason.'</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    <span class="badge badge-'.($appointment->appointment_status == "inactive"?'danger">Waiting'
                    :'success">Confirmed')
                    .'</span>
                </td>
            </tr>';
            if(auth()->user()->is_admin())
            $output .=
            '<tr>
                <td>Entered By</td><td>'.$appointment->user->username.'</td>
            </tr>'
            .' </table>
            </div>';
            return response()->json(array('response'=>$output));
        }
        public function edit(Appointment $appointment)
        {
            $row =Appointment::withdepartment()->withdoctors()->withpatientdata()
            ->withdoctordata()
               ->where('appointment_id',$appointment->appointment_id)
               ->addselect('*','appointments.patient_name as patient_name')
                ->first();
                $row->appointment_doctor_id = Select::instance()->load_doctor($row->appointment_doctor_id);
                $row->appointment_date =$row->appointment_start_time->format('Y-m-d');
                $row->appointment_time = $row->appointment_start_time->format('H:i');
            return response()->json($row);
        }

    public function store(Request $request)
    {
        $this->validate($request, [
            'patient_name' => ['required', 'string'],
            'appointment_start_time' => ['required'],
            'appointment_date' => ['required'],
        ]);
        //create appointment timestamp from given date and time if both of them exist
        if ($request->filled('appointment_date')&& $request->filled('appointment_start_time')) {
            $request->merge([
                'appointment_start_time' => date('Y-m-d H:i:s', strtotime
                ($request->appointment_date.''.$request->appointment_start_time))
            ]);
        }
        Appointment::create(array_filter($request->all()));
         return response()->json(array('response'=>'<div class="alert alert-success">Appointment Added</div>'));
    }
    public function update(Request $request, Appointment $appointment)
    {
        if (!$request->hasAny('appointment_status','patient_remark')){
            $this->validate($request, [
                        'patient_name' => ['required', 'string'],
                        'appointment_start_time' => ['required'],
                        'appointment_date' => ['required'],
             ]);
        }
        //create appointment timestamp from given date and time if both of them exist
        if ($request->filled('appointment_date')&& $request->filled('appointment_start_time')) {
            $request->merge([
                'appointment_start_time' => date('Y-m-d H:i:s', strtotime
                ($request->appointment_date.''.$request->appointment_start_time))
            ]);
        }

        $appointment->update(array_filter($request->all()));
		return response()->json(array('response'=>'<div class="alert alert-success">'.
                ' Appointment Updated'
        .'</div>'));
    }


    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(array('response'=>'<div class="alert alert-success">The Appointment was deleted!</div>'));
    }
}
