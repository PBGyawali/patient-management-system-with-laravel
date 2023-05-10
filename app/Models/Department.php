<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $primaryKey='department_id';

    public $timestamps = ["department_created_at"]; //only want to used created_at column

    protected $fillable = ['department_name','department_capacity'];

    const CREATED_AT ="department_created_at"; //and updated by default null set

    const UPDATED_AT = null; //and updated by default null set


    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function patient_history()
    {
        return $this->hasMany(PatientHistory::class,'patient_department');
    }

    public function getdepartmentNameAttribute($name)
    {
        return ucwords($name);
    }

    function is_active(){
        return $this->department_status=='active';
    }

}
