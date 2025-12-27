<?php

namespace App\Models\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'nisn', 'dob', 'gender'];

    public function parents()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_student');
    }
}
