<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    
    <title>@yield('title', config('app.name', 'RESCUE-LOG')) - Posko Komando</title>
    <link rel="icon" type="image/png" href="{{ asset('img/Rescue-log.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/Rescue-log.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body x-data="{ sidebarOpen: true }" class="bg-gradient-to-br from-slate-100 via-blue-50/40 to-slate-100 font-sans antialiased text-gray-800 flex h-screen w-screen overflow-hidden">

    <!-- 1. SIDEBAR (Full dari atas sampai bawah di sisi paling kiri) -->
    @include('layouts.sidebar')

    <!-- 2. WRAPPER AREA KANAN (NAVBAR + KONTEN) -->
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        
        <!-- NAVBAR -->
        @include('layouts.navbar')

        <!-- AREA KONTEN HALAMAN -->
        <main class="flex-1 overflow-y-auto p-8 bg-slate-50/70">
            @yield('content')
        </main>

    </div>

    <!-- CDN SweetAlert2 & Script Toast -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end', 
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
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
        });
    </script>

    @stack('scripts')
</body>
</html>