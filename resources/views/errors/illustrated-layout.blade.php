<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'FastingMate') }}</title>
    <link rel="icon" type="image/png" href="/favicon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full text-center">
        <!-- Logo/Brand -->
        <div class="mb-8 flex justify-center">
            <h1 class="text-3xl font-extrabold text-primary-600 tracking-tight">FastingMate</h1>
        </div>

        <!-- Image/Illustration -->
        <div class="mb-8 flex justify-center">
            @yield('image')
        </div>

        <!-- Content -->
        <div class="space-y-4">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">@yield('heading')</h2>
            <p class="text-gray-500 text-lg leading-relaxed">
                @yield('message')
            </p>
        </div>

        <!-- Actions -->
        <div class="mt-8">
            @yield('action')
        </div>
        
        <div class="mt-12 text-sm text-gray-400">
            &copy; {{ date('Y') }} FastingMate. All rights reserved.
        </div>
    </div>
</body>
</html>
