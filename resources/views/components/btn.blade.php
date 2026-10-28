@props(['href' => null, 'variant' => 'primary', 'type' => 'button', 'size' => 'md'])
@php
    $base = 'inline-flex items-center justify-center gap-2 border border-transparent font-semibold transition-colors whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed ';
    $sizes = ['md' => 'h-[2.625rem] px-5 text-sm rounded-full', 'sm' => 'h-9 px-3.5 text-sm rounded-full'];
    $variants = [
        'primary' => 'bg-gray-900 dark:bg-gray-100 text-gray-50 dark:text-gray-900 hover:bg-indigo-600 dark:hover:bg-indigo-400',
        'accent' => 'bg-indigo-600 text-white hover:bg-indigo-700',
        'secondary' => '!border-gray-300 dark:!border-gray-600 text-gray-700 dark:text-gray-200 hover:!border-gray-900 dark:hover:!border-gray-300',
        'danger' => '!border-red-300 dark:!border-red-800 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30',
        'link' => 'text-indigo-600 dark:text-indigo-400 hover:underline underline-offset-4 !px-0 !py-0',
    ];
    $cls = $base . ($variant === 'link' ? '' : $sizes[$size] . ' ') . $variants[$variant];
@endphp
@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $cls]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $cls]) }}>{{ $slot }}</button>
@endif
