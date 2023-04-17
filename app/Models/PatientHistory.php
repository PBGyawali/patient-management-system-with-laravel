<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientHistory extends Model
{
    use HasFactory;
    protected $primaryKey='patient_history_id';

    protected $fillable = ['patient_enter_time','patient_reason_to_visit','patient_out_time',
    'patient_name','patient_visit_doctor_name','patient_id','patient_source','patient_status',
    'patient_department','patient_outing_remark','patient_enter_by','patient_remarks'];

    protected $casts = ['patient_enter_time' => 'datetime','patient_out_time' => 'datetime'];
    
    protected $guarded = ['id'];

    protected $hidden = ['password','remember_token'];


    public function user()
    {
        return $this->belongsTo(User::class,'id','doctor_user_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class,'patient_visit_doctor_name');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointmnet::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class,'patient_department');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class,'patient_id');
    }

    public function patientUser()
    {
        return $this->hasOneThrough(User::class, Patient::class,'patient_id','id','patient_history_id','user_id');
    }
    public function scopeWithDepartment($query)
    {
        $query ->leftJoin('departments', 'departments.department_id', 'patient_histories.patient_department');

    }
    public function scopeWithDoctors($query)
    {
        $query->leftJoin('doctors', 'doctors.doctor_id', 'patient_histories.patient_visit_doctor_name');

    }

    protected static function booted()
    {
        static::creating(function ($patientHistory) {
            $patientHistory->patient_enter_by = auth()->user()->id;
        });
    }
}
