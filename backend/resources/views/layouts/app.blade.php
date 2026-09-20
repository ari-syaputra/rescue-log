<!DOCTYPE html>
<html lang="id" class="w-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Judul Tab Browser Murni RESCUE-LOG -->
    <title>RESCUE-LOG</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    
    <!-- Favicon Logo Seragam -->
    <link rel="icon" type="image/png" href="{{ asset('img/Rescue-log.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/Rescue-log.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { 
            display: none !important; 
        }

        html,
        body {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>

    @stack('styles')
</head>

<body x-data="{ sidebarOpen: true }" class="bg-gradient-to-br from-slate-100 via-blue-50/40 to-slate-100 font-sans antialiased text-slate-800 flex h-screen w-screen overflow-hidden">

    <!-- 1. SIDEBAR (Full dari atas sampai bawah di sisi paling kiri) -->
    @include('layouts.sidebar')

    <!-- 2. WRAPPER AREA KANAN (NAVBAR + KONTEN) -->
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        
        <!-- NAVBAR -->
        @include('layouts.navbar')

        <!-- AREA KONTEN HALAMAN -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50/70">
            @yield('content')
        </main>

    </div>

    <!-- CDN SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Global Toast Notification Helper
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end', 
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-2xl shadow-lg border border-slate-100'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if(session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif

            @if(session('warning'))
                Toast.fire({
                    icon: 'warning',
                    title: "{{ session('warning') }}"
                });
            @endif

            @if(session('info'))
                Toast.fire({
                    icon: 'info',
                    title: "{{ session('info') }}"
                });
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html>