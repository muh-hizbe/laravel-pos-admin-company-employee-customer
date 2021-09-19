@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center w-full px-3 py-2 rounded-full shadow-md text-sm font-semibold leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 whitespace-nowrap transition duration-150 ease-in-out'
            : 'inline-flex items-center w-full px-3 py-2 rounded-full text-sm font-semibold leading-5 text-gray-500 hover:shadow-md focus:outline-none focus:text-gray-700 focus:border-gray-300 whitespace-nowrap transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
