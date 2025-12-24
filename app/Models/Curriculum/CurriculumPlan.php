<?php

namespace App\Models\Curriculum;

use App\Models\Master\AcademicYear;
use App\Models\Master\CpElement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurriculumPlan extends Model
{
    use SoftDeletes;

    protected $fillable = ['academic_year_id', 'semester', 'week_number', 'theme'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function cpElements()
    {
        return $this->belongsToMany(CpElement::class, 'plan_cp_element');
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function learningObjectives()
    {
        return $this->hasMany(LearningObjective::class);
    }
}
