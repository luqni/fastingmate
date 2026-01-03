@props(['active'])

@php
$classes = ($active ?? false)
            ? 'd-inline-d-flex align-items-center px-1 pt-1 border-b-2 border-indigo-400 dark:border-indigo-600 small fw-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'd-inline-d-flex align-items-center px-1 pt-1 border-b-2 border-transparent small fw-medium leading-5 text-secondary dark:text-muted hover:text-gray-700 dark:hover:text-gray-300 hover:border-secondary dark:hover:border-secondary focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-secondary dark:focus:border-secondary transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
