<!DOCTYPE html>
<html lang="id" class="w-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- PWA Manifest & Theme Color -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#1d4ed8">

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

<body class="bg-slate-100 font-sans antialiased min-h-screen flex flex-col w-full text-slate-800">

    @include('layouts.navbar-lapangan')

    <main class="flex-1 w-full">
        <div class="w-full px-4 sm:px-6 lg:px-10 py-6">
            @yield('content')
        </div>
    </main>

    <!-- CDN SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. REGISTRASI SERVICE WORKER PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => {
                        console.log('[PWA SW] Service Worker Registered:', reg.scope);
                    })
                    .catch(err => {
                        console.error('[PWA SW] Service Worker Registration failed:', err);
                    });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Global Toast Notification Helper
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-2xl shadow-lg border border-slate-100'
                }
            });

            @if (session('success'))
                Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
            @endif
            @if (session('error'))
                Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
            @endif
            @if (session('warning'))
                Toast.fire({ icon: 'warning', title: "{{ session('warning') }}" });
            @endif
            @if (session('info'))
                Toast.fire({ icon: 'info', title: "{{ session('info') }}" });
            @endif
        });

        // 2. HANDLER NATIVE PWA INSTALL PROMPT
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            const installBtn = document.getElementById('btn-install-pwa');
            if (installBtn) {
                installBtn.classList.remove('hidden');
                installBtn.classList.add('inline-flex');
                
                installBtn.addEventListener('click', () => {
                    installBtn.classList.remove('inline-flex');
                    installBtn.classList.add('hidden');
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User menginstall PWA RESCUE-LOG');
                        }
                        deferredPrompt = null;
                    });
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>