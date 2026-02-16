@extends('errors::illustrated-layout')

@section('title', __('Tidak Ditemukan'))

@section('image')
<div class="w-full max-w-sm">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" class="w-full h-auto text-primary-500">
        <defs>
            <style>
                .cls-1{fill:#e0e7ff;}.cls-2{fill:#4f46e5;}.cls-3{fill:#a5b4fc;}.cls-4{fill:#fff;}
            </style>
        </defs>
        <!-- Background Blob -->
        <path class="cls-1" d="M363.5,198c0,88.4-71.6,160-160,160S43.5,286.4,43.5,198s71.6-160,160-160S363.5,109.6,363.5,198Z" transform="translate(0 -50)"/>
        
        <!-- Simplified Character/Map Concept -->
        <circle cx="200" cy="150" r="80" class="cls-3" opacity="0.2"/>
        <path class="cls-2" d="M200,100c-27.6,0-50,22.4-50,50s22.4,50,50,50,50-22.4,50-50S227.6,100,200,100Zm0,80c-16.5,0-30-13.5-30-30s13.5-30,30-30,30,13.5,30,30S216.5,180,200,180Z"/>
        <circle cx="200" cy="150" r="15" class="cls-4"/>
        
        <!-- "404" Text stylized -->
        <text x="50" y="250" font-family="Plus Jakarta Sans, sans-serif" font-weight="800" font-size="64" fill="#4338ca" opacity="0.1">4</text>
        <text x="310" y="250" font-family="Plus Jakarta Sans, sans-serif" font-weight="800" font-size="64" fill="#4338ca" opacity="0.1">4</text>
    </svg>
</div>
@endsection

@section('heading', __('404 - Halaman Tidak Ditemukan'))

@section('message')
    {{ __('Ops, sepertinya kamu tersesat. Halaman yang kamu cari mungkin telah dipindahkan atau tidak ada.') }}
@endsection

@section('action')
    <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all transform hover:scale-105">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        {{ __('Kembali ke Beranda') }}
    </a>
@endsection
