@php
    $info = $letter->cv?->personal_info ?? [];
    $name = trim(($info['first_name'] ?? '').' '.($info['last_name'] ?? '')) ?: $letter->title;
    $email = $info['email'] ?? null;
    $phone = $info['phone'] ?? null;
    $location = $info['location'] ?? null;
    $today = now()->format('F j, Y');
@endphp
<div class="cv-export-content p-12 font-serif text-gray-900" style="font-size: 12pt; line-height: 1.6;">
    <header class="mb-6">
        <h1 class="text-2xl font-bold">{{ $name }}</h1>
        @if($email || $phone || $location)
        <p class="mt-1 text-sm text-gray-600">
            @foreach(array_filter([$email, $phone, $location]) as $i => $line)
                @if($i > 0) <span class="mx-2">·</span> @endif
                {{ $line }}
            @endforeach
        </p>
        @endif
    </header>

    <p class="mb-4">{{ $today }}</p>

    <div class="space-y-4 whitespace-pre-wrap">{!! nl2br(e($letter->body)) !!}</div>

    @if($name)
    <div class="mt-8">
        <p>Sincerely,</p>
        <p class="mt-3 font-bold">{{ $name }}</p>
    </div>
    @endif
</div>
