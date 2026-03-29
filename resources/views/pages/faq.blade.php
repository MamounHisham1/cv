<x-layouts::landing>
    <main>
        {{-- Hero --}}
        <section class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 py-20 lg:py-28">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">Frequently Asked Questions</h1>
                <p class="text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto leading-relaxed">
                    Find answers to the most common questions about CVForge. Can't find what you need? Reach out to our support team.
                </p>
            </div>
        </section>

        <section class="py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-3xl px-6 lg:px-8">
                {{-- General --}}
                <div x-data="{ open: null }" class="mb-12">
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-6 flex items-center gap-3">
                        <flux:icon name="globe" class="size-6 text-emerald-600 dark:text-emerald-400" variant="solid" />
                        General
                    </h2>
                    <div class="space-y-3">
                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                            <button
                                @click="open = open === 'g1' ? null : 'g1'"
                                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors"
                            >
                                <span class="text-sm font-medium text-zinc-900 dark:text-white pr-4">What is CVForge?</span>
                                <flux:icon :name="open === 'g1' ? 'chevron-up' : 'chevron-down'" class="size-5 text-zinc-500 dark:text-zinc-400 shrink-0" />
                            </button>
                            <div x-show="open === 'g1'" x-collapse x-cloak>
                                <div class="px-6 pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    CVForge is an AI-powered CV builder that helps you create professional, ATS-optimized resumes. Our platform analyzes job descriptions, suggests improvements to your content, and provides real-time compatibility scoring to maximize your chances of getting past automated screening systems.
                                </div>
                            </div>
                        </div>

                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                            <button
                                @click="open = open === 'g2' ? null : 'g2'"
                                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors"
                            >
                                <span class="text-sm font-medium text-zinc-900 dark:text-white pr-4">Who is CVForge for?</span>
                                <flux:icon :name="open === 'g2' ? 'chevron-up' : 'chevron-down'" class="size-5 text-zinc-500 dark:text-zinc-400 shrink-0" />
                            </button>
                            <div x-show="open === 'g2'" x-collapse x-cloak>
                                <div class="px-6 pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    CVForge is designed for anyone looking to create a professional resume, from recent graduates entering the job market to experienced professionals making career transitions. Whether you are applying for your first role or your twentieth, our AI adapts to your experience level and industry.
                                </div>
                            </div>
                        </div>

                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                            <button
                                @click="open = open === 'g3' ? null : 'g3'"
                                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors"
                            >
                                <span class="text-sm font-medium text-zinc-900 dark:text-white pr-4">Is my data secure?</span>
                                <flux:icon :name="open === 'g3' ? 'chevron-up' : 'chevron-down'" class="size-5 text-zinc-500 dark:text-zinc-400 shrink-0" />
                            </button>
                            <div x-show="open === 'g3'" x-collapse x-cloak>
                                <div class="px-6 pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    Absolutely. We take data privacy seriously. All personal information is encrypted at rest and in transit using industry-standard protocols. We never sell or share your data with third parties. CVForge is fully compliant with GDPR and SOC 2 standards.
                                </div>
                            </div>
                        </div>

                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                            <button
                                @click="open = open === 'g4' ? null : 'g4'"
                                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors"
                            >
                                <span class="text-sm font-medium text-zinc-900 dark:text-white pr-4">Can I use CVForge on mobile devices?</span>
                                <flux:icon :name="open === 'g4' ? 'chevron-up' : 'chevron-down'" class="size-5 text-zinc-500 dark:text-zinc-400 shrink-0" />
                            </button>
                            <div x-show="open === 'g4'" x-collapse x-cloak>
                                <div class="px-6 pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    Yes. CVForge is fully responsive and works on any device with a modern web browser, including smartphones and tablets. You can start building your CV on your laptop and continue editing on your phone seamlessly.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Features --}}
                <div x-data="{ open: null }" class="mb-12">
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-6 flex items-center gap-3">
                        <flux:icon name="bolt" class="size-6 text-emerald-600 dark:text-emerald-400" variant="solid" />
                        Features
                    </h2>
                    <div class="space-y-3">
                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                            <button
                                @click="open = open === 'f1' ? null : 'f1'"
                                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors"
                            >
                                <span class="text-sm font-medium text-zinc-900 dark:text-white pr-4">How does the AI writing assistant work?</span>
                                <flux:icon :name="open === 'f1' ? 'chevron-up' : 'chevron-down'" class="size-5 text-zinc-500 dark:text-zinc-400 shrink-0" />
                            </button>
                            <div x-show="open === 'f1'" x-collapse x-cloak>
                                <div class="px-6 pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    Our AI analyzes your work experience, the target job description, and current hiring trends to generate optimized bullet points and summaries. It suggests stronger action verbs, quantifies achievements, and ensures your content aligns with what recruiters in your industry are looking for.
                                </div>
                            </div>
                        </div>

                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                            <button
                                @click="open = open === 'f2' ? null : 'f2'"
                                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors"
                            >
                                <span class="text-sm font-medium text-zinc-900 dark:text-white pr-4">What does ATS optimization mean?</span>
                                <flux:icon :name="open === 'f2' ? 'chevron-up' : 'chevron-down'" class="size-5 text-zinc-500 dark:text-zinc-400 shrink-0" />
                            </button>
                            <div x-show="open === 'f2'" x-collapse x-cloak>
                                <div class="px-6 pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    ATS stands for Applicant Tracking System. Over 98% of large companies use ATS software to filter resumes before a human ever sees them. Our optimization engine scans your CV against a job description, checks for required keywords, proper formatting, and common ATS pitfalls, then gives you a compatibility score with actionable improvement suggestions.
                                </div>
                            </div>
                        </div>

                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                            <button
                                @click="open = open === 'f3' ? null : 'f3'"
                                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors"
                            >
                                <span class="text-sm font-medium text-zinc-900 dark:text-white pr-4">What file formats can I export my CV in?</span>
                                <flux:icon :name="open === 'f3' ? 'chevron-up' : 'chevron-down'" class="size-5 text-zinc-500 dark:text-zinc-400 shrink-0" />
                            </button>
                            <div x-show="open === 'f3'" x-collapse x-cloak>
                                <div class="px-6 pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    You can export your CV as a high-quality PDF, which is the industry standard for job applications. PDF files preserve your formatting exactly as designed and are universally accepted by ATS systems and hiring managers.
                                </div>
                            </div>
                        </div>

                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                            <button
                                @click="open = open === 'f4' ? null : 'f4'"
                                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors"
                            >
                                <span class="text-sm font-medium text-zinc-900 dark:text-white pr-4">Can I create multiple CVs for different job applications?</span>
                                <flux:icon :name="open === 'f4' ? 'chevron-up' : 'chevron-down'" class="size-5 text-zinc-500 dark:text-zinc-400 shrink-0" />
                            </button>
                            <div x-show="open === 'f4'" x-collapse x-cloak>
                                <div class="px-6 pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    Yes. Pro and Enterprise users can create unlimited CVs and tailor each one to specific job applications. This is highly recommended, as customizing your CV for each role significantly increases your chances of passing ATS screening and landing interviews.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div x-data="{ open: null }" class="mb-12">
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-6 flex items-center gap-3">
                        <flux:icon name="briefcase" class="size-6 text-emerald-600 dark:text-emerald-400" variant="solid" />
                        Pricing
                    </h2>
                    <div class="space-y-3">
                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                            <button
                                @click="open = open === 'p1' ? null : 'p1'"
                                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors"
                            >
                                <span class="text-sm font-medium text-zinc-900 dark:text-white pr-4">Is there a free trial for the Pro plan?</span>
                                <flux:icon :name="open === 'p1' ? 'chevron-up' : 'chevron-down'" class="size-5 text-zinc-500 dark:text-zinc-400 shrink-0" />
                            </button>
                            <div x-show="open === 'p1'" x-collapse x-cloak>
                                <div class="px-6 pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    Yes. Every new account starts with a 7-day free trial of the Pro plan. You get full access to all Pro features, including unlimited CVs, all templates, and unlimited AI suggestions. No credit card is required to start your trial.
                                </div>
                            </div>
                        </div>

                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                            <button
                                @click="open = open === 'p2' ? null : 'p2'"
                                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors"
                            >
                                <span class="text-sm font-medium text-zinc-900 dark:text-white pr-4">Can I cancel my subscription at any time?</span>
                                <flux:icon :name="open === 'p2' ? 'chevron-up' : 'chevron-down'" class="size-5 text-zinc-500 dark:text-zinc-400 shrink-0" />
                            </button>
                            <div x-show="open === 'p2'" x-collapse x-cloak>
                                <div class="px-6 pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    Absolutely. You can cancel your subscription at any time from your account settings. There are no cancellation fees or long-term commitments. If you cancel, you will retain access to Pro features until the end of your current billing period.
                                </div>
                            </div>
                        </div>

                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                            <button
                                @click="open = open === 'p3' ? null : 'p3'"
                                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors"
                            >
                                <span class="text-sm font-medium text-zinc-900 dark:text-white pr-4">Do you offer discounts for annual billing?</span>
                                <flux:icon :name="open === 'p3' ? 'chevron-up' : 'chevron-down'" class="size-5 text-zinc-500 dark:text-zinc-400 shrink-0" />
                            </button>
                            <div x-show="open === 'p3'" x-collapse x-cloak>
                                <div class="px-6 pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    Yes. When you choose annual billing, you save 20% compared to the monthly price. That brings the Pro plan down to $7.20/month and the Enterprise plan to $23.20/month. The discount is applied automatically when you select the annual option.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Technical --}}
                <div x-data="{ open: null }">
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-6 flex items-center gap-3">
                        <flux:icon name="code-bracket" class="size-6 text-emerald-600 dark:text-emerald-400" variant="solid" />
                        Technical
                    </h2>
                    <div class="space-y-3">
                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                            <button
                                @click="open = open === 't1' ? null : 't1'"
                                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors"
                            >
                                <span class="text-sm font-medium text-zinc-900 dark:text-white pr-4">Which browsers are supported?</span>
                                <flux:icon :name="open === 't1' ? 'chevron-up' : 'chevron-down'" class="size-5 text-zinc-500 dark:text-zinc-400 shrink-0" />
                            </button>
                            <div x-show="open === 't1'" x-collapse x-cloak>
                                <div class="px-6 pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    CVForge supports all modern browsers including Chrome, Firefox, Safari, and Edge. We recommend using the latest version of your preferred browser for the best experience. Internet Explorer is not supported.
                                </div>
                            </div>
                        </div>

                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                            <button
                                @click="open = open === 't2' ? null : 't2'"
                                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors"
                            >
                                <span class="text-sm font-medium text-zinc-900 dark:text-white pr-4">Does CVForge offer an API?</span>
                                <flux:icon :name="open === 't2' ? 'chevron-up' : 'chevron-down'" class="size-5 text-zinc-500 dark:text-zinc-400 shrink-0" />
                            </button>
                            <div x-show="open === 't2'" x-collapse x-cloak>
                                <div class="px-6 pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    Yes, API access is available on the Enterprise plan. Our RESTful API allows you to integrate CVForge functionality into your existing HR tools, job boards, or career platforms. Full documentation and SDK support are provided upon request.
                                </div>
                            </div>
                        </div>

                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                            <button
                                @click="open = open === 't3' ? null : 't3'"
                                class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors"
                            >
                                <span class="text-sm font-medium text-zinc-900 dark:text-white pr-4">How do I report a bug or request a feature?</span>
                                <flux:icon :name="open === 't3' ? 'chevron-up' : 'chevron-down'" class="size-5 text-zinc-500 dark:text-zinc-400 shrink-0" />
                            </button>
                            <div x-show="open === 't3'" x-collapse x-cloak>
                                <div class="px-6 pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    You can report bugs or request features through our contact page, by emailing support@cvforge.com, or by using the in-app feedback button. Our team reviews all submissions and aims to respond within 24 hours. Many of our most popular features started as user suggestions.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Still have questions --}}
                <div class="mt-16 text-center">
                    <flux:card>
                        <div class="p-8 lg:p-12">
                            <flux:icon name="mail" class="size-10 text-emerald-600 dark:text-emerald-400 mx-auto mb-4" variant="solid" />
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">Still Have Questions?</h3>
                            <p class="text-zinc-600 dark:text-zinc-400 mb-6">Our support team is ready to help you with anything you need.</p>
                            <flux:button variant="primary" href="{{ route('contact') }}" icon="arrow-right">
                                Contact Support
                            </flux:button>
                        </div>
                    </flux:card>
                </div>
            </div>
        </section>
    </main>
</x-layouts::landing>
