@props(['percentage' => 0, 'size' => 120, 'width' => 12])

@php
    $radius = ($size - $width) / 2;
    $circumference = 2 * pi() * $radius;
    $offset = $circumference - ($percentage / 100) * $circumference;
@endphp

<div class="relative d-flex align-items-center justify-content-center" style="width: {{ $size }}px; height: {{ $size }}px;">
    <!-- Background Circle -->
    <svg class="transform -rotate-90 w-100 h-100">
        <circle
            cx="{{ $size / 2 }}"
            cy="{{ $size / 2 }}"
            r="{{ $radius }}"
            stroke="currentColor"
            stroke-width="{{ $width }}"
            fill="transparent"
            class="text-gray-100 dark:text-gray-700"
        />
        
        <!-- Progress Circle -->
        <circle
            cx="{{ $size / 2 }}"
            cy="{{ $size / 2 }}"
            r="{{ $radius }}"
            stroke="currentColor"
            stroke-width="{{ $width }}"
            fill="transparent"
            stroke-dasharray="{{ $circumference }}"
            stroke-dashoffset="{{ $offset }}"
            stroke-linecap="round"
            class="text-teal-500 transition-all duration-1000 ease-out"
        />
    </svg>
    
    <!-- Text Content -->
    <div class="absolute d-flex d-flex-column align-items-center justify-content-center text-center">
        <span class="text-3xl font-extrabold text-gray-800 dark:text-gray-200" :class="$store.privacy.on ? 'blur-md' : ''">{{ $percentage }}%</span>
        <span class="text-[10px] text-uppercase fw-bold text-muted">Lunas</span>
    </div>
</div>
