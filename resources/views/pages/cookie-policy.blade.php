<x-layouts::landing>
    <main class="relative">
        {{-- Animated Background Elements --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-40 left-10 w-72 h-72 bg-emerald-200 dark:bg-emerald-900/20 rounded-full blur-3xl animate-pulse-glow opacity-50"></div>
            <div class="absolute bottom-40 right-10 w-96 h-96 bg-emerald-300 dark:bg-emerald-800/20 rounded-full blur-3xl animate-pulse-glow opacity-50" style="animation-delay: 2s;"></div>
        </div>

        {{-- Hero Section --}}
        <section class="relative bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 py-24 lg:py-32 overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 48px 48px;"></div>
            </div>

            <div class="absolute top-20 left-1/4 w-3 h-3 bg-white rounded-full animate-float opacity-30"></div>
            <div class="absolute bottom-32 right-1/4 w-2 h-2 bg-emerald-200 rounded-full animate-float-reverse opacity-40" style="animation-delay: 1s;"></div>

            <div class="relative mx-auto max-w-4xl px-6 lg:px-8 text-center">
                <div class="animate-slide-up inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm text-white text-sm font-medium px-5 py-2.5 rounded-full mb-6 border border-white/20 shadow-lg">
                    <x-ui::icon name="shield-check" size="sm" />
                    Legal
                </div>
                <h1 class="animate-slide-up delay-100 text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    Cookie Policy
                </h1>
                <p class="animate-slide-up delay-200 text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto leading-relaxed">
                    Learn about how SeratyAI uses cookies and similar technologies to improve your experience.
                </p>
            </div>
        </section>

        {{-- Content Section --}}
        <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-4xl px-6 lg:px-8 prose prose-zinc dark:prose-invert max-w-none">
                {{-- What Are Cookies --}}
                <div class="mb-16">
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">What Are Cookies?</h2>
                    <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Cookies are small text files that are stored on your device when you visit a website. They help websites remember your preferences, understand how you interact with the site, and improve your overall experience. Cookies may be "session" cookies (deleted when you close your browser) or "persistent" cookies (remain until they expire or are deleted).
                    </p>
                </div>

                {{-- How We Use Cookies --}}
                <div class="mb-16">
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">How We Use Cookies</h2>
                    <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed mb-8">
                        SeratyAI uses cookies to deliver and maintain our services, enhance your experience, and analyze how our platform is used. Below is a breakdown of the cookie categories we use.
                    </p>

                    <div class="space-y-6">
                        @foreach(config('cookie-consent.categories') as $key => $category)
                        <div class="border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300">
                            <div class="flex items-center gap-3 mb-3">
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $category['name'] }}</h3>
                                @if(!$category['toggleable'])
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-400">Always Active</span>
                                @endif
                            </div>
                            <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                {{ $category['description'] }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Managing Cookies --}}
                <div class="mb-16">
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Managing Your Cookie Preferences</h2>
                    <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed mb-4">
                        You can manage your cookie preferences at any time by clicking the "Customize" button in the cookie consent banner or by clearing your browser cookies. When you revisit our site after clearing cookies, the consent banner will reappear, allowing you to set your preferences again.
                    </p>
                    <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed mb-4">
                        You can also control cookies through your browser settings. Most browsers allow you to:
                    </p>
                    <ul class="list-disc pl-6 text-base text-zinc-600 dark:text-zinc-400 leading-relaxed space-y-2">
                        <li>View what cookies are stored and delete them individually</li>
                        <li>Block third-party cookies</li>
                        <li>Block cookies from specific sites</li>
                        <li>Block all cookies from being set</li>
                        <li>Delete all cookies when you close your browser</li>
                    </ul>
                    <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed mt-4">
                        Please note that blocking certain cookies may impact your experience on our site and limit some functionality.
                    </p>
                </div>

                {{-- Third-Party Cookies --}}
                <div class="mb-16">
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Third-Party Cookies</h2>
                    <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        Some cookies on our site are set by third-party services that appear on our pages. We do not control these third-party cookies. Please refer to the respective third-party privacy policies for more information about their cookie practices.
                    </p>
                </div>

                {{-- Updates to Policy --}}
                <div class="mb-16">
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Updates to This Policy</h2>
                    <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        We may update this Cookie Policy from time to time to reflect changes in our practices or for other operational, legal, or regulatory reasons. We encourage you to review this page periodically for the latest information on our use of cookies.
                    </p>
                </div>

                {{-- Contact CTA --}}
                <div class="mt-20">
                    <x-ui::card class="relative overflow-hidden border-2 border-emerald-200 dark:border-emerald-800">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
                        <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-emerald-500/10 to-transparent rounded-full blur-3xl transform -translate-x-1/2 translate-y-1/2"></div>

                        <div class="relative p-8 lg:p-12 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-500/30">
                                <x-ui::icon name="mail" size="xl" class="text-white" />
                            </div>
                            <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-3">Questions About Cookies?</h3>
                            <p class="text-base text-zinc-600 dark:text-zinc-400 mb-8 max-w-md mx-auto">If you have any questions about our use of cookies, please don't hesitate to reach out.</p>
                            <x-ui::button variant="primary" href="/contact" icon="arrow-right" size="lg" class="shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                                Contact Us
                            </x-ui::button>
                        </div>
                    </x-ui::card>
                </div>
            </div>
        </section>
    </main>
</x-layouts::landing>
