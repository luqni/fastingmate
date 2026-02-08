<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dokumentasi - FastingMate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .iframe-container {
            position: relative;
            width: 100%;
            height: 100vh; /* Full viewport height */
            padding-top: 60px; /* Space for header */
        }
        iframe {
            position: absolute;
            top: 60px;
            left: 0;
            width: 100%;
            height: calc(100% - 60px);
            border: 0;
        }
    </style>
    <script>
        // Fix for Google Docs Embed "DOCS_timing is not defined" error
        window.DOCS_timing = {};
    </script>
</head>
<body class="bg-gray-50 text-gray-900">

    <!-- Simple Header -->
    <div class="fixed top-0 left-0 w-full h-[60px] bg-white border-b border-gray-200 z-50 flex items-center justify-between px-4 sm:px-6">
        <div class="flex items-center gap-3">
             <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="p-2 rounded-full hover:bg-gray-100 transition text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
             </a>
             <h1 class="font-bold text-lg text-gray-800">Dokumentasi FastingMate</h1>
        </div>
        
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold px-2 py-1 bg-emerald-100 text-emerald-700 rounded-md">v1.0</span>
        </div>
    </div>

    <!-- Iframe Container -->
    <div class="iframe-container">
        <iframe src="https://docs.google.com/document/d/e/2PACX-1vQU607PMUwvg2EzqXhu16hwy6_qmDOngSv3UgEV6kUHmTlvneXcSXaPZbzaCHC6E9MX4v2TmRACj9fY/pub?embedded=true" 
                sandbox="allow-scripts allow-same-origin allow-popups allow-forms"
                loading="lazy"></iframe>
    </div>

</body>
</html>
