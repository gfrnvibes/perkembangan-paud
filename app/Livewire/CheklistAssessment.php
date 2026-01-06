<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Master\AcademicYear;
use Illuminate\Support\Facades\Auth;
use App\Models\Assessment\AsChecklist;

#[Title('Cheklist - RA Nurul Amin')]
#[Layout('components.layouts.app')]
class CheklistAssessment extends Component
{
    public $student_id;
    public $date;
    public $semester;
    public $academic_year_id;
    public $status;

    public function mount()
    {
        // Menggunakan relasi children() terbaru
        $childIds = Auth::user()
            ->children()
            ->pluck('students.id');

        if ($childIds->isNotEmpty()) {
            $latest = AsChecklist::whereIn('student_id', $childIds)->latest('date')->first();

            if ($latest) {
                $this->student_id = $latest->student_id;
                $this->date = $latest->date;
            }
        }
    }

    public function resetFilters()
    {
        $this->reset(['student_id', 'date', 'semester', 'academic_year_id', 'status']);
    }

    public function getChildrenProperty()
    {
        // Memanggil relasi hasMany children
        return Auth::user()->children;
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

        // app/Livewire/Parent/ChecklistHistory.php

        $query = AsChecklist::query()
            ->with(['learningObjective.curriculumPlan', 'student.classrooms']) // Sesuaikan di sini
            ->whereIn('student_id', $childIds);

        // Filter dinamis
        if ($this->student_id) {
            $query->where('student_id', $this->student_id);
        }
        if ($this->date) {
            $query->where('date', $this->date);
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->semester || $this->academic_year_id) {
            $query->whereHas('learningObjective.curriculumPlan', function ($q) {
                if ($this->semester) {
                    $q->where('semester', $this->semester);
                }
                if ($this->academic_year_id) {
                    $q->where('academic_year_id', $this->academic_year_id);
                }
            });
        }

        // Ambil data paling terakhir jika tidak ada filter tanggal
        if (!$this->date && !$this->semester && !$this->status) {
            $latestDate = (clone $query)->latest('date')->value('date');
            if ($latestDate) {
                $query->where('date', $latestDate);
            }
        }

        return $query->get()->groupBy('student_id');
    }

    public function render()
    {
        return view('livewire.cheklist-assessment', [
            'children' => $this->children,
            'academicYears' => $this->academicYears,
            'assessmentGroups' => $this->assessmentData,
        ]);
    }
}
