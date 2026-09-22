<!DOCTYPE html>
<html lang="id" class="w-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Manifest & Theme Color -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#1d4ed8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- Judul Tab Browser Murni RESCUE-LOG -->
    <title>RESCUE-LOG - Sistem Posko Kebencanaan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    
    <!-- Favicon & PWA Apple Touch Icon -->
    <link rel="icon" type="image/png" href="{{ asset('img/Rescue-log.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/Rescue-log.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/Rescue-log.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lucide Icons & Alpine.js -->
    <script src="https://unpkg.com/lucide@latest"></script>

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

<body class="bg-slate-50 font-sans antialiased text-slate-800 min-h-screen w-full">

    @yield('content')

    <script>
        // 1. REGISTRASI SERVICE WORKER PWA
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => {
                        console.log('[PWA SW Guest] Service Worker registered with scope:', reg.scope);
                    })
                    .catch(err => {
                        console.error('[PWA SW Guest] Registration failed:', err);
                    });
            });
        }

        // 2. HANDLER NATIVE PWA INSTALL PROMPT UNTUK GUEST / LOGIN
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
                            console.log('User menginstall PWA RESCUE-LOG dari Guest App');
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