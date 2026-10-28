@props(['show', 'title', 'wide' => false])
<div x-show="{{ $show }}" x-cloak style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="{{ $show }} = false" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" @click="{{ $show }} = false"></div>
    <div class="relative w-full {{ $wide ? 'max-w-2xl' : 'max-w-md' }} max-h-[90vh] overflow-y-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 sm:p-7 shadow-2xl">
        <h2 class="font-display text-2xl font-extrabold tracking-tight text-gray-900 dark:text-gray-50">{{ $title }}</h2>
        <div class="mt-5">{{ $slot }}</div>
    </div>
</div>
