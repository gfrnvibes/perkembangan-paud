<?php

namespace App\Models\Curriculum;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use SoftDeletes;
    protected $fillable = ['curriculum_plan_id', 'name'];
    
    public function curriculumPlan() { return $this->belongsTo(CurriculumPlan::class); }
}
