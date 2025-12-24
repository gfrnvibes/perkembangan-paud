<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Curriculum\CurriculumPlan;

class CpElement extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    public function curriculumPlans()
    {
        return $this->belongsToMany(CurriculumPlan::class, 'plan_cp_element');
    }
}
