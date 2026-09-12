<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- 1. TITLE DINAMIS (Akan mengambil 'RESCUE-LOG' dari .env) -->
    <title>@yield('title', config('app.name', 'RESCUE-LOG')) - Posko Komando</title>
    <link rel="icon" type="image/png" href="{{ asset('img/Rescue-log.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/Rescue-log.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body x-data="{ sidebarOpen: true }" class="bg-slate-50 font-sans antialiased text-gray-800 flex h-screen overflow-hidden">

    <!-- 1. SIDEBAR -->
    @include('layouts.sidebar')

    <!-- 2. CONTAINER KONTEN UTAMA -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- 3. NAVBAR -->
        @include('layouts.navbar')

        <!-- 4. AREA KONTEN HALAMAN -->
        <main class="flex-1 overflow-y-auto p-8 bg-[#f8fafc]">
            @yield('content')
        </main>
    </div>

    <!-- CDN SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Global Toast Notification (Kanan Atas) -->
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