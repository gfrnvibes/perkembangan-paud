<?php

namespace App\Models\Assessment;

use App\Models\Master\Student;
use App\Models\Master\CpElement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AsArtwork extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = ['student_id', 'date', 'child_speech', 'teacher_analysis'];

    public function student() { return $this->belongsTo(Student::class); }

    // Relasi Polymorphic untuk Tagging CP
    public function cpElements()
    {
        return $this->morphToMany(CpElement::class, 'assessable', 'assessment_cp');
    }
}
