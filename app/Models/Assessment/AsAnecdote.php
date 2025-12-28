<?php

namespace App\Models\Assessment;

use Spatie\Image\Enums\Fit;
use App\Models\Master\Student;
use App\Models\Master\CpElement;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AsAnecdote extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = ['student_id', 'date', 'time', 'location', 'description', 'teacher_analysis'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relasi Polymorphic untuk Tagging CP
    public function cpElements()
    {
        return $this->morphToMany(CpElement::class, 'assessable', 'assessment_cp');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
        ->fit(Fit::Contain, 300, 300)
        ->nonQueued();
    }
}
