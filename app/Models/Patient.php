<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Patient extends Model
{
    use HasFactory;
    protected $primaryKey='patient_id';

    protected $fillable = ['patient_name','patient_user_id','patient_status','patient_enter_by'];


    protected $guarded = ['id'];
    protected $hidden = ['password','remember_token'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointmnet::class);
    }

    public function patient_history()
    {
        return $this->hasMany(PatientHistory::class);
    }

    public function scopeWithDepartment($query)
    {
        $query ->leftJoin('departments', 'departments.department_id', 'doctors.department_id');

    }

    public function scopeWithSpecialization($query)
    {
        $query ->leftJoin('specializations', 'specializations.specialization_id', 'doctors.specialization_id');

    }
    public function scopeWithUserData($query)
    {
        $query->leftJoin('users','users.id','doctors.doctor_user_id');

    }

    protected static function booted()
    {
        static::creating(function ($patient) {
            $patient->patient_enter_by = auth()->id();
        });
    }
}
