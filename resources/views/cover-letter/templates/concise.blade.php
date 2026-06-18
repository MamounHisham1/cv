@php
    $info = $letter->cv?->personal_info ?? [];
    $name = trim(($info['first_name'] ?? '').' '.($info['last_name'] ?? '')) ?: $letter->title;
    $email = $info['email'] ?? null;
    $phone = $info['phone'] ?? null;
    $today = now()->format('F j, Y');
@endphp
<div class="cv-export-content p-12 text-gray-900" style="font-family: 'Instrument Sans', system-ui, sans-serif; font-size: 11.5pt; line-height: 1.65;">
    <header class="mb-5">
        <h1 class="text-xl font-bold">{{ $name }}</h1>
        @if($email || $phone)
        <p class="text-sm text-gray-500">{{ implode(' · ', array_filter([$email, $phone])) }}</p>
        @endif
    </header>

    <p class="mb-3 text-sm text-gray-500">{{ $today }}</p>

    <div class="space-y-3 whitespace-pre-wrap" style="max-width: 16cm;">{!! nl2br(e($letter->body)) !!}</div>

    @if($name)
    <div class="mt-6">
        <p>Best,</p>
        <p class="mt-2 font-bold">{{ $name }}</p>
    </div>
    @endif
</div>
