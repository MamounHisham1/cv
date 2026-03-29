@props([
    'length' => 6,
    'name' => null,
    'label' => null,
    'labelSrOnly' => false,
])

@php
$fields = range(0, $length - 1);
$inputClasses = 'h-12 w-12 rounded-lg border border-input bg-background text-center text-lg font-semibold transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-1 disabled:cursor-not-allowed disabled:opacity-50';
@endphp

@if($label)
    <label
        for="otp-field-0"
        class="text-sm font-medium leading-none mb-1.5 block @if($labelSrOnly) sr-only @endif"
    >
        {{ $label }}
    </label>
@endif

<div
    x-data="{
        length: {{ $length }},
        fields: new Array({{ $length }}).fill(''),
        init() {
            this.$el.addEventListener('otp-set', (event) => {
                const value = event.detail.value || '';
                for (let i = 0; i < this.length; i++) {
                    this.fields[i] = value[i] || '';
                }
            });
        },
        focus(index) {
            const input = this.$el.querySelectorAll('input')[index];
            if (input) input.focus();
        },
        onInput(index, event) {
            const value = event.target.value.replace(/[^0-9]/g, '');
            this.fields[index] = value.slice(-1);
            event.target.value = this.fields[index];
            if (this.fields[index] && index < this.length - 1) {
                this.focus(index + 1);
            }
            this.emitValue();
        },
        onKeydown(index, event) {
            if (event.key === 'Backspace' && !this.fields[index] && index > 0) {
                this.focus(index - 1);
            }
        },
        onPaste(index, event) {
            event.preventDefault();
            const paste = (event.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
            for (let i = 0; i < paste.length && index + i < this.length; i++) {
                this.fields[index + i] = paste[i];
            }
            const nextEmpty = index + paste.length;
            if (nextEmpty < this.length) {
                this.focus(nextEmpty);
            }
            this.emitValue();
        },
        emitValue() {
            const value = this.fields.join('');
            this.$dispatch('input', value);
            this.$dispatch('change', value);
        }
    }"
    class="flex items-center gap-2"
    {{ $attributes->merge(['class' => '']) }}
>
    @foreach($fields as $i)
        <input
            type="text"
            inputmode="numeric"
            maxlength="1"
            autocomplete="one-time-code"
            x-model="fields[{{ $i }}]"
            x-on:input="onInput({{ $i }}, $event)"
            x-on:keydown="onKeydown({{ $i }}, $event)"
            x-on:paste="onPaste({{ $i }}, $event)"
            class="{{ $inputClasses }}"
            @if($name) name="{{ $name }}[]" @endif
        />
    @endforeach
</div>
