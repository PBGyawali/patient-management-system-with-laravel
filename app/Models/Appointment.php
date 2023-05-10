<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Appointment extends Model
{
    use HasFactory;
    protected $primaryKey='appointment_id';

    protected $fillable = ['appointment_start_time','appointment_end_time',
    'patient_name','appointment_doctor_id','patient_id','appointment_source','appointment_status',
    'appointment_department_id','appointment_enter_by','appointment_reason'];

    protected $casts = ['appointment_start_time' => 'datetime','appointment_end_time' => 'datetime'];

    protected $guarded = ['id'];
    protected $hidden = ['password','remember_token'];

    public function department()
    {
        return $this->belongsTo(Department::class,'appointment_department_id','department_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class,'appointment_doctor_id','doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class,'appointment_id','patient_id');
    }
    public function patient_history()
    {
        return $this->belongsTo(PatientHistory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'appointment_enter_by','id');
    }
    public function getAppointmentReasonAttribute($value){
        return ucfirst($value);
    }

    public function scopeWithDepartment($query)
    {
        $query->leftJoin('departments','departments.department_id','appointments.appointment_department_id');

    }

    public function scopeWithDoctors($query)
    {
        $query->leftJoin('doctors','appointments.appointment_doctor_id','doctors.doctor_id');

    }
    public function scopeWithPatientData($query)
    {
        $query->leftJoin('patients','patients.patient_id','appointments.appointment_id');
    }

    public function scopeWithDoctorData($query,$alias=null)
    {
        $query->leftJoin('users'.($alias?' as '.$alias:''),($alias?$alias:'users').'.id','doctors.doctor_user_id');
    }
    public function is_active()
    {
        return in_array(strtolower($this->appointment_status),['active','confirmed']);
    }
    public function creator()
    {
        return 'appointment_enter_by';
    }

    protected static function booted()
    {
        //assign values while creating model
        static::creating(function ($Appointment) {
            $Appointment->appointment_enter_by = auth()->id();
        });
    }


}
