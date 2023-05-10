<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use DateTime;
class Schedule extends Model
{
    use HasFactory;

    protected $primaryKey='schedule_id';
    protected $fillable = ['department_id','schedule_status','doctor_id',
    'schedule_start_time','schedule_end_time','available_days'];

    protected $hidden = ['password','remember_token'];
    public $timestamps = false; //only want to used created_at column
    protected $casts = ['schedule_start_time' => 'datetime','schedule_end_time' => 'datetime'];

    public function doctorName(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ucwords($value),
           
        );
    }

    public function getdepartmentNameAttribute($name)
    {
        return ucwords($name);
    }

    public function getavailableTimeAttribute()
    {
        return $this->schedule_start_time.'-'.$this->schedule_end_time;
    }


    public function scheduleStartTime(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                if ($value instanceof DateTime) {
                    return $value->format('H:i');
                } else {
                    $value = new DateTime($value);
                    return $value->format('H:i');
                }
            }
        );
    }

    public function scheduleEndTime(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                if ($value instanceof DateTime) {
                    return $value->format('H:i');
                } else {
                    $value = new DateTime($value);
                    return $value->format('H:i');
                }
            },
            set: function ($value) {
                if ($value instanceof DateTime) {
                    return $value->format('H:i');
                } else {
                    $value = new DateTime($value);
                    return $value->format('H:i');
                }
            }
        );
    }

    public function doctor(){
        return $this->belongsTo(Doctor::class,'doctor_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->available_days = implode(',',$model->available_days);
        });
        static::updating(function ($model) {
          $model->available_days = implode(',',$model->available_days);
        });
    }
}
