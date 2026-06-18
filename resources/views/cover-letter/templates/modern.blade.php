@php
    $info = $letter->cv?->personal_info ?? [];
    $name = trim(($info['first_name'] ?? '').' '.($info['last_name'] ?? '')) ?: $letter->title;
    $email = $info['email'] ?? null;
    $phone = $info['phone'] ?? null;
    $location = $info['location'] ?? null;
    $today = now()->format('F j, Y');
@endphp
<div class="cv-export-content p-12 text-gray-900" style="font-family: 'Instrument Sans', system-ui, sans-serif; font-size: 11.5pt; line-height: 1.7;">
    <header class="mb-6 border-b-2 border-emerald-600 pb-3">
        <h1 class="text-2xl font-semibold tracking-tight">{{ $name }}</h1>
        @if($email || $phone || $location)
        <p class="mt-1 text-sm text-gray-500">
            @foreach(array_filter([$email, $phone, $location]) as $i => $line)
                @if($i > 0) <span class="mx-2">·</span> @endif
                {{ $line }}
            @endforeach
        </p>
        @endif
    </header>

    <p class="mb-4 text-gray-500">{{ $today }}</p>

    <div class="space-y-4 whitespace-pre-wrap">{!! nl2br(e($letter->body)) !!}</div>

    @if($name)
    <div class="mt-8">
        <p>Warm regards,</p>
        <p class="mt-3 font-semibold text-emerald-700">{{ $name }}</p>
    </div>
    @endif
</div>
