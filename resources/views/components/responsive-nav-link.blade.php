@props(['active'])

@php
$classes = ($active ?? false)
            ? 'd-block w-100 ps-3 pe-4 py-2 border-l-4 border-indigo-400 dark:border-indigo-600 text-start text-base fw-medium text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 focus:outline-none focus:text-indigo-800 dark:focus:text-indigo-200 focus:bg-indigo-100 dark:focus:bg-indigo-900 focus:border-indigo-700 dark:focus:border-indigo-300 transition duration-150 ease-in-out'
            : 'd-block w-100 ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base fw-medium text-gray-600 dark:text-muted hover:text-gray-800 dark:hover:text-gray-200 hover:bg-light dark:hover:bg-light hover:border-secondary dark:hover:border-secondary focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-light dark:focus:bg-light focus:border-secondary dark:focus:border-secondary transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
