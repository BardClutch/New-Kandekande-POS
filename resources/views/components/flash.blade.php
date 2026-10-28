@if(session('success'))
    <div class="rounded-lg border border-emerald-300 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/30 px-4 py-3 text-sm font-medium text-emerald-800 dark:text-emerald-300" role="status">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="rounded-lg border border-red-300 dark:border-red-800 bg-red-50 dark:bg-red-950/30 px-4 py-3 text-sm font-medium text-red-800 dark:text-red-300" role="alert">{{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="rounded-lg border border-red-300 dark:border-red-800 bg-red-50 dark:bg-red-950/30 px-4 py-3 text-sm text-red-800 dark:text-red-300" role="alert">
        <p class="font-semibold">Periksa kembali isian Anda.</p>
        <ul class="mt-1 list-disc pl-5 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif
