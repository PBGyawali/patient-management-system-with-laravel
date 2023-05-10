<?php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Helper\Select;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\PatientHistory;
class CompanyInfoController extends Controller
{
    public $companyInfo=array();

    public function __construct()
    {
        $this->companyInfo=CompanyInfo::first();
    }

    public function index()
    {

        $appointments =Appointment::withdepartment()->withdoctors()->withpatientdata()
                        ->withdoctordata('u1')
                        ->select('*','appointments.patient_name as patient_name')
                        ->take(10)
                        ->get();
        $doctors=Doctor::withdepartment()->withspecialization()->withuserdata()->take(10)->get();
        $patient_histories=PatientHistory::withdepartment()->withdoctors()
            ->leftJoin('users as u2','u2.id','doctors.doctor_user_id')
            ->take(10)
            ->get();
        $info=$this->companyInfo;
        $today_patient=$this->today_patient();
        $yesterday_patient=$this->yesterday_patient();
        $last_seven_day_patient=$this->last_seven_day_patient();
        $total_patient=$this->total_patient();
        $patient_limit=$this->patient_limit();
        $avg_month_patient= $this->avg_month_patient();
        $avg_yearly_patient=$this->avg_yearly_patient();
        $total_appointments=$this->total_appointments();
        $total_active_appointments=$this->total_active_appointments();
        $total_pending_appointments=$this->total_pending_appointments();
        $total_doctors=$this->total_doctors();
        $fullmonthvalue=$this->fullmonthvalue();
        $fullmonth=$this->fullmonth();
        $patient_sources=$this->patient_sources();
        $page='dashboard';       
        return view('home',
        compact('info','total_active_appointments','total_pending_appointments',
        'today_patient','total_doctors','doctors','appointments','patient_histories',
        'yesterday_patient',        'last_seven_day_patient',        'total_patient',
        'patient_limit',        'avg_month_patient',        'avg_yearly_patient'
        ,'total_appointments','fullmonthvalue','fullmonth', 'patient_sources','page')
        );
    }
    public function show()
    {
        $page='Welcome';
        $info=$this->companyInfo;
        if(!$info)
         return $this->create();
         session(['setup' =>null]);
        return view('welcome',['info'=> $this->companyInfo,'page'=>$page]);
    }
    public function create()
    {
         $info=$this->companyInfo;
        if($info)
          return $this->edit();
        session(['setup' =>true]);
        $timezonelist=Select::instance()->Timezone_list();
        $currencylist=Select::instance()->Currency_list();
        return view('settings',compact('info','timezonelist','currencylist'));
    }

    public function edit()
    {
        $info=$this->companyInfo;
        if(!$info)
            return redirect()->route('settings_create');
        session(['setup' =>null]);
        $timezonelist=Select::instance()->Timezone_list($info->facility_timezone);
        $currencylist=Select::instance()->Currency_list($info->facility_currency);
        return view('settings',compact('info','timezonelist','currencylist'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'facility_currency' => ['required', 'string'],
            'facility_timezone' => ['required','timezone'],
            'facility_target' => ['required','numeric'],
            'username' => ['sometimes','required', 'string', 'max:255'],
            'email' => ['sometimes','required', 'email', 'max:255', 'unique:users'],
            'password'=>['sometimes','required', 'string'],
        ]);
            CompanyInfo::create($request->all());
        if ($request->has('email'))
            User::create($request->all());
        session(['setup' =>null]);
        session(['website' =>$request->facility_name]);
        return response()->json(array('response'=>__('message.detail_create')));
    }

    public function update(Request $request, CompanyInfo $company_info)
    {
        $this->validate($request, [
            'facility_currency' => ['required'],
            'facility_timezone' => ['required','timezone'],
            'facility_email' => ['required','email'],
            'facility_target' => ['required','numeric'],
            'facility_contact_no' => ['required','numeric'],
        ]);
        $fields['currency_symbol']=Select::Get_currency_symbol($request->facility_currency);
        $company_info->update(array_merge(array_filter($request->all()),$fields));
        session(['website' =>$request->facility_name]);
        return response()->json(array('response'=>__('message.detail_update')));
    }


    function fullmonth(){
		return $this->loopmonth("&quot;","-7");
	}

	function loopmonth($quote=null){
        $months = array();
        $currentMonth = 1;
        $mainmonth=$currentMonth + 12;
        for ($i = $currentMonth; $i < $mainmonth; $i++) {
            $months[]=$quote.substr(date('F', mktime(0, 0, 0, ($i), 2, date('Y'))),0,3).$quote;
        }
        if ($quote)
        return implode(',',$months);

        return  $months;
    }

    function patient_sources($quote="&quot;"){
        $sources=array('regular','outside','referral');
        $value=array();
        foreach ($sources as $source)
            array_push($value,$quote .$this->CountTable("patient_histories",'patient_source',$source).$quote);
        return  implode(',',$value);
    }
    function today_patient(){
		return $this->Get_patient_value('patient_enter_time');
	}
	function yesterday_patient(){
        return $this->Get_patient_value('patient_enter_time','1');
	}

	function last_seven_day_patient(){
		return $this->Get_patient_value('patient_enter_time','7','>');
	}

    function total_patient($noformat=false){
        $attr['noformat']=$noformat;
		return $this->Get_patient_value('','','',$attr);
    }

	function patient_limit()
    {  $patient=$this->total_patient(true);
        $target=$this->companyInfo->facility_target;
		$limit=$patient/$target*100;
		return number_format($limit);
	}
	function Get_patient_value($date=null,$interval=null,$sign=null,$attr=array())
	{
		$condition=$value=array();
		if ($date){
           $condition['raw']= 'date('.$date.')';
           $value['raw']=date('Y/m/d');
            $attr['compare']=$sign?$sign.'=':'=';
		}
		if($interval){
           $attr['interval']='- INTERVAL '. $interval  .' DAY';
        }
		return $this->CountTable('patient_histories',$condition,$value,$attr);
	}
	function monthvalue($table='patient'){
		return  $this->loopmonthvalue($table,'&quot;');
	}
	function loopmonthvalue($table='patient',$quote=null)
	{	$value=array();
		for($i=1;$i<=date('n');$i++)
			array_push($value,$quote .$this->ValuePerMonth($i,$table).$quote);
		if ($quote)
			return  implode(',',$value);
		return  $value;
	}
	function fullmonthvalue($table='patient'){
		return $this->loopfullmonthvalue($table,"&quot;");
	}

	function loopfullmonthvalue($table='patient',$quote=null)
	{
        $value=array();
		$startpos = date('n');
		for($i=1;$i<=12;$i++)
			array_push($value,$quote .$this->ValuePerMonth($i,$table).$quote);
		$output = array_merge(array_slice($value,$startpos), array_slice($value, 0, $startpos));
		if ($quote)
			return implode(',',$output);
		return $output;
	}

    function avg_month_patient()
    {
        $sum=0;
        for($i=1;$i<=12;$i++)
            $sum+=$this->ValuePerMonth($i);
        $average=$sum/12;
            return  number_format($average);
    }

    function avg_yearly_patient()
    {	 $average=0;
            for($i=1;$i<=12;$i++)
            $average+=$this->ValuePerMonth($i);
            return  number_format($average);
    }

    function total_appointments()
    {
        return $this->CountTable("appointments");
    }
    function total_active_appointments()
    {
        return $this->CountTable("appointments",'appointment_status','active');
    }
    function total_pending_appointments()
    {
        return $this->CountTable("appointments",'appointment_status','inactive');
    }

    function total_doctors()
    {
        return $this->CountTable("doctors");
    }

    function ValuePerMonth($value,$table='patient')	{
        $attr['noformat']=true;
        return $this->CountTable($table."_histories", array('raw'=>'MONTH('.$table.'_enter_time)'),
        array('raw'=>$value),$attr);
	}

    function CountTable($table,$condition=null,$value=null,$attr=array()){
        $compare=$attr['compare']??'=';
        $noformat=$attr['noformat']??false;
       $value=Helper::CountTable($table,$condition,$value,$compare,$attr);
       return Helper::format_number($value,$noformat);
    }
}
