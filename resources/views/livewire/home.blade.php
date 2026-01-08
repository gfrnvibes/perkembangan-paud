<div class="container py-3">
{{-- @auth
<div>
    <livewire:wirechat/>
</div>
@endauth --}}
    <section class="py-lg-5 py-4 mb-5 bg-playful rounded-4 px-4 px-lg-5">
        <div class="row align-items-center g-4">
            <div class="col-12 col-lg-6" data-aos="fade-right">
                <span class="badge badge-soft rounded-pill mb-3 px-3 py-2 fw-semibold">
                    🌱 Pantau Tumbuh Kembang Ananda
                </span>
                <h1 class="display-5 fw-bold hero-title mb-3 text-dark">
                    Cinta, Perhatian, dan <span class="accent">Tumbuh Bersama 👨‍👩‍👧‍👦</span>
                </h1>
                <p class="lead text-secondary mb-4">
                    Satu pintu untuk orang tua memantau aktivitas, perilaku, dan capaian belajar Ananda di
                    <strong class="text-danger fw-bold">RA Nurul Amin</strong> — transparan, real-time, dan mudah
                    dipahami.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('/') }}" class="btn btn-lg btn-sun fw-bold">
                        👉 Lihat Jejak Ananda
                    </a>
                    <a href="#fitur" class="btn btn-lg btn-outline-danger fw-semibold">
                        Galeri
                    </a>
                </div>
            </div>

            <div class="col-12 col-lg-6 d-lg-flex justify-content-end" data-aos="fade-left">
                <div class="hero-illus-wrap mt-3 mt-lg-0">
                    <!-- Ilustrasi SVG sederhana: keluarga + taman bermain (bebas lisensi, buatan inline) -->
                    <svg class="hero-illus" viewBox="0 0 720 480" xmlns="http://www.w3.org/2000/svg" role="img"
                        aria-label="Ilustrasi orang tua dan anak di taman bermain">
                        <defs>
                            <linearGradient id="gSky" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0%" stop-color="#E8F9FF" />
                                <stop offset="100%" stop-color="#FFFFFF" />
                            </linearGradient>
                        </defs>
                        <!-- background -->
                        <rect width="100%" height="100%" fill="url(#gSky)" />
                        <!-- sun -->
                        <circle cx="620" cy="80" r="34" fill="#FFC107" opacity=".9" />
                        <!-- clouds -->
                        <g fill="#EAF6FF" opacity=".9">
                            <ellipse cx="130" cy="90" rx="70" ry="28" />
                            <ellipse cx="180" cy="90" rx="40" ry="18" />
                            <ellipse cx="160" cy="110" rx="48" ry="20" />
                            <ellipse cx="500" cy="120" rx="60" ry="22" />
                            <ellipse cx="540" cy="120" rx="36" ry="16" />
                        </g>
                        <!-- ground -->
                        <rect y="360" width="720" height="120" fill="#E9FBEF" />
                        <rect y="370" width="720" height="110" fill="#D8F7E1" />

                        <!-- slide -->
                        <g transform="translate(440,250)">
                            <rect x="-8" y="0" width="16" height="120" rx="8" fill="#82E29A" />
                            <rect x="88" y="0" width="16" height="120" rx="8" fill="#82E29A" />
                            <path
                                d="M0,18 C50,-10 100,10 100,40 L100,110 L72,110 L72,48 C72,34 52,26 32,36 C12,46 0,62 0,76Z"
                                fill="#4CC9F0" />
                            <rect x="0" y="10" width="100" height="14" rx="7" fill="#FF6F61"
                                opacity=".9" />
                        </g>

                        <!-- tree -->
                        <g transform="translate(90,250)">
                            <rect x="48" y="80" width="18" height="90" rx="6" fill="#8B5E3C" />
                            <circle cx="56" cy="70" r="46" fill="#7AE582" />
                            <circle cx="26" cy="90" r="30" fill="#7AE582" opacity=".9" />
                            <circle cx="86" cy="100" r="30" fill="#7AE582" opacity=".9" />
                        </g>

                        <!-- family (simple shapes) -->
                        <g transform="translate(210,280)">
                            <!-- parent left -->
                            <circle cx="20" cy="20" r="14" fill="#FFB6A3" />
                            <rect x="8" y="34" width="24" height="56" rx="12" fill="#4CC9F0" />
                            <!-- parent right -->
                            <circle cx="86" cy="20" r="14" fill="#FFD29D" />
                            <rect x="74" y="34" width="24" height="56" rx="12" fill="#FF6F61" />
                            <!-- child -->
                            <circle cx="53" cy="34" r="11" fill="#FFD6E3" />
                            <rect x="44" y="46" width="18" height="40" rx="9" fill="#FFC107" />
                            <!-- hands -->
                            <rect x="28" y="50" width="26" height="8" rx="4" fill="#4CC9F0" />
                            <rect x="62" y="50" width="26" height="8" rx="4" fill="#FF6F61" />
                        </g>

                        <!-- confetti dots -->
                        <g fill="#FFD166" opacity=".6">
                            <circle cx="70" cy="40" r="4" />
                            <circle cx="680" cy="160" r="3" />
                            <circle cx="520" cy="60" r="3" />
                            <circle cx="300" cy="40" r="3" />
                        </g>
                    </svg>
                    <span class="shape-pill"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- FITUR -->
    <section id="fitur" class="mt-4 mb-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card kartu-feature h-100">
                    <div class="card-body p-4 p-lg-5">
                        <div class="icon-bubble ib-sun mb-3">
                            <i class="bi bi-mortarboard fs-4"></i>
                        </div>
                        <h3 class="h5 mb-2">Rapot Perkembangan Interaktif</h3>
                        <p class="mb-0 text-secondary">
                            Lihat progres kognitif, motorik, bahasa, dan sosial-emosi dalam tampilan yang simpel—ada
                            highlight dan catatan guru.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card kartu-feature h-100">
                    <div class="card-body p-4 p-lg-5">
                        <div class="icon-bubble ib-sky mb-3">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                        <h3 class="h5 mb-2">Komunikasi Guru–Orang Tua</h3>
                        <p class="mb-0 text-secondary">
                            Pesan dua arah + foto kegiatan harian. Biar update itu real, bukan cuma “katanya”.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card kartu-feature h-100">
                    <div class="card-body p-4 p-lg-5">
                        <div class="icon-bubble ib-mint mb-3">
                            <i class="bi bi-graph-up-arrow fs-4"></i>
                        </div>
                        <h3 class="h5 mb-2">Insight & Rekomendasi</h3>
                        <p class="mb-0 text-secondary">
                            Rangkuman mingguan + tips aktivitas rumah, biar perkembangan Ananda nyambung antara sekolah
                            dan rumah.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if (session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif

</div>
