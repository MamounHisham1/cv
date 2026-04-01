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
                    Terms of Service
                </h1>
                <p class="animate-slide-up delay-200 text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto leading-relaxed">
                    Please read these terms carefully before using SeratyAI.
                </p>
            </div>
        </section>

        {{-- Content Section --}}
        <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-4xl px-6 lg:px-8">
                <div class="space-y-12">
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Acceptance of Terms</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            By accessing or using SeratyAI, you agree to be bound by these Terms of Service. If you do not agree to these terms, you may not use our services. These terms apply to all visitors, users, and others who access or use the platform.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Use of Services</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed mb-4">
                            SeratyAI grants you a limited, non-exclusive, non-transferable license to use our platform for personal and professional CV building purposes. You agree not to:
                        </p>
                        <ul class="list-disc pl-6 text-base text-zinc-600 dark:text-zinc-400 leading-relaxed space-y-2">
                            <li>Use the service for any unlawful purpose or in violation of any applicable laws</li>
                            <li>Attempt to gain unauthorized access to any part of the platform or its systems</li>
                            <li>Interfere with or disrupt the service or servers connected to the platform</li>
                            <li>Reproduce, duplicate, copy, sell, or resell any portion of the service</li>
                            <li>Upload malicious code, viruses, or any harmful content</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">User Accounts</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. You must notify us immediately of any unauthorized use of your account. We reserve the right to suspend or terminate accounts that violate these terms.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Intellectual Property</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            The SeratyAI platform, including its design, features, logos, and underlying technology, is owned by us and protected by intellectual property laws. You retain ownership of the content you create on our platform, including your CV data. By using our AI features, you grant us a limited license to process your content solely for the purpose of providing the service.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Subscriptions and Payments</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Certain features of SeratyAI require a paid subscription. Subscription fees are billed in advance on a recurring basis. You may cancel your subscription at any time, and your access will continue until the end of the current billing period. Refunds are handled on a case-by-case basis at our discretion.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Disclaimer of Warranties</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            SeratyAI is provided on an "as is" and "as available" basis without any warranties of any kind, either express or implied. We do not guarantee that the service will be uninterrupted, secure, or error-free. The AI-generated content is provided as a suggestion tool and should be reviewed and verified by you before use.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Limitation of Liability</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            To the fullest extent permitted by law, SeratyAI shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including loss of profits, data, or business opportunities, arising from your use of the service.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-4">Changes to Terms</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            We reserve the right to modify these terms at any time. We will notify users of significant changes via email or through the platform. Continued use of the service after changes constitutes acceptance of the updated terms.
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
                            <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-3">Questions About Our Terms?</h3>
                            <p class="text-base text-zinc-600 dark:text-zinc-400 mb-8 max-w-md mx-auto">If you have any questions about these Terms of Service, please don't hesitate to reach out.</p>
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
