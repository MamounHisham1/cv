<x-layouts::landing>
    <main class="relative">
        {{-- Animated Background Elements --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-40 left-10 w-72 h-72 bg-emerald-200 dark:bg-emerald-900/20 rounded-full blur-3xl animate-pulse-glow opacity-50"></div>
            <div class="absolute bottom-40 right-10 w-96 h-96 bg-emerald-300 dark:bg-emerald-800/20 rounded-full blur-3xl animate-pulse-glow opacity-50" style="animation-delay: 2s;"></div>
        </div>

        {{-- Hero Section with Modern Design --}}
        <section class="relative bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 py-24 lg:py-32 overflow-hidden">
            {{-- Geometric Pattern Overlay --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 48px 48px;"></div>
            </div>

            {{-- Floating Elements --}}
            <div class="absolute top-20 left-1/4 w-3 h-3 bg-white rounded-full animate-float opacity-30"></div>
            <div class="absolute bottom-32 right-1/4 w-2 h-2 bg-emerald-200 rounded-full animate-float-reverse opacity-40" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/3 right-10 w-4 h-4 bg-emerald-300 rounded-full animate-float-slow opacity-25" style="animation-delay: 0.5s;"></div>

            <div class="relative mx-auto max-w-4xl px-6 lg:px-8 text-center">
                <div class="animate-slide-up inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm text-white text-sm font-medium px-5 py-2.5 rounded-full mb-6 border border-white/20 shadow-lg">
                    <x-ui::icon name="sparkles" size="sm" />
                    Support Center
                </div>
                <h1 class="animate-slide-up delay-100 text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    Frequently Asked Questions
                </h1>
                <p class="animate-slide-up delay-200 text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto leading-relaxed">
                    Find answers to the most common questions about SeratyAI. Can't find what you need? Our support team is here to help.
                </p>
            </div>
        </section>

        {{-- FAQ Content with Modern Accordion --}}
        <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-4xl px-6 lg:px-8">
                {{-- General Questions --}}
                <div class="mb-16" x-data="{ open: null }">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                            <x-ui::icon name="globe" size="lg" class="text-white" />
                        </div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">General Questions</h2>
                    </div>

                    <div class="space-y-4">
                        @foreach([
                            ['id' => 'g1', 'question' => 'What is SeratyAI?', 'answer' => 'SeratyAI is an AI-powered CV builder that helps you create professional, ATS-optimized resumes. Our platform analyzes job descriptions, suggests improvements to your content, and provides real-time compatibility scoring to maximize your chances of getting past automated screening systems.'],
                            ['id' => 'g2', 'question' => 'Who is SeratyAI for?', 'answer' => 'SeratyAI is designed for anyone looking to create a professional resume, from recent graduates entering the job market to experienced professionals making career transitions. Whether you are applying for your first role or your twentieth, our AI adapts to your experience level and industry.'],
                            ['id' => 'g3', 'question' => 'Is my data secure?', 'answer' => 'Absolutely. We take data privacy seriously. All personal information is encrypted at rest and in transit using industry-standard protocols. We never sell or share your data with third parties. SeratyAI is fully compliant with GDPR and SOC 2 standards.'],
                            ['id' => 'g4', 'question' => 'Can I use SeratyAI on mobile devices?', 'answer' => 'Yes. SeratyAI is fully responsive and works on any device with a modern web browser, including smartphones and tablets. You can start building your CV on your laptop and continue editing on your phone seamlessly.']
                        ] as $faq)
                        <div class="group border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/10">
                            <button
                                @click="open = open === '{{ $faq['id'] }}' ? null : '{{ $faq['id'] }}'"
                                class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-zinc-50 dark:hover:bg-zinc-900/50 transition-all duration-200"
                            >
                                <span class="text-base font-semibold text-zinc-900 dark:text-white pr-4">{{ $faq['question'] }}</span>
                                <div class="relative w-8 h-8 shrink-0">
                                    <div class="absolute inset-0 flex items-center justify-center transition-all duration-300" :class="open === '{{ $faq['id'] }}' ? 'rotate-180 opacity-0' : 'rotate-0 opacity-100'">
                                        <x-ui::icon name="chevron-down" size="md" class="text-zinc-400" />
                                    </div>
                                    <div class="absolute inset-0 flex items-center justify-center transition-all duration-300" :class="open === '{{ $faq['id'] }}' ? 'rotate-0 opacity-100' : '-rotate-180 opacity-0'">
                                        <x-ui::icon name="chevron-up" size="md" class="text-emerald-600 dark:text-emerald-400" />
                                    </div>
                                </div>
                            </button>
                            <div x-show="open === '{{ $faq['id'] }}'" x-collapse x-cloak>
                                <div class="px-6 pb-6 pt-2 text-base text-zinc-600 dark:text-zinc-400 leading-relaxed bg-gradient-to-r from-zinc-50/50 to-transparent dark:from-zinc-900/50">
                                    {{ $faq['answer'] }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Features Questions --}}
                <div class="mb-16" x-data="{ open: null }">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                            <x-ui::icon name="sparkles" size="lg" class="text-white" />
                        </div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Features & Capabilities</h2>
                    </div>

                    <div class="space-y-4">
                        @foreach([
                            ['id' => 'f1', 'question' => 'How does the AI writing assistant work?', 'answer' => 'Our AI analyzes your work experience, the target job description, and current hiring trends to generate optimized bullet points and summaries. It suggests stronger action verbs, quantifies achievements, and ensures your content aligns with what recruiters in your industry are looking for.'],
                            ['id' => 'f2', 'question' => 'What does ATS optimization mean?', 'answer' => 'ATS stands for Applicant Tracking System. Over 98% of large companies use ATS software to filter resumes before a human ever sees them. Our optimization engine scans your CV against a job description, checks for required keywords, proper formatting, and common ATS pitfalls, then gives you a compatibility score with actionable improvement suggestions.'],
                            ['id' => 'f3', 'question' => 'What file formats can I export my CV in?', 'answer' => 'You can export your CV as a high-quality PDF, which is the industry standard for job applications. PDF files preserve your formatting exactly as designed and are universally accepted by ATS systems and hiring managers.'],
                            ['id' => 'f4', 'question' => 'Can I create multiple CVs for different job applications?', 'answer' => 'Yes. Pro and Enterprise users can create unlimited CVs and tailor each one to specific job applications. This is highly recommended, as customizing your CV for each role significantly increases your chances of passing ATS screening and landing interviews.']
                        ] as $faq)
                        <div class="group border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/10">
                            <button
                                @click="open = open === '{{ $faq['id'] }}' ? null : '{{ $faq['id'] }}'"
                                class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-zinc-50 dark:hover:bg-zinc-900/50 transition-all duration-200"
                            >
                                <span class="text-base font-semibold text-zinc-900 dark:text-white pr-4">{{ $faq['question'] }}</span>
                                <div class="relative w-8 h-8 shrink-0">
                                    <div class="absolute inset-0 flex items-center justify-center transition-all duration-300" :class="open === '{{ $faq['id'] }}' ? 'rotate-180 opacity-0' : 'rotate-0 opacity-100'">
                                        <x-ui::icon name="chevron-down" size="md" class="text-zinc-400" />
                                    </div>
                                    <div class="absolute inset-0 flex items-center justify-center transition-all duration-300" :class="open === '{{ $faq['id'] }}' ? 'rotate-0 opacity-100' : '-rotate-180 opacity-0'">
                                        <x-ui::icon name="chevron-up" size="md" class="text-emerald-600 dark:text-emerald-400" />
                                    </div>
                                </div>
                            </button>
                            <div x-show="open === '{{ $faq['id'] }}'" x-collapse x-cloak>
                                <div class="px-6 pb-6 pt-2 text-base text-zinc-600 dark:text-zinc-400 leading-relaxed bg-gradient-to-r from-zinc-50/50 to-transparent dark:from-zinc-900/50">
                                    {{ $faq['answer'] }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Pricing Questions --}}
                <div class="mb-16" x-data="{ open: null }">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                            <x-ui::icon name="briefcase" size="lg" class="text-white" />
                        </div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Pricing & Subscriptions</h2>
                    </div>

                    <div class="space-y-4">
                        @foreach([
                            ['id' => 'p1', 'question' => 'Is there a free trial for the Pro plan?', 'answer' => 'Yes. Every new account starts with a 7-day free trial of the Pro plan. You get full access to all Pro features, including unlimited CVs, all templates, and unlimited AI suggestions. No credit card is required to start your trial.'],
                            ['id' => 'p2', 'question' => 'Can I cancel my subscription at any time?', 'answer' => 'Absolutely. You can cancel your subscription at any time from your account settings. There are no cancellation fees or long-term commitments. If you cancel, you will retain access to Pro features until the end of your current billing period.'],
                            ['id' => 'p3', 'question' => 'Do you offer discounts for annual billing?', 'answer' => 'Yes. When you choose annual billing, you save 20% compared to the monthly price. That brings the Pro plan down to $7.20/month and the Enterprise plan to $23.20/month. The discount is applied automatically when you select the annual option.']
                        ] as $faq)
                        <div class="group border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/10">
                            <button
                                @click="open = open === '{{ $faq['id'] }}' ? null : '{{ $faq['id'] }}'"
                                class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-zinc-50 dark:hover:bg-zinc-900/50 transition-all duration-200"
                            >
                                <span class="text-base font-semibold text-zinc-900 dark:text-white pr-4">{{ $faq['question'] }}</span>
                                <div class="relative w-8 h-8 shrink-0">
                                    <div class="absolute inset-0 flex items-center justify-center transition-all duration-300" :class="open === '{{ $faq['id'] }}' ? 'rotate-180 opacity-0' : 'rotate-0 opacity-100'">
                                        <x-ui::icon name="chevron-down" size="md" class="text-zinc-400" />
                                    </div>
                                    <div class="absolute inset-0 flex items-center justify-center transition-all duration-300" :class="open === '{{ $faq['id'] }}' ? 'rotate-0 opacity-100' : '-rotate-180 opacity-0'">
                                        <x-ui::icon name="chevron-up" size="md" class="text-emerald-600 dark:text-emerald-400" />
                                    </div>
                                </div>
                            </button>
                            <div x-show="open === '{{ $faq['id'] }}'" x-collapse x-cloak>
                                <div class="px-6 pb-6 pt-2 text-base text-zinc-600 dark:text-zinc-400 leading-relaxed bg-gradient-to-r from-zinc-50/50 to-transparent dark:from-zinc-900/50">
                                    {{ $faq['answer'] }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Technical Questions --}}
                <div class="mb-16" x-data="{ open: null }">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                            <x-ui::icon name="code" size="lg" class="text-white" />
                        </div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Technical Support</h2>
                    </div>

                    <div class="space-y-4">
                        @foreach([
                            ['id' => 't1', 'question' => 'Which browsers are supported?', 'answer' => 'SeratyAI supports all modern browsers including Chrome, Firefox, Safari, and Edge. We recommend using the latest version of your preferred browser for the best experience. Internet Explorer is not supported.'],
                            ['id' => 't2', 'question' => 'Does SeratyAI offer an API?', 'answer' => 'Yes, API access is available on the Enterprise plan. Our RESTful API allows you to integrate SeratyAI functionality into your existing HR tools, job boards, or career platforms. Full documentation and SDK support are provided upon request.'],
                            ['id' => 't3', 'question' => 'How do I report a bug or request a feature?', 'answer' => 'You can report bugs or request features through our contact page, by emailing support@seratyai.com, or by using the in-app feedback button. Our team reviews all submissions and aims to respond within 24 hours. Many of our most popular features started as user suggestions.']
                        ] as $faq)
                        <div class="group border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/10">
                            <button
                                @click="open = open === '{{ $faq['id'] }}' ? null : '{{ $faq['id'] }}'"
                                class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-zinc-50 dark:hover:bg-zinc-900/50 transition-all duration-200"
                            >
                                <span class="text-base font-semibold text-zinc-900 dark:text-white pr-4">{{ $faq['question'] }}</span>
                                <div class="relative w-8 h-8 shrink-0">
                                    <div class="absolute inset-0 flex items-center justify-center transition-all duration-300" :class="open === '{{ $faq['id'] }}' ? 'rotate-180 opacity-0' : 'rotate-0 opacity-100'">
                                        <x-ui::icon name="chevron-down" size="md" class="text-zinc-400" />
                                    </div>
                                    <div class="absolute inset-0 flex items-center justify-center transition-all duration-300" :class="open === '{{ $faq['id'] }}' ? 'rotate-0 opacity-100' : '-rotate-180 opacity-0'">
                                        <x-ui::icon name="chevron-up" size="md" class="text-emerald-600 dark:text-emerald-400" />
                                    </div>
                                </div>
                            </button>
                            <div x-show="open === '{{ $faq['id'] }}'" x-collapse x-cloak>
                                <div class="px-6 pb-6 pt-2 text-base text-zinc-600 dark:text-zinc-400 leading-relaxed bg-gradient-to-r from-zinc-50/50 to-transparent dark:from-zinc-900/50">
                                    {{ $faq['answer'] }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Contact Support CTA --}}
                <div class="mt-20">
                    <x-ui::card class="relative overflow-hidden border-2 border-emerald-200 dark:border-emerald-800">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
                        <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-emerald-500/10 to-transparent rounded-full blur-3xl transform -translate-x-1/2 translate-y-1/2"></div>

                        <div class="relative p-8 lg:p-12 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-500/30 animate-pulse">
                                <x-ui::icon name="mail" size="xl" class="text-white" />
                            </div>
                            <h3 class="text-2xl font-bold text-zinc-900 dark:text-white mb-3">Still Have Questions?</h3>
                            <p class="text-base text-zinc-600 dark:text-zinc-400 mb-8 max-w-md mx-auto">Our support team is ready to help you with anything you need. We typically respond within 24 hours.</p>
                            <x-ui::button variant="primary" href="/contact" icon="arrow-right" size="lg" class="shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                                Contact Support
                            </x-ui::button>
                        </div>
                    </x-ui::card>
                </div>
            </div>
        </section>
    </main>
</x-layouts::landing>
