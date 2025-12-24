<?php

namespace App\Models\Curriculum;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LearningObjective extends Model
{
    use SoftDeletes;
    protected $fillable = ['curriculum_plan_id', 'description'];

    public function curriculumPlan() { return $this->belongsTo(CurriculumPlan::class); }
}
