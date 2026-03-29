<x-layouts::landing>
    <main>
        <section class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 animate-gradient min-h-[600px] flex items-center">
            <div class="absolute inset-0">
                <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full blur-3xl animate-pulse-glow"></div>
                <div class="absolute bottom-20 right-10 w-96 h-96 bg-emerald-300 rounded-full blur-3xl animate-pulse-glow" style="animation-delay: 2s;"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-emerald-400 rounded-full blur-[120px] opacity-10"></div>

                <div class="absolute top-16 right-1/4 w-3 h-3 bg-emerald-300 rounded-full animate-float opacity-40"></div>
                <div class="absolute top-32 left-1/3 w-2 h-2 bg-white rounded-full animate-float-reverse opacity-30" style="animation-delay: 1s;"></div>
                <div class="absolute bottom-32 right-1/3 w-4 h-4 bg-emerald-200 rounded-full animate-float-slow opacity-30" style="animation-delay: 0.5s;"></div>
                <div class="absolute top-1/2 left-20 w-2 h-2 bg-white rounded-full animate-float opacity-25" style="animation-delay: 3s;"></div>
                <div class="absolute bottom-40 left-1/4 w-3 h-3 bg-emerald-300 rounded-full animate-float-reverse opacity-35" style="animation-delay: 2s;"></div>
                <div class="absolute top-24 right-16 w-2 h-2 bg-white rounded-full animate-float-slow opacity-20" style="animation-delay: 4s;"></div>
            </div>

            <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8 py-24 lg:py-36">
                <div class="max-w-3xl mx-auto text-center">
                    <div class="animate-slide-up inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm text-white text-sm font-medium px-4 py-2 rounded-full mb-8 border border-white/20">
                        <x-ui::icon name="sparkles" size="sm" />
                        AI-Powered Resume Building
                    </div>
                    <h1 class="animate-slide-up delay-100 text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                        Build Your Perfect CV
                        <span class="relative inline-block">
                            with AI
                            <svg class="absolute -bottom-2 left-0 w-full" viewBox="0 0 200 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 8C50 2 150 2 198 8" stroke="rgba(255,255,255,0.5)" stroke-width="3" stroke-linecap="round"/></svg>
                        </span>
                    </h1>
                    <p class="animate-slide-up delay-200 text-lg md:text-xl text-emerald-100 leading-relaxed mb-10 max-w-2xl mx-auto">
                        Create professional, ATS-optimized resumes in minutes. Our AI analyzes job descriptions and tailors your CV to maximize your chances of landing interviews.
                    </p>
                    <div class="animate-slide-up delay-300 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <x-ui::button variant="primary" :href="route('cv.builder')" icon="arrow-right" class="bg-white text-emerald-700 hover:bg-emerald-50 shadow-lg shadow-emerald-900/30 w-full sm:w-auto hover:shadow-xl hover:shadow-emerald-900/40 hover:-translate-y-0.5 transition-all duration-300">
                            Get Started Free
                        </x-ui::button>
                        <x-ui::button variant="ghost" href="#features" icon="graduation-cap" class="text-white hover:bg-white/10 border border-white/25 w-full sm:w-auto hover:-translate-y-0.5 transition-all duration-300">
                            Learn More
                        </x-ui::button>
                    </div>

                    <div class="animate-fade-in delay-500 mt-16 flex items-center justify-center gap-8 text-white/80 text-sm">
                        <div class="flex items-center gap-2">
                            <x-ui::icon name="check-circle" size="sm" />
                            Free to start
                        </div>
                        <div class="flex items-center gap-2">
                            <x-ui::icon name="zap" size="sm" />
                            AI-powered
                        </div>
                        <div class="flex items-center gap-2 max-sm:hidden">
                            <x-ui::icon name="shield-check" size="sm" />
                            ATS-friendly
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white dark:from-zinc-950 to-transparent pointer-events-none"></div>
        </section>

        <section id="features" class="py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <x-ui::badge variant="brand" class="mb-4">Features</x-ui::badge>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Everything You Need to Land Your Dream Job
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        Our comprehensive toolkit helps you create, optimize, and manage professional resumes with ease.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    <x-ui::card class="group hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div class="p-6 lg:p-8">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-5 group-hover:scale-110 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800/50 transition-all duration-300">
                                <x-ui::icon name="sparkles" size="lg" class="text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">AI-Powered Writing</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Generate compelling bullet points, summaries, and cover letters tailored to your industry and experience level using advanced AI models.
                            </p>
                        </div>
                    </x-ui::card>

                    <x-ui::card class="group hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div class="p-6 lg:p-8">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-5 group-hover:scale-110 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800/50 transition-all duration-300">
                                <x-ui::icon name="shield-check" size="lg" class="text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">ATS Optimization</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Ensure your CV passes Applicant Tracking Systems with keyword analysis, formatting checks, and real-time compatibility scoring.
                            </p>
                        </div>
                    </x-ui::card>

                    <x-ui::card class="group hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div class="p-6 lg:p-8">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-5 group-hover:scale-110 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800/50 transition-all duration-300">
                                <x-ui::icon name="file-text" size="lg" class="text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Professional Templates</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Choose from over 50 professionally designed templates, each crafted by hiring managers and career experts to make a lasting impression.
                            </p>
                        </div>
                    </x-ui::card>

                    <x-ui::card class="group hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div class="p-6 lg:p-8">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-5 group-hover:scale-110 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800/50 transition-all duration-300">
                                <x-ui::icon name="eye" size="lg" class="text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Real-Time Preview</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                See exactly how your CV will look as you edit. Our live preview updates instantly so you can perfect every detail before exporting.
                            </p>
                        </div>
                    </x-ui::card>

                    <x-ui::card class="group hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div class="p-6 lg:p-8">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-5 group-hover:scale-110 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800/50 transition-all duration-300">
                                <x-ui::icon name="lightbulb" size="lg" class="text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Smart Suggestions</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Get intelligent recommendations for skills, action verbs, and content improvements based on current hiring trends and job market data.
                            </p>
                        </div>
                    </x-ui::card>

                    <x-ui::card class="group hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div class="p-6 lg:p-8">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-5 group-hover:scale-110 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800/50 transition-all duration-300">
                                <x-ui::icon name="download" size="lg" class="text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Export Ready</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Download your polished CV in PDF format, perfectly formatted and ready to submit. Share via link or email directly from the platform.
                            </p>
                        </div>
                    </x-ui::card>
                </div>
            </div>
        </section>

        <section class="py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <x-ui::badge variant="brand" class="mb-4">How It Works</x-ui::badge>
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

        <section class="py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <x-ui::badge variant="brand" class="mb-4">Testimonials</x-ui::badge>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Trusted by Professionals Worldwide
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        Hear from people who landed their dream jobs using SeratyAI.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    <x-ui::card>
                        <div class="p-6 lg:p-8">
                            <div class="flex items-center gap-1 mb-4">
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
                            </div>
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6">
                                "SeratyAI completely transformed my job search. The AI suggestions made my experience descriptions so much stronger, and I started getting interview calls within a week. The ATS checker was a game-changer."
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
                    </x-ui::card>

                    <x-ui::card>
                        <div class="p-6 lg:p-8">
                            <div class="flex items-center gap-1 mb-4">
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
                            </div>
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6">
                                "As a career changer, I struggled to present my transferable skills effectively. SeratyAI's AI helped me reframe my experience and within two months I landed a role in product management."
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
                    </x-ui::card>

                    <x-ui::card>
                        <div class="p-6 lg:p-8">
                            <div class="flex items-center gap-1 mb-4">
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
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
                    </x-ui::card>
                </div>
            </div>
        </section>

        <section id="pricing" class="py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <x-ui::badge variant="brand" class="mb-4">Pricing</x-ui::badge>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Simple, Transparent Pricing
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        Start free and upgrade when you need more. No hidden fees, cancel anytime.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    <x-ui::card class="flex flex-col">
                        <div class="p-6 lg:p-8 flex flex-col flex-1">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-1">Free</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">Perfect for getting started</p>
                            <div class="mb-6">
                                <span class="text-4xl font-bold text-zinc-900 dark:text-white">$0</span>
                                <span class="text-zinc-500 dark:text-zinc-400">/month</span>
                            </div>
                            <x-ui::button variant="outline" :href="route('register')" class="w-full mb-8">
                                Get Started
                            </x-ui::button>
                            <ul class="space-y-3 mt-auto">
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    1 CV with basic template
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    PDF export with watermark
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    Basic ATS compatibility check
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    5 AI suggestions per CV
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-400">
                                    <x-ui::icon name="x" size="md" class="text-red-400 shrink-0 mt-0.5" />
                                    Unlimited CVs
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-400">
                                    <x-ui::icon name="x" size="md" class="text-red-400 shrink-0 mt-0.5" />
                                    AI cover letter generator
                                </li>
                            </ul>
                        </div>
                    </x-ui::card>

                    <x-ui::card class="ring-2 ring-emerald-500 relative flex flex-col">
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                            <span class="bg-emerald-600 text-white text-xs font-semibold px-3 py-1 rounded-full">Most Popular</span>
                        </div>
                        <div class="p-6 lg:p-8 flex flex-col flex-1">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-1">Pro</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">For serious job seekers</p>
                            <div class="mb-6">
                                <span class="text-4xl font-bold text-zinc-900 dark:text-white">$9</span>
                                <span class="text-zinc-500 dark:text-zinc-400">/month</span>
                            </div>
                            <x-ui::button variant="primary" :href="route('register')" class="w-full mb-8">
                                Start Free Trial
                            </x-ui::button>
                            <ul class="space-y-3 mt-auto">
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    Unlimited CVs
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    All 50+ premium templates
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    Clean PDF export, no watermark
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    Advanced ATS optimization
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    Unlimited AI suggestions
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    AI cover letter generator
                                </li>
                            </ul>
                        </div>
                    </x-ui::card>

                    <x-ui::card class="flex flex-col">
                        <div class="p-6 lg:p-8 flex flex-col flex-1">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-1">Enterprise</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">For teams and organizations</p>
                            <div class="mb-6">
                                <span class="text-4xl font-bold text-zinc-900 dark:text-white">$29</span>
                                <span class="text-zinc-500 dark:text-zinc-400">/month</span>
                            </div>
                            <x-ui::button variant="outline" :href="route('contact')" class="w-full mb-8">
                                Contact Sales
                            </x-ui::button>
                            <ul class="space-y-3 mt-auto">
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    Everything in Pro
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    Team management dashboard
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    Custom branding on templates
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    Priority support and onboarding
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    API access and integrations
                                </li>
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    SSO and advanced security
                                </li>
                            </ul>
                        </div>
                    </x-ui::card>
                </div>
            </div>
        </section>

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
                <x-ui::button variant="primary" :href="route('register')" icon="arrow-right" class="bg-white text-emerald-700 hover:bg-emerald-50 shadow-lg">
                    Create Your CV Now
                </x-ui::button>
            </div>
        </section>
    </main>
</x-layouts::landing>
