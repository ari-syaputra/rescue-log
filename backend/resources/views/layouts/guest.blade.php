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

    @stack('scripts')
</body>
</html>