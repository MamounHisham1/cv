import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('otpInput', (livewire) => ({
    length: 6,
    fields: ['', '', '', '', '', ''],

    init() {
        // Sync with Livewire on changes
        this.$watch('fields', () => {
            if (livewire) {
                livewire.otp = this.fields.join('');
            }
        });
    },

    focus(index) {
        const input = this.$refs[`input${index}`];
        if (input) {
            input.focus();
            input.select();
        }
    },

    onInput(index, event) {
        const value = event.target.value.replace(/[^0-9]/g, '');
        const newValue = value.slice(-1);
        this.fields[index] = newValue;

        if (newValue && index < this.length - 1) {
            this.$nextTick(() => this.focus(index + 1));
        }
    },

    onKeydown(index, event) {
        if (event.key === 'Backspace') {
            if (!this.fields[index] && index > 0) {
                event.preventDefault();
                this.fields[index - 1] = '';
                this.$nextTick(() => this.focus(index - 1));
            }
        } else if (event.key === 'ArrowLeft' && index > 0) {
            event.preventDefault();
            this.focus(index - 1);
        } else if (event.key === 'ArrowRight' && index < this.length - 1) {
            event.preventDefault();
            this.focus(index + 1);
        }
    },

    onPaste(index, event) {
        event.preventDefault();
        const paste = (event.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');

        if (!paste) return;

        // Fill fields starting from current index
        for (let i = 0; i < paste.length && index + i < this.length; i++) {
            this.fields[index + i] = paste[i];
        }

        // Focus the last filled input or the last input
        const lastFilledIndex = Math.min(index + paste.length - 1, this.length - 1);
        this.$nextTick(() => this.focus(lastFilledIndex));
    },
}));

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-lucide]').forEach(el => {
        el.remove();
    });
});
