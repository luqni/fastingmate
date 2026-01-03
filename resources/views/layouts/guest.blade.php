<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
             body { font-family: 'Plus Jakarta Sans', sans-serif; }
             .bg-pattern {
                 background-color: #f0f9ff;
                 background-image: radial-gradient(#bae6fd 0.5px, transparent 0.5px), radial-gradient(#bae6fd 0.5px, #f0f9ff 0.5px);
                 background-size: 20px 20px;
                 background-position: 0 0, 10px 10px;
             }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-pattern min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            <div class="flex justify-center mb-8">
                <a href="/" class="text-4xl font-extrabold text-primary-600 tracking-tighter">
                   FastingMate
                </a>
            </div>

            <div class="w-full bg-white/70 backdrop-blur-xl rounded-[2rem] shadow-soft border border-white/50 p-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
