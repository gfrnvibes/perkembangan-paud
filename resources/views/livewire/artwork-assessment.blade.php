<div class="container p-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-semibold">
            Catatan Anecdotal Anak
        </div>

        {{-- FILTER --}}
        <div class="card-body ">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Pilih Anak</label>
                    <select class="form-select" wire:model.live="studentId">
                        <option value="">Pilih Anak</option>

                        @foreach ($this->children as $child)
                            <option value="{{ $child->id }}">
                                {{ $child->name }}
                            </option>
                        @endforeach
                    </select>

                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" class="form-control" wire:model.live="date">
                </div>

                {{-- <div class="col-md-3">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            Kolom
                        </button>

                        <ul class="dropdown-menu p-2" style="min-width: 220px">
                            @foreach ($columns as $key => $visible)
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="col-{{ $key }}"
                                            wire:click="toggleColumn('{{ $key }}')"
                                            @checked($visible)>
                                        <label class="form-check-label" for="col-{{ $key }}">
                                            {{ ucfirst($key) }}
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div> --}}


                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-secondary w-100" wire:click="resetFilter">
                        Reset Filter
                    </button>

                </div>
            </div>
        </div>

        @if ($this->selectedChild)
            <div class="card-body border-bottom bg-light-subtle">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="text-muted small">Nama</div>
                        <div class="fw-semibold">{{ $this->selectedChild->name }}</div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-muted small">NISN</div>
                        <div class="fw-semibold">{{ $this->selectedChild->nisn ?? '-' }}</div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-muted small">Tanggal Lahir</div>
                        <div class="fw-semibold">
                            {{ optional($this->selectedChild->dob)->format('d M Y') ?? '-' }}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-muted small">Jenis Kelamin</div>
                        <div class="fw-semibold">
                            {{ $this->selectedChild->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif


        {{-- TABLE --}}
        <div class="container mt-3">
            <div class="table-responsive">
                <table class="table table-bordered">
                    {{-- <thead class="table-light">
                        <tr>
                            @if ($columns['child'])
                                <th>Anak</th>
                            @endif
                            @if ($columns['date'])
                                <th>Tanggal</th>
                            @endif
                            @if ($columns['time'])
                                <th>Waktu</th>
                            @endif
                            @if ($columns['location'])
                                <th>Lokasi</th>
                            @endif
                            @if ($columns['description'])
                                <th>Deskripsi</th>
                            @endif
                            @if ($columns['cp'])
                                <th>CP Element</th>
                            @endif
                            @if ($columns['analysis'])
                                <th>Analisis Guru</th>
                            @endif
                            <th>Dokumentasi</th>
                        </tr>
                    </thead> --}}

                    <tbody>
                        @forelse ($artworks as $item)
                            <tr>
                                <td> Tanggal: {{ $item->created_at->format('d/m/Y') }}</td>
                                <td colspan="2">Waktu: {{ \Carbon\Carbon::parse($item->time)->format('H:i') }}
                                    WIB</td>
                            </tr>
                            <tr>
                                <td>Lokasi: {{ $item->location }}</td>


                                <td colspan="2"> Elemen CP:
                                    @foreach ($item->cpElements as $cp)
                                        <span class="badge bg-primary-subtle text-primary border mb-1 d-inline-block">
                                            {{ $cp->name }}
                                        </span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th class="text-bg-secondary text-center">Celoteh Anak</th>
                                <th class="text-bg-secondary text-center">Analisis Guru</th>
                                <th class="text-bg-secondary text-center">Dokumentasi</th>
                            </tr>
                            <tr>

                                <td class="text-wrap" style="max-width: 250px">
                                    {{ $item->child_speech }}
                                </td>

                                <td class="text-wrap" style="max-width: 250px">
                                    {{ $item->teacher_analysis ?? '-' }}
                                </td>
                                <td class="">

                                    @php
                                        $gambars = $item->getMedia('artwork_image');
                                    @endphp

                                    @if ($gambars)
                                        <div class="d-flex gap-2 flex-wrap">
                                            @foreach ($gambars as $gambar)
                                                <img src="{{ route('media.show', $gambar) }}" alt="Dokumentasi"
                                                    class="rounded border"
                                                    style="width: 200px; height: 200px; object-fit: cover; cursor: pointer">
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">
                                            Tidak ada Dokumentasi
                                        </span>
                                    @endif
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada Data.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
