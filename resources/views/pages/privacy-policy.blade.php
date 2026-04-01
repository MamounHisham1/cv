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
                    Privacy Policy
                </h1>
                <p class="animate-slide-up delay-200 text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto leading-relaxed">
                    Your privacy matters to us. Learn how we collect, use, and protect your information.
                </p>
            </div>
        </section>

        {{-- Content Section --}}
        <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-4xl px-6 lg:px-8">
                <div class="space-y-12">
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Information We Collect</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed mb-4">
                            When you use SeratyAI, we collect the following types of information:
                        </p>
                        <ul class="list-disc pl-6 text-base text-zinc-600 dark:text-zinc-400 leading-relaxed space-y-2">
                            <li><strong class="text-zinc-900 dark:text-white">Account Information:</strong> Your name, email address, and authentication details when you create an account.</li>
                            <li><strong class="text-zinc-900 dark:text-white">CV Content:</strong> The resume data you enter, including work experience, education, skills, and personal details.</li>
                            <li><strong class="text-zinc-900 dark:text-white">Usage Data:</strong> Information about how you interact with our platform, including features used and actions taken.</li>
                            <li><strong class="text-zinc-900 dark:text-white">Device Information:</strong> Browser type, operating system, and IP address for security and analytics purposes.</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">How We Use Your Information</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed mb-4">
                            We use the information we collect to:
                        </p>
                        <ul class="list-disc pl-6 text-base text-zinc-600 dark:text-zinc-400 leading-relaxed space-y-2">
                            <li>Provide, maintain, and improve our services</li>
                            <li>Generate AI-powered suggestions and evaluations for your CV</li>
                            <li>Send you important account and service updates</li>
                            <li>Monitor and analyze usage trends to improve user experience</li>
                            <li>Detect, prevent, and address technical issues and security threats</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Data Security</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            We take the security of your data seriously. All personal information is encrypted at rest and in transit using industry-standard protocols. We implement appropriate technical and organizational measures to protect your data against unauthorized access, alteration, disclosure, or destruction.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Data Sharing</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            We do not sell, trade, or rent your personal information to third parties. We may share your data only in the following circumstances: with your explicit consent, to comply with legal obligations, to protect our rights and safety, or with trusted service providers who assist us in operating our platform under strict confidentiality agreements.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Your Rights</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed mb-4">
                            You have the right to:
                        </p>
                        <ul class="list-disc pl-6 text-base text-zinc-600 dark:text-zinc-400 leading-relaxed space-y-2">
                            <li>Access and download your personal data</li>
                            <li>Request correction of inaccurate information</li>
                            <li>Request deletion of your account and associated data</li>
                            <li>Opt out of non-essential communications</li>
                            <li>Withdraw consent at any time</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Data Retention</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            We retain your personal data for as long as your account is active or as needed to provide you services. If you delete your account, we will delete your personal information within 30 days, except where we are required to retain it for legal, regulatory, or security purposes.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Changes to This Policy</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            We may update this Privacy Policy from time to time. We will notify you of any significant changes by posting the new policy on this page and updating the effective date. We encourage you to review this policy periodically.
                        </p>
                    </div>
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
                            <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-3">Questions About Privacy?</h3>
                            <p class="text-base text-zinc-600 dark:text-zinc-400 mb-8 max-w-md mx-auto">If you have any questions about this Privacy Policy, please don't hesitate to reach out.</p>
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
