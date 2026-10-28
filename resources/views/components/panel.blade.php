@props(['title' => null, 'sub' => null, 'flush' => false])
<section {{ $attributes->merge(['class' => 'rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800']) }}>
    @if($title || isset($actions))
        <div class="flex flex-wrap items-baseline justify-between gap-3 px-5 sm:px-6 pt-5 sm:pt-6 {{ $flush ? 'pb-4' : 'pb-1' }}">
            <div class="min-w-0">
                @if($title)<h2 class="font-display text-xl font-extrabold tracking-tight text-gray-900 dark:text-gray-50">{{ $title }}</h2>@endif
                @if($sub)<p class="text-sm text-gray-500 dark:text-gray-400">{{ $sub }}</p>@endif
            </div>
            @isset($actions)<div class="flex items-center gap-2">{{ $actions }}</div>@endisset
        </div>
    @endif
    <div class="{{ $flush ? '' : 'p-5 sm:p-6' }}">{{ $slot }}</div>
</section>
