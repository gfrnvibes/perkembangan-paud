<div class="container py-4">
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <h5 class="card-title mb-3"><i class="fas fa-filter me-2"></i>Filter Perkembangan</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Pilih Anak</label>
                    <select wire:model.live="student_id" class="form-select">
                        <option value="">Semua Anak</option>
                        @foreach ($children as $child)
                            <option value="{{ $child->id }}">{{ $child->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Tanggal</label>
                    <input type="date" wire:model.live="date" class="form-select">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Semester</label>
                    <select wire:model.live="semester" class="form-select">
                        <option value="">Pilih Semester</option>
                        <option value="1">Semester 1</option>
                        <option value="2">Semester 2</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Tahun Ajaran</label>
                    <select wire:model.live="academic_year_id" class="form-select">
                        <option value="">Pilih Tahun</option>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->year_range }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    @forelse($assessmentGroups as $childId => $checklists)
        @php $student = $checklists->first()->student; @endphp

        <div class="card shadow-sm mb-5">
            <div class="card-header bg-primary text-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h6 class="mb-0">NAMA SISWA: <strong>{{ strtoupper($student->name) }}</strong></h6>
                    </div>
                    <div class="col-md-4">
                        <h6 class="mb-0">NISN: <strong>{{ $student->nisn ?? '-' }}</strong></h6>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="small opacity-75 d-block">Kelas</span>
                        <span class="fw-bold">
                            {{ $student->classrooms->first()->name ?? 'Belum Ada Kelas' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="120">Minggu</th>
                            <th>Tujuan Pembelajaran (TP)</th>
                            <th width="200">Status Capaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Mengelompokkan data berdasarkan nomor minggu dari rencana kurikulum
                            $groupedByWeek = $checklists->groupBy(
                                fn($item) => $item->learningObjective->curriculumPlan->week_number,
                            );
                        @endphp

                        @foreach ($groupedByWeek as $weekNumber => $items)
                            @foreach ($items as $iteration => $item)
                                <tr>
                                    {{-- Kolom Minggu dengan Rowspan dinamis sesuai jumlah TP di minggu tersebut --}}
                                    @if ($iteration === 0)
                                        <td rowspan="{{ $items->count() }}" class="text-center fw-bold bg-light">
                                            Minggu {{ $weekNumber }}
                                        </td>
                                    @endif

                                    {{-- Kolom Deskripsi Tujuan Pembelajaran --}}
                                    <td class="ps-3">
                                        {{ $item->learningObjective->description }}
                                    </td>

                                    {{-- Kolom Status dengan Icon FontAwesome --}}
                                    <td class="text-center">
                                        @if ($item->status == 'muncul')
                                            <span class="badge text-bg-success">
                                                <i class="fas fa-check-circle me-1"></i> Muncul
                                            </span>
                                        @elseif($item->status == 'tidak_muncul')
                                            <span class="badge text-bg-danger">
                                                <i class="fas fa-times-circle me-1"></i> Belum Muncul
                                            </span>
                                        @else
                                            <span class="badge text-bg-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i> Bimbingan
                                            </span>
                                        @endif
                                        <div class="text-muted" style="font-size: 0.7rem;">
                                            {{ date('d/m/Y', strtotime($item->date)) }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center py-5 shadow-sm">
            <i class="fas fa-clipboard-list fa-3x mb-3 d-block"></i>
            <h5>Belum ada data penilaian tersedia.</h5>
            <p class="text-muted">Data akan muncul setelah guru menginput penilaian mingguan.</p>
        </div>
    @endforelse
</div>
