@extends('errors::illustrated-layout')

@section('title', __('Akses Ditolak'))

@section('image')
<div class="w-full max-w-sm">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" class="w-full h-auto text-yellow-500">
        <!-- Shield/Lock Concept -->
        <path d="M200,40 C120,50 60,100 60,180 C60,240 120,280 200,290 C280,280 340,240 340,180 C340,100 280,50 200,40 Z" fill="#fef3c7"/>
        <path d="M200,50 C270,60 320,105 320,180 C320,230 270,265 200,275 C130,265 80,230 80,180 C80,105 130,60 200,50 Z" fill="#fffbeb"/>
        
        <!-- Lock Icon -->
        <rect x="160" y="160" width="80" height="70" rx="10" fill="#f59e0b"/>
        <path d="M175,160 L175,140 A25,25 0 0,1 225,140 L225,160" stroke="#f59e0b" stroke-width="8" fill="none"/>
        <circle cx="200" cy="195" r="8" fill="#fff"/>
        
        <!-- Cross Mark -->
        <path d="M280,100 L320,140 M320,100 L280,140" stroke="#ef4444" stroke-width="8" stroke-linecap="round"/>
    </svg>
</div>
@endsection

@section('heading', __('403 - Akses Dilarang'))

@section('message')
    {{ __('Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Silahkan hubungi administrator jika Anda merasa ini adalah kesalahan.') }}
@endsection

@section('action')
    <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all transform hover:scale-105">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        {{ __('Kembali ke Beranda') }}
    </a>
@endsection
