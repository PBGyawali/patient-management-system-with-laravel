<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $primaryKey='doctor_id';

    protected $fillable = ['doctor_name','doctor_status','department_id','specialization_id','doctor_user_id'];

    protected $guarded = ['id'];
    protected $hidden = ['password','remember_token'];

    public function specialization(){
    	return $this->belongsTo(Specialization::class,'specialization_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'doctor_user_id','id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id');
    }

    public function appointment()
    {
        return $this->hasMany(Appointmnet::class,'appointment_doctor_id');
    }
    public function patient()
    {
        return $this->hasMany(Patient::class);
    }
    public function patient_history()
    {
        return $this->hasMany(PatientHistory::class);
    }

    public function scopeWithDepartment($query)
    {
        $query ->leftJoin('departments', 'departments.department_id', 'doctors.department_id');

    }

    function is_active(){
        return $this->doctor_status=='active';
    }

    public function scopeWithSpecialization($query)
    {
        $query ->leftJoin('specializations', 'specializations.specialization_id', 'doctors.specialization_id');

    }
    public function scopeWithUserData($query)
    {
        $query->leftJoin('users','users.id','doctors.doctor_user_id');

    }

    public function getProfileAttribute($value){
        if(is_dir(config('app.images_dir').$value)
        ||  !file_exists(config('app.images_dir').$value))
                return config('app.storage_url').'user_profile.png';
        else
                return config('app.storage_url').$value;
    }

}
