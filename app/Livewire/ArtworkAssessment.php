<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Assessment\AsArtwork;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\AcademicYear;

#[Title('Hasil Karya - RA Nurul Amin')]
#[Layout('components.layouts.app')]
class ArtworkAssessment extends Component
{
    public $student_id;
    public $date;
    public $semester;
    public $academic_year_id;
    public $status;

public function mount()
{
    $user = Auth::user();

    // Pastikan user sudah login DAN memiliki role 'parent'
    if (!$user || !$user->hasRole('parent')) {
        abort(403);
    }

    $childIds = $user->children()->pluck('students.id');
    
    if ($childIds->isNotEmpty()) {
        $latest = AsArtwork::whereIn('student_id', $childIds)->latest('date')->first();

        if ($latest) {
            $this->student_id = $latest->student_id;
            $this->date = $latest->date;
        }
    }
}

    public function getChildrenProperty()
    {
        return Auth::user()
            ->children()
            ->select(
                'students.id',
                'students.name',
                'students.nisn'
            )
            ->get();
    }


    public function getAcademicYearsProperty()
    {
        return AcademicYear::all();
    }

    public function getAssessmentDataProperty()
    {
        $childIds = $this->children->pluck('id');

        if ($childIds->isEmpty()) {
            return collect();
        }

        $query = AsArtwork::query()
            ->with(['media', 'student', 'cpElements'])
            ->whereIn('student_id', $childIds);

        if ($this->student_id) {
            $query->where('student_id', $this->student_id);
        }

        if ($this->date) {
            $query->whereDate('date', $this->date);
        }

        if ($this->semester || $this->academic_year_id) {
            $query->whereHas('cpElements.curriculumPlan', function ($q) {
                if ($this->semester) {
                    $q->where('semester', $this->semester);
                }
                if ($this->academic_year_id) {
                    $q->where('academic_year_id', $this->academic_year_id);
                }
            });
        }

        if (!$this->date && !$this->semester && !$this->status) {
            $latestDate = (clone $query)->latest('date')->value('date');
            if ($latestDate) {
                $query->whereDate('date', $latestDate);
            }
        }

        return $query->latest('date')->get()->groupBy('student_id');
    }

    public function resetFilters()
    {
        $this->reset(['student_id', 'date', 'semester', 'academic_year_id', 'status']);
    }

    public function render()
    {
        return view('livewire.artwork-assessment', [
            'children' => $this->children,
            'academicYears' => $this->academicYears,
            'assessmentGroups' => $this->assessmentData,
        ]);
    }
}
