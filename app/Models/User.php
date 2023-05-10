<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['username','email','password','user_status','profile','address','user_type','contact_no','birthdate','gender','role','name'];

    protected $guarded = ['id'];

    protected $hidden = ['password','remember_token','secret_password'];

    public $timestamps = ["created_at"]; //only want to use created_at column

    const UPDATED_AT = null; //and updated by default null set

    protected $casts = ['created_at' => 'datetime'];


       public function setPasswordAttribute($password)
    {
        // Check if the given password is already hashed
        if (Hash::needsRehash($password)) {
                // If it is not hashed, hash it before setting the attribute
                $this->attributes['password'] = Hash::make($password);
        } else {
            // If it is already hashed, don't hash it again
            $this->attributes['password'] = $password;
        }
    }

    public function setUsernameAttribute($name){
        $this->attributes['username'] = ucwords($name);
    }
    public function getUsernameAttribute($name){
        return ucwords($name);
    }

    public function getProfileAttribute($value){
        if(is_dir(config('app.images_dir').$value)
        ||  !file_exists(config('app.images_dir').$value))
                return config('app.storage_url').'user_profile.png';
        else
                return config('app.storage_url').$value;
    }
    public function doctor(){
    	return $this->hasOne(Doctor::class,'doctor_user_id');
    }
    public function patient(){
        return $this->hasOne(Patient::class,'patient_user_id');
    }

    public function patient_history()
    {
        return $this->hasOne(PatientHistory::class,'patient_id');
    }
    public function name()
    {
        return $this->username;
    }

    public function is_admin()
    {
        return $this->user_type=='master'; // this looks for an admin column in your users table
    }

    function is_active(){
        return $this->user_status=='active';
    }

    function is_doctor(){
        return $this->user_role=='doctor';
    }


    function is_same_user($data){
        if ($data instanceof Model) {
            return $this->is($data);
        }
        return $data==$this->id;
    }

}
