<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <title>@yield('title', 'SILAPU - Puspamukti Smart Village')</title>

    <!-- Favicon / Logo Tab -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-desa-puspamukti.jpg') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo-desa-puspamukti.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-desa-puspamukti.jpg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @layer base {
            html,body { margin:0; padding:0; }
            body { overscroll-behavior:none; }
        }
        ::-webkit-scrollbar { display:none; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
</head>
    <body class="bg-surface font-body-md text-on-surface">
    {{ $slot }}
    @stack('scripts')
</body>
</html>
