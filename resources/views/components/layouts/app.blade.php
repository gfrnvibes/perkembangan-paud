<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">

    {{-- Icon --}}
    <link rel="shortcut icon" href="{{ asset('assets/images/paud.png') }}">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">


    <!-- Font Awesome 5 CDN (Free version) -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-jLKHWMZtD6YvMlXoImdCJjOulFvWDbCoA3D0zWRZLkNBlQg4qJxMBE+Z4YBkt9I5" crossorigin="anonymous">

    <title>{{ $title ?? 'Page Title' }}</title>
    {{-- <script src="{{ asset('assets/libs/chart.js/chart.min.js') }}"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    @livewireStyles
    @stack('styles')
</head>

<body>

    @include('components.navbar')

    <div class="min-vh-100">
        {{ $slot }}
    </div>

    {{-- Footer --}}
    <!-- Footer -->
    <footer class="footer bg-dark text-light">
        <div class="container">
            <div class="row gy-4">

                <!-- Brand & Tagline -->
                <div class="col-md-4">
                    <h5 class="fw-bold">🌟 RA Nurul Amin</h5>
                    <p class="mb-2 small">
                        Bersama mendampingi tumbuh kembang Ananda dengan cinta, perhatian, dan kegembiraan.
                    </p>
                    <p class="mb-0 small">
                        <i class="bi bi-geo-alt-fill"></i> 
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="col-md-4">
                    <h6 class="fw-semibold mb-3">Link Cepat</h6>
                    <ul class="list-unstyled small">
                        <li><a href="{{ route('/') }}" class="text-light text-decoration-none">📈 Jejak Ananda</a>
                        </li>
                        <li><a href="#fitur" class="text-light text-decoration-none">✨ Fitur</a></li>
                        <li><a href="#tentang" class="text-light text-decoration-none">📚 Tentang Kami</a></li>
                        <li><a href="#kontak" class="text-light text-decoration-none">📞 Kontak</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-md-4">
                    <h6 class="fw-semibold mb-3">Hubungi Kami</h6>
                    <p class="mb-1 small"><i class="bi bi-telephone-fill"></i> 0812-3456-7890</p>
                    <p class="mb-1 small"><i class="bi bi-envelope-fill"></i> info@ranurulamin.sch.id</p>
                    <p class="small"><i class="bi bi-clock-fill"></i> Sen–Jum: 07.00–15.00 WIB</p>
                </div>

            </div>

            {{-- <hr class="border-secondary my-3">

            <div class="row">
                <div class="col-12 text-center small">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> © RA Nurul Amin. Semua Hak Dilindungi.
                </div>
            </div> --}}
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
    {{-- <script>
        AOS.init();
    </script> --}}
    @livewireScripts
    @stack('scripts')
</body>

</html>
