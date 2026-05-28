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
        },
    }));
});
