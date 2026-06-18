import './ai-interviewer';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-lucide]').forEach(el => {
        el.remove();
    });
});

document.addEventListener('alpine:init', () => {
    Alpine.data('cvBuilderTabs', () => ({
        activeTab: 'personal',

        init() {
            this.activeTab = this.$el.dataset.activeSection || 'personal';
        },

        switchTab(tab) {
            if (this.activeTab === tab) return;
            this.activeTab = tab;
            // Bring the tab content into view so "Go to X" lands the user
            // on the relevant section rather than leaving them scrolled away.
            this.$nextTick(() => {
                this.$el?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        },
    }));
});
