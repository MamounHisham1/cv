<x-layouts::landing>
    <main>
        {{-- Hero Section --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 right-10 w-96 h-96 bg-emerald-300 rounded-full blur-3xl"></div>
            </div>
            <div class="relative mx-auto max-w-7xl px-6 lg:px-8 py-24 lg:py-36">
                <div class="max-w-3xl mx-auto text-center">
                    <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm text-white text-sm font-medium px-4 py-2 rounded-full mb-8">
                        <flux:icon name="sparkles" class="size-4" variant="solid" />
                        AI-Powered Resume Building
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                        Build Your Perfect CV with AI
                    </h1>
                    <p class="text-lg md:text-xl text-emerald-100 leading-relaxed mb-10 max-w-2xl mx-auto">
                        Create professional, ATS-optimized resumes in minutes. Our AI analyzes job descriptions and tailors your CV to maximize your chances of landing interviews.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <flux:button variant="primary" size="base" :href="route('cv.builder')" icon="arrow-right" class="bg-white text-emerald-700 hover:bg-emerald-50 shadow-lg w-full sm:w-auto">
                            Get Started Free
                        </flux:button>
                        <flux:button variant="ghost" size="base" href="#features" icon="academic-cap" class="text-white hover:bg-white/10 border border-white/25 w-full sm:w-auto">
                            Learn More
                        </flux:button>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features Section --}}
        <section id="features" class="py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <flux:badge variant="subtle" color="emerald" class="mb-4">Features</flux:badge>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Everything You Need to Land Your Dream Job
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        Our comprehensive toolkit helps you create, optimize, and manage professional resumes with ease.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    <flux:card class="group hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-200">
                        <div class="p-6 lg:p-8">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-5">
                                <flux:icon name="sparkles" class="size-6 text-emerald-600 dark:text-emerald-400" variant="solid" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">AI-Powered Writing</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Generate compelling bullet points, summaries, and cover letters tailored to your industry and experience level using advanced AI models.
                            </p>
                        </div>
                    </flux:card>

                    <flux:card class="group hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-200">
                        <div class="p-6 lg:p-8">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-5">
                                <flux:icon name="shield-check" class="size-6 text-emerald-600 dark:text-emerald-400" variant="solid" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">ATS Optimization</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Ensure your CV passes Applicant Tracking Systems with keyword analysis, formatting checks, and real-time compatibility scoring.
                            </p>
                        </div>
                    </flux:card>

                    <flux:card class="group hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-200">
                        <div class="p-6 lg:p-8">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-5">
                                <flux:icon name="document-text" class="size-6 text-emerald-600 dark:text-emerald-400" variant="solid" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Professional Templates</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Choose from over 50 professionally designed templates, each crafted by hiring managers and career experts to make a lasting impression.
                            </p>
                        </div>
                    </flux:card>

                    <flux:card class="group hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-200">
                        <div class="p-6 lg:p-8">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-5">
                                <flux:icon name="eye" class="size-6 text-emerald-600 dark:text-emerald-400" variant="solid" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Real-Time Preview</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                See exactly how your CV will look as you edit. Our live preview updates instantly so you can perfect every detail before exporting.
                            </p>
                        </div>
                    </flux:card>

                    <flux:card class="group hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-200">
                        <div class="p-6 lg:p-8">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-5">
                                <flux:icon name="light-bulb" class="size-6 text-emerald-600 dark:text-emerald-400" variant="solid" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Smart Suggestions</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Get intelligent recommendations for skills, action verbs, and content improvements based on current hiring trends and job market data.
                            </p>
                        </div>
                    </flux:card>

                    <flux:card class="group hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-200">
                        <div class="p-6 lg:p-8">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-5">
                                <flux:icon name="arrow-down-tray" class="size-6 text-emerald-600 dark:text-emerald-400" variant="solid" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Export Ready</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Download your polished CV in PDF format, perfectly formatted and ready to submit. Share via link or email directly from the platform.
                            </p>
                        </div>
                    </flux:card>
                </div>
            </div>
        </section>

        {{-- How It Works Section --}}
        <section class="py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <flux:badge variant="subtle" color="emerald" class="mb-4">How It Works</flux:badge>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Three Simple Steps to Your Perfect CV
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        Creating a professional resume has never been easier. Our streamlined process gets you from zero to polished in minutes.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-full bg-emerald-600 text-white flex items-center justify-center mx-auto mb-6 text-2xl font-bold">
                            1
                        </div>
                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-white mb-3">Fill Your Info</h3>
                        <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Enter your work experience, education, skills, and certifications. Our guided form makes it easy to capture every detail.
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 rounded-full bg-emerald-600 text-white flex items-center justify-center mx-auto mb-6 text-2xl font-bold">
                            2
                        </div>
                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-white mb-3">AI Enhances</h3>
                        <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Our AI analyzes your content and optimizes it for impact. Get stronger action verbs, better descriptions, and ATS-friendly formatting.
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 rounded-full bg-emerald-600 text-white flex items-center justify-center mx-auto mb-6 text-2xl font-bold">
                            3
                        </div>
                        <h3 class="text-xl font-semibold text-zinc-900 dark:text-white mb-3">Download & Apply</h3>
                        <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Preview your finished CV, make any final adjustments, then export as a clean PDF ready to send to employers.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Stats Bar --}}
        <section class="bg-gradient-to-r from-emerald-600 to-emerald-700">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 py-12 lg:py-16">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-white mb-1">10,000+</div>
                        <div class="text-emerald-100 text-sm md:text-base">CVs Created</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-white mb-1">95%</div>
                        <div class="text-emerald-100 text-sm md:text-base">ATS Pass Rate</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-white mb-1">50+</div>
                        <div class="text-emerald-100 text-sm md:text-base">Templates</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-bold text-white mb-1">4.9/5</div>
                        <div class="text-emerald-100 text-sm md:text-base">User Rating</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Testimonials Section --}}
        <section class="py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <flux:badge variant="subtle" color="emerald" class="mb-4">Testimonials</flux:badge>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Trusted by Professionals Worldwide
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        Hear from people who landed their dream jobs using CVForge.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    <flux:card>
                        <div class="p-6 lg:p-8">
                            <div class="flex items-center gap-1 mb-4">
                                <flux:icon name="star" class="size-5 text-amber-400" variant="solid" />
                                <flux:icon name="star" class="size-5 text-amber-400" variant="solid" />
                                <flux:icon name="star" class="size-5 text-amber-400" variant="solid" />
                                <flux:icon name="star" class="size-5 text-amber-400" variant="solid" />
                                <flux:icon name="star" class="size-5 text-amber-400" variant="solid" />
                            </div>
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6">
                                "CVForge completely transformed my job search. The AI suggestions made my experience descriptions so much stronger, and I started getting interview calls within a week. The ATS checker was a game-changer."
                            </p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                    <span class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">SR</span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-zinc-900 dark:text-white">Sarah Rodriguez</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">Software Engineer at Google</div>
                                </div>
                            </div>
                        </div>
                    </flux:card>

                    <flux:card>
                        <div class="p-6 lg:p-8">
                            <div class="flex items-center gap-1 mb-4">
                                <flux:icon name="star" class="size-5 text-amber-400" variant="solid" />
                                <flux:icon name="star" class="size-5 text-amber-400" variant="solid" />
                                <flux:icon name="star" class="size-5 text-amber-400" variant="solid" />
                                <flux:icon name="star" class="size-5 text-amber-400" variant="solid" />
                                <flux:icon name="star" class="size-5 text-amber-400" variant="solid" />
                            </div>
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6">
                                "As a career changer, I struggled to present my transferable skills effectively. CVForge's AI helped me reframe my experience and within two months I landed a role in product management."
                            </p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <span class="text-sm font-semibold text-blue-700 dark:text-blue-300">MK</span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-zinc-900 dark:text-white">Marcus Kim</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">Product Manager at Stripe</div>
                                </div>
                            </div>
                        </div>
                    </flux:card>

                    <flux:card>
                        <div class="p-6 lg:p-8">
                            <div class="flex items-center gap-1 mb-4">
                                <flux:icon name="star" class="size-5 text-amber-400" variant="solid" />
                                <flux:icon name="star" class="size-5 text-amber-400" variant="solid" />
                                <flux:icon name="star" class="size-5 text-amber-400" variant="solid" />
                                <flux:icon name="star" class="size-5 text-amber-400" variant="solid" />
                                <flux:icon name="star" class="size-5 text-amber-400" variant="solid" />
                            </div>
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6">
                                "The templates are stunning and the real-time preview is incredibly useful. I customized a design that perfectly matched my personal brand. Recruiters frequently compliment my CV format."
                            </p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                    <span class="text-sm font-semibold text-purple-700 dark:text-purple-300">EP</span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-zinc-900 dark:text-white">Elena Petrova</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">UX Designer at Figma</div>
                                </div>
                            </div>
                        </div>
                    </flux:card>
                </div>
            </div>
        </section>

        {{-- Pricing Section --}}
        <section id="pricing" class="py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <flux:badge variant="subtle" color="emerald" class="mb-4">Pricing</flux:badge>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Simple, Transparent Pricing
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        Start free and upgrade when you need more. No hidden fees, cancel anytime.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 items-start">
                    {{-- Free Tier --}}
                    <flux:card>
                        <div class="p-6 lg:p-8">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-1">Free</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">Perfect for getting started</p>
                            <div class="mb-6">
                                <span class="text-4xl font-bold text-zinc-900 dark:text-white">$0</span>
                                <span class="text-zinc-500 dark:text-zinc-400">/month</span>
                            </div>
                            <flux:button variant="outline" :href="route('register')" class="w-full mb-8">
                                Get Started
                            </flux:button>
                            <ul class="space-y-3">
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    1 CV with basic template
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    PDF export with watermark
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    Basic ATS compatibility check
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    5 AI suggestions per CV
                                </li>
                            </ul>
                        </div>
                    </flux:card>

                    {{-- Pro Tier --}}
                    <flux:card class="ring-2 ring-emerald-500 relative">
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                            <span class="bg-emerald-600 text-white text-xs font-semibold px-3 py-1 rounded-full">Most Popular</span>
                        </div>
                        <div class="p-6 lg:p-8">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-1">Pro</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">For serious job seekers</p>
                            <div class="mb-6">
                                <span class="text-4xl font-bold text-zinc-900 dark:text-white">$9</span>
                                <span class="text-zinc-500 dark:text-zinc-400">/month</span>
                            </div>
                            <flux:button variant="primary" :href="route('register')" class="w-full mb-8">
                                Start Free Trial
                            </flux:button>
                            <ul class="space-y-3">
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    Unlimited CVs
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    All 50+ premium templates
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    Clean PDF export, no watermark
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    Advanced ATS optimization
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    Unlimited AI suggestions
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    AI cover letter generator
                                </li>
                            </ul>
                        </div>
                    </flux:card>

                    {{-- Enterprise Tier --}}
                    <flux:card>
                        <div class="p-6 lg:p-8">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-1">Enterprise</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">For teams and organizations</p>
                            <div class="mb-6">
                                <span class="text-4xl font-bold text-zinc-900 dark:text-white">$29</span>
                                <span class="text-zinc-500 dark:text-zinc-400">/month</span>
                            </div>
                            <flux:button variant="outline" :href="route('contact')" class="w-full mb-8">
                                Contact Sales
                            </flux:button>
                            <ul class="space-y-3">
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    Everything in Pro
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    Team management dashboard
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    Custom branding on templates
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    Priority support and onboarding
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    API access and integrations
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <flux:icon name="check" class="size-5 text-emerald-500 shrink-0 mt-0.5" variant="solid" />
                                    SSO and advanced security
                                </li>
                            </ul>
                        </div>
                    </flux:card>
                </div>
            </div>
        </section>

        {{-- CTA Section --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-20 -right-20 w-96 h-96 bg-white rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-emerald-300 rounded-full blur-3xl"></div>
            </div>
            <div class="relative mx-auto max-w-7xl px-6 lg:px-8 py-20 lg:py-28 text-center">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">
                    Ready to Stand Out?
                </h2>
                <p class="text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto mb-10 leading-relaxed">
                    Join thousands of professionals who have already transformed their job search. Your next career move starts with a great CV.
                </p>
                <flux:button variant="primary" size="base" :href="route('register')" icon="arrow-right" class="bg-white text-emerald-700 hover:bg-emerald-50 shadow-lg">
                    Create Your CV Now
                </flux:button>
            </div>
        </section>
    </main>
</x-layouts::landing>
