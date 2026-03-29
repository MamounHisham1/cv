document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-lucide]').forEach(el => {
        el.remove();
    });
});
