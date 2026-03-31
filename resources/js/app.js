// Alpine.js is bundled with Livewire v4 — no manual import needed.
// The previous manual Alpine import/start was creating a second instance
// that conflicted with Livewire's built-in Alpine, breaking file uploads.

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-lucide]').forEach(el => {
        el.remove();
    });
});
