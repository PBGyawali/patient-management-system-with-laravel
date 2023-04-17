<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;

    protected $primaryKey='specialization_id';
    
    protected $fillable = ['specialization_name','specialization_status'];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function getspecializationNameAttribute($name)
    {
        return ucwords($name);
    }

}
