<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Assessment\AsArtwork;
use Illuminate\Support\Facades\Auth;

#[Title('Hasil Karya - RA Nurul Amin')]
#[Layout('components.layouts.app')]
class ArtworkAssessment extends Component
{
    // public $children = [];
    public $studentId = '';
    public $date = '';

    // public array $columns = [
    //     'child' => false,
    //     'date' => true,
    //     'time' => true, // default HIDDEN
    //     'location' => true,
    //     'description' => true,
    //     'cp' => true,
    //     'analysis' => true,
    // ];

    // public function toggleColumn(string $key): void
    // {
    //     if (array_key_exists($key, $this->columns)) {
    //         $this->columns[$key] = !$this->columns[$key];
    //     }
    // }

    public function mount()
    {
        $user = Auth::user();

        abort_unless($user->hasRole('parent'), 403);

        $this->children = $user->children()->get();
    }

    public function getChildrenProperty()
    {
        return Auth::user()->children()->select('id', 'name', 'nisn', 'dob', 'gender')->get();
    }

    public function getSelectedChildProperty()
    {
        // kalau anak dipilih manual
        if ($this->studentId) {
            return $this->children->firstWhere('id', (int) $this->studentId);
        }

        // kalau belum pilih anak → ambil dari anekdot terbaru
        $latestArtwork = AsArtwork::query()->whereHas('student', fn($q) => $q->where('user_id', auth()->id()))->with('student')->latest('date')->first();

        return $latestArtwork?->student;
    }

    public function resetFilter()
    {
        $this->studentId = '';
        $this->date = '';
    }

    public function render()
    {
        $user = Auth::user();

        $artworks = AsArtwork::with('media')
            ->whereHas('student', function ($q) use ($user) {
                $q->where('user_id', $user->id);

                // kalau anak dipilih → filter anak
                if ($this->studentId) {
                    $q->where('id', $this->studentId);
                }
            })
            ->when($this->date, fn($q) => $q->whereDate('date', $this->date))
            ->with(['student', 'cpElements'])
            ->latest('date')
            ->when(
                empty($this->studentId),
                fn($q) => $q->limit(1), // 🔥 hanya 1 data terakhir
            )
            ->get();

        return view('livewire.artwork-assessment', [
            'artworks' => $artworks,
        ]);
    }
}
