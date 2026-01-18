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
                {{-- <div class="col-md-2">
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
                </div> --}}
                <div class="col-md-2 d-flex align-items-end">
                    <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    @forelse($assessmentGroups as $childId => $artworks)
        @php $student = $artworks->first()->student; @endphp

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
                    {{-- <thead class="table-light text-center">
                        <tr>
                            <th width="140">Tanggal</th>
                            <th width="100">Waktu</th>
                            <th>Lokasi</th>
                            <th>Elemen CP</th>
                            <th>Keterangan</th>
                            <th>Analisis Guru</th>
                            <th>Dokumentasi</th>
                        </tr>
                    </thead> --}}
                    <tbody>
                        @foreach ($artworks as $item)
                            <tr>
                                <td class="">Tanggal: {{ optional($item->created_at)->format('d/m/Y') }}
                                </td>
                                <td class="">Waktu:
                                    {{ \Carbon\Carbon::parse($item->time)->format('H:i') ?? '-' }}
                                </td>
                                <td>Elemen CP:
                                    @foreach ($item->cpElements as $cp)
                                        <span
                                            class="badge bg-success-subtle text-dark border mb-1 d-inline-block">{{ $cp->name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            {{-- <tr>
                                <td>Lokasi: {{ $item->location ?? '-' }}</td>
                            </tr> --}}
                            <tr class="text-center">
                                <th class="text-bg-secondary">Celoteh Anak</th>
                                <th class="text-bg-secondary">Analisis Guru</th>
                                <th class="text-bg-secondary">Dokumentasi</th>
                            </tr>
                            <tr>
                                {{-- <td class="text-center">{{ optional($item->date)->format('d/m/Y') }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($item->time)->format('H:i') ?? '-' }}
                                </td>
                                <td>{{ $item->location ?? '-' }}</td>
                                <td>
                                    @foreach ($item->cpElements as $cp)
                                        <span
                                            class="badge bg-success-subtle text-dark border mb-1 d-inline-block">{{ $cp->name }}</span>
                                    @endforeach
                                </td> --}}
                                <td style="max-width:300px">{{ $item->child_speech ?? ($item->description ?? '-') }}
                                </td>
                                <td style="max-width:300px">{{ $item->teacher_analysis ?? '-' }}</td>
                                <td>
                                    @php $gambars = $item->getMedia('artwork_image'); @endphp
                                    @if ($gambars->isNotEmpty())
                                        <div class="d-flex flex-column gap-2 align-items-center">
                                            @foreach ($gambars as $gambar)
                                                <img src="{{ route('media.show', $gambar) }}" alt="Dokumentasi"
                                                    class="rounded border"
                                                    style="width: 240px; height: 200px; object-fit: cover; cursor: pointer">
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">Tidak ada Dokumentasi</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center py-5 shadow-sm">
            <i class="fas fa-clipboard-list fa-3x mb-3 d-block"></i>
            <h5>Belum ada data penilaian tersedia.</h5>
            <p class="text-muted">Data akan muncul setelah guru menginput penilaian.</p>
        </div>
    @endforelse
</div>
