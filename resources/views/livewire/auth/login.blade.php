<div class="bg-bubbles">
    <section class="py-md-4 py-xl-5 py-4">
        <div class="container">
            <div class="row gy-5 align-items-center justify-content-center mb-5">

                <!-- COPY + ILLUSTRATION -->
                <div class="col-12 col-md-6 col-xl-6">
                    <div class="col-12 col-xl-10">
                        <span class="badge rounded-pill tagline-badge mb-3 px-3 py-2 fw-semibold">🌟 Ceria
                            & Hangat</span>
                        <img class="img-fluid rounded mb-3" loading="lazy" src="{{ asset('images/paud.png') }}"
                            width="140" height="80" alt="Logo RA Nurul Amin">
                        <h2 class="h1 mb-3">Untuk Senyum Kecil Hari Ini & Masa Depan yang Cerah</h2>
                        <p class="lead mb-4">Hadiri setiap capaian kecil Ananda—karena cinta orang tua itu superpower
                            paling nyata. 🌱</p>

                        <!-- Ilustrasi SVG inline (ringan & bebas lisensi) -->
                        <div class="illus-wrap">
                            <svg class="w-100 rounded shadow-sm" style="background:#fff" viewBox="0 0 720 240"
                                xmlns="http://www.w3.org/2000/svg" role="img"
                                aria-label="Orang tua dan anak di taman">
                                <defs>
                                    <linearGradient id="g1" x1="0" x2="0" y1="0"
                                        y2="1">
                                        <stop offset="0%" stop-color="#E8F9FF" />
                                        <stop offset="100%" stop-color="#FFFFFF" />
                                    </linearGradient>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#g1)" />
                                <circle cx="650" cy="42" r="18" fill="#FFC107" />
                                <rect y="180" width="720" height="60" fill="#E9FBEF" />
                                <!-- ayunan -->
                                <g transform="translate(470,80)">
                                    <rect x="0" y="0" width="8" height="120" rx="4" fill="#7AE582" />
                                    <rect x="100" y="0" width="8" height="120" rx="4" fill="#7AE582" />
                                    <rect x="0" y="0" width="108" height="10" rx="5" fill="#4CC9F0" />
                                    <rect x="34" y="70" width="40" height="10" rx="5" fill="#FF6F61" />
                                    <rect x="44" y="10" width="2" height="70" fill="#9adbf5" />
                                    <rect x="76" y="10" width="2" height="70" fill="#9adbf5" />
                                </g>
                                <!-- keluarga -->
                                <g transform="translate(160,110)">
                                    <circle cx="20" cy="10" r="10" fill="#FFB6A3" />
                                    <rect x="12" y="22" width="16" height="46" rx="8" fill="#4CC9F0" />
                                    <circle cx="74" cy="10" r="10" fill="#FFD29D" />
                                    <rect x="66" y="22" width="16" height="46" rx="8" fill="#FF6F61" />
                                    <circle cx="47" cy="18" r="8" fill="#FFD6E3" />
                                    <rect x="41" y="28" width="12" height="30" rx="6" fill="#FFC107" />
                                </g>
                            </svg>
                            <span class="soft-pill"></span>
                        </div>
                    </div>
                </div>

                <!-- CARD LOGIN -->
                <div class="col-12 col-md-6 col-xl-5">
                    <div class="card card-login">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <div class="mb-4">
                                <h3 class="mb-1">Masuk</h3>
                                <p class="text-secondary mb-0">Gunakan email & kata sandi yang telah dikirimkan sekolah.
                                </p>
                            </div>

                            <form wire:submit.prevent="login" novalidate>
                                <div class="row gy-3">

                                    <!-- EMAIL -->
                                    <div class="col-12">
                                        <div class="form-floating position-relative">
                                            <i class="bi bi-envelope input-icon"></i>
                                            <input type="email" wire:model="email" class="form-control"
                                                id="email" placeholder="name@example.com" required>
                                            <label for="email">Email</label>
                                            @error('email')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- PASSWORD -->
                                    <div class="col-12">
                                        <div class="form-floating position-relative">
                                            <i class="bi bi-lock input-icon"></i>
                                            <input type="password" wire:model="password" class="form-control"
                                                id="password" placeholder="Password" required>
                                            <label for="password">Password</label>
                                            <button type="button"
                                                class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2"
                                                style="background: rgba(76,201,240,.12); border:1px solid rgba(76,201,240,.35);"
                                                onclick="const p=document.getElementById('password'); p.type = p.type==='password'?'text':'password'">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @error('password')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- CTA -->
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn btn-sun btn-lg fw-bold" type="submit">👉
                                                Masuk</button>
                                        </div>
                                    </div>

                                    <!-- FOOT ROW -->
                                        <a href="#" class="small">Lupa password?</a>
                                        <span class="small text-secondary mt-2 mt-md-0">
                                            Belum menerima akun? <a href="#">Hubungi wali
                                                kelas</a>.
                                        </span>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
