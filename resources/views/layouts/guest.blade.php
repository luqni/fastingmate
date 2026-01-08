<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FastingMate') }}</title>
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <script>
            window.flashMessages = {
                success: @json(session('success')),
                error: @json(session('error'))
            };
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
             body { font-family: 'Plus Jakarta Sans', sans-serif; }
             .bg-gradient-premium {
                 background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
             }
             .bg-gradient-premium-animation {
                 position: absolute;
                 top: -50%;
                 left: -50%;
                 width: 200%;
                 height: 200%;
                 background: radial-gradient(circle at center, rgba(14, 165, 233, 0.08) 0%, transparent 50%);
                 animation: rotate 60s linear infinite;
             }
             @keyframes rotate {
                 from { transform: rotate(0deg); }
                 to { transform: rotate(360deg); }
             }
             .glass-card {
                 background: rgba(255, 255, 255, 0.9);
                 backdrop-filter: blur(20px);
                 -webkit-backdrop-filter: blur(20px);
                 border: 1px solid rgba(255, 255, 255, 0.5);
                 box-shadow: 0 20px 40px -15px rgba(14, 165, 233, 0.1);
             }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 min-h-screen flex flex-col items-center justify-center p-4 sm:pt-0 relative">
        <!-- Fixed Background -->
        <div class="fixed inset-0 -z-10 overflow-hidden bg-gradient-premium">
            <div class="absolute inset-0 bg-gradient-premium-animation"></div>
        </div>

        <div class="relative w-full max-w-md z-10">


            <div class="w-full glass-card rounded-[2.5rem] p-6 sm:p-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary-400 via-primary-500 to-primary-600"></div>
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center text-sm text-gray-400">
                &copy; {{ date('Y') }} FastingMate. All rights reserved.
            </div>
        </div>
    </body>
</html>
