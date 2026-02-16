@extends('errors::illustrated-layout')

@section('title', __('Terjadi Kesalahan'))

@section('image')
<div class="w-full max-w-sm">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" class="w-full h-auto text-red-500">
        <!-- Simplified Server Error Concept -->
        <rect x="100" y="50" width="200" height="200" rx="20" fill="#fee2e2"/>
        <circle cx="150" cy="100" r="10" fill="#ef4444"/>
        <circle cx="150" cy="150" r="10" fill="#ef4444"/>
        <circle cx="150" cy="200" r="10" fill="#ef4444"/>
        
        <!-- Warning Sign -->
        <path d="M280,220 L320,290 L240,290 Z" fill="#f87171"/>
        <rect x="278" y="245" width="4" height="25" fill="#fff"/>
        <circle cx="280" cy="280" r="3" fill="#fff"/>
        
        <!-- Connection Lines -->
        <path d="M50,150 L100,150" stroke="#fca5a5" stroke-width="4" stroke-dasharray="10,10"/>
        <path d="M300,150 L350,150" stroke="#fca5a5" stroke-width="4" stroke-dasharray="10,10"/>
    </svg>
</div>
@endsection

@section('heading', __('500 - Terjadi Kesalahan Teknis'))

@section('message')
    {{ __('Mohon maaf, server kami sedang mengalami gangguan. Silakan coba muat ulang halaman atau hubungi kami jika masalah berlanjut.') }}
@endsection

@section('action')
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <button onclick="window.location.reload()" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-xl shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            {{ __('Muat Ulang') }}
        </button>
        
        <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
            {{ __('Kembali ke Beranda') }}
        </a>
    </div>
@endsection
