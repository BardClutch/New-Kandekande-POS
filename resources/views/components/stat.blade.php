@props(['label', 'value', 'sub' => null, 'tone' => null])
<div class="p-5 sm:p-6">
    <dt class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</dt>
    <dd class="mt-2 font-display text-2xl sm:text-3xl font-extrabold tabular-nums {{ $tone === 'bad' ? 'text-red-600 dark:text-red-400' : ($tone === 'good' ? 'text-emerald-600 dark:text-emerald-400' : ($tone === 'accent' ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-900 dark:text-gray-50')) }}">{{ $value }}</dd>
    @if($sub)<p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $sub }}</p>@endif
</div>
