<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helper\Select;
use Illuminate\Support\Facades\Hash;
class CompanyInfo extends Model
{
    use HasFactory;

    protected $fillable = [ 'facility_name','facility_email','facility_timezone','facility_target',
        'facility_currency','facility_address','currency_symbol',
        'facility_contact_no','facility_logo','secret_password'
    ];

    public $timestamps = false;

   // protected $hidden = ['secret_password'];
    protected $primaryKey='branch_id';


    public function getFacilityNameAttribute($name){
        return ucwords($name);
    }
    public function setFacilityNameAttribute($name){
        $this->attributes['facility_name'] =ucwords($name);
    }

    public function setFacilityAddressAttribute($name){
        $this->attributes['facility_address'] =ucwords($name);
    }
    public function setSecretPasswordAttribute($password){
        $this->attributes['secret_password'] = Hash::make($password);
    }


    protected static function booted()
    {
        static::creating(function ($info) {
            $info->currency_symbol=Select::Get_currency_symbol($info->facility_currency);
        });
    }
}
