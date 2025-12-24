<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Curriculum\CurriculumPlan;

class AcademicYear extends Model
{
    use SoftDeletes;

    protected $fillable = ['year_range', 'is_active'];

    public function curriculumPlans()
    {
        return $this->hasMany(CurriculumPlan::class);
    }
}
