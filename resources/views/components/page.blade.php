@props(['title', 'sub' => null, 'narrow' => false])
<div class="py-8 sm:py-10">
    <div class="w-full px-4 sm:px-6 lg:px-8 space-y-8 {{ $narrow ? 'max-w-4xl' : '' }}">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div class="min-w-0">
                <h1 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight text-gray-900 dark:text-gray-50">{{ $title }}</h1>
                @if($sub)<p class="mt-1.5 text-gray-500 dark:text-gray-400 max-w-2xl">{{ $sub }}</p>@endif
            </div>
            @isset($actions)
                <div class="flex flex-wrap items-center gap-2 flex-shrink-0">{{ $actions }}</div>
            @endisset
        </div>

        <x-flash />

        {{ $slot }}
    </div>
</div>
