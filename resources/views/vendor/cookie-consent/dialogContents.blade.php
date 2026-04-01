<div class="js-cookie-consent cookie-consent fixed bottom-0 inset-x-0 p-4 z-50 transition-opacity duration-300">
    <div class="max-w-5xl mx-auto">
        <div class="rounded-xl border border-white/10 bg-zinc-900/95 backdrop-blur-md shadow-2xl shadow-black/40">
            <div class="p-5 md:p-6">
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <div class="flex-1">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-zinc-300 leading-relaxed">
                                    {!! trans('cookie-consent::texts.message') !!}
                                </p>
                                <a href="#" class="inline-block mt-2 text-xs text-emerald-400 hover:text-emerald-300 transition-colors underline underline-offset-2">
                                    {{ trans('cookie-consent::texts.learn_more') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 md:flex-shrink-0">
                        <button
                            class="js-cookie-consent-agree inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium bg-emerald-500 text-white hover:bg-emerald-400 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-zinc-900"
                        >
                            {{ trans('cookie-consent::texts.agree') }}
                        </button>
                        <button
                            class="js-cookie-consent-reject inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium bg-zinc-700 text-zinc-200 hover:bg-zinc-600 transition-colors focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 focus:ring-offset-zinc-900"
                        >
                            {{ trans('cookie-consent::texts.reject') }}
                        </button>
                        <button
                            data-cookie-consent-customize
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium border border-white/10 text-zinc-300 hover:bg-white/5 transition-colors focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 focus:ring-offset-zinc-900"
                        >
                            {{ trans('cookie-consent::texts.customize') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div
    id="cookie-preferences-modal"
    class="fixed inset-0 z-[60] hidden"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
>
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" data-cookie-consent-backdrop></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl border border-white/10 bg-zinc-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-white" id="modal-title">Cookie Preferences</h3>
                        <button
                            data-cookie-consent-close
                            class="text-zinc-400 hover:text-white transition-colors p-1 rounded-lg hover:bg-white/5"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <p class="text-sm text-zinc-400 mb-6">
                        Manage your cookie preferences below. Necessary cookies are always enabled for the site to function properly.
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 rounded-xl bg-zinc-800/50 border border-white/5">
                            <div class="flex-1 mr-4">
                                <h4 class="text-sm font-medium text-white flex items-center gap-2">
                                    Necessary
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-400">Required</span>
                                </h4>
                                <p class="text-xs text-zinc-400 mt-1">Required for the website to function properly.</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" checked disabled class="sr-only peer">
                                <div class="w-11 h-6 bg-emerald-500 rounded-full peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 rounded-xl bg-zinc-800/50 border border-white/5">
                            <div class="flex-1 mr-4">
                                <h4 class="text-sm font-medium text-white">Analytics</h4>
                                <p class="text-xs text-zinc-400 mt-1">Help us improve with anonymous usage statistics.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="cookie-analytics" class="sr-only peer">
                                <div class="w-11 h-6 bg-zinc-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 rounded-xl bg-zinc-800/50 border border-white/5">
                            <div class="flex-1 mr-4">
                                <h4 class="text-sm font-medium text-white">Marketing</h4>
                                <p class="text-xs text-zinc-400 mt-1">Personalized content and targeted advertising.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="cookie-marketing" class="sr-only peer">
                                <div class="w-11 h-6 bg-zinc-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button
                            data-cookie-consent-cancel
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium text-zinc-300 hover:text-white hover:bg-white/5 transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            data-cookie-consent-save
                            class="inline-flex items-center justify-center px-6 py-2 rounded-lg text-sm font-medium bg-emerald-500 text-white hover:bg-emerald-400 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-zinc-900"
                        >
                            Save Preferences
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
