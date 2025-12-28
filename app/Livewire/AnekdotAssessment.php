<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\Assessment\AsAnecdote;
use App\Models\Master\AcademicYear;

#[Title('Anekdot - RA Nurul Amin')]
#[Layout('components.layouts.app')]
class AnekdotAssessment extends Component
{
    public $student_id;
    public $date;
    public $semester;
    public $academic_year_id;
    public $status;

    public function mount()
    {
        $user = Auth::user();

        abort_unless($user->hasRole('parent'), 403);

        $childIds = $user->children()->pluck('id');
        if ($childIds->isNotEmpty()) {
            $latest = AsAnecdote::whereIn('student_id', $childIds)->latest('date')->first();

            if ($latest) {
                $this->student_id = $latest->student_id;
                $this->date = $latest->date;
            }
        }
    }

    public function getChildrenProperty()
    {
        return Auth::user()->children()->select('id', 'name', 'nisn')->get();
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

        $query = AsAnecdote::query()
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

    public function getSelectedChildProperty()
    {
        // kalau anak dipilih manual
        if ($this->studentId) {
            return $this->children->firstWhere('id', (int) $this->studentId);
        }

        // kalau belum pilih anak → ambil dari anekdot terbaru
        $latestAnecdote = AsAnecdote::query()->whereHas('student', fn($q) => $q->where('user_id', auth()->id()))->with('student')->latest('date')->first();

        return $latestAnecdote?->student;
    }

    public function resetFilter()
    {
        $this->studentId = '';
        $this->date = '';
    }

    public function render()
    {
        return view('livewire.anekdot-assessment', [
            'children' => $this->children,
            'academicYears' => $this->academicYears,
            'assessmentGroups' => $this->assessmentData,
        ]);
    }
}
