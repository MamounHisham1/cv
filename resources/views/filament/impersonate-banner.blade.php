@php
    $impersonateService = app(\App\Services\ImpersonateService::class);
@endphp

@if($impersonateService->isImpersonating())
    <div class="fixed top-0 left-0 right-0 z-[100] bg-amber-600 px-4 py-3 text-center text-sm font-medium text-white shadow-lg">
        You are impersonating <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})
        <form method="POST" action="{{ route('impersonate.stop') }}" class="inline ml-3">
            @csrf
            <button type="submit" class="rounded bg-white/20 px-4 py-1 text-sm font-semibold text-white hover:bg-white/30 transition-colors">
                Stop Impersonating
            </button>
        </form>
    </div>
@endif
