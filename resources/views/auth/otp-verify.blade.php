<x-layouts::auth :title="__('Verify Email')">
    <livewire:otp-verification :email="auth()->user()->email" />
</x-layouts::auth>
