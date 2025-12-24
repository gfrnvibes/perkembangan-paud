<?php

namespace App\Models\Assessment;

use App\Models\Master\Student;
use App\Models\Master\Classroom;
use Illuminate\Database\Eloquent\Model;
use App\Models\Curriculum\LearningObjective;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsChecklist extends Model
{
    use SoftDeletes;

    protected $fillable = ['student_id', 'learning_objective_id', 'date', 'status'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function learningObjective()
    {
        return $this->belongsTo(LearningObjective::class);
    }

    public function classrooms()
{
    return $this->belongsToMany(Classroom::class, 'classroom_student');
}
}
