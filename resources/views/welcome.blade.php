<x-layouts::landing>
    <main class="relative">
        {{-- Hero Section with Enhanced Animations --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 min-h-[700px] flex items-center">
            {{-- Animated Background Elements --}}
            <div class="absolute inset-0">
                <div class="absolute top-20 left-10 w-80 h-80 bg-white rounded-full blur-3xl animate-pulse-glow"></div>
                <div class="absolute bottom-20 right-10 w-[500px] h-[500px] bg-emerald-300 rounded-full blur-3xl animate-pulse-glow" style="animation-delay: 2s;"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-emerald-400 rounded-full blur-[120px] opacity-10"></div>
            </div>

            {{-- Floating Particles --}}
            <div class="absolute top-16 right-1/4 w-3 h-3 bg-white rounded-full animate-float opacity-40"></div>
            <div class="absolute top-32 left-1/3 w-2 h-2 bg-emerald-200 rounded-full animate-float-reverse opacity-30" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-32 right-1/3 w-4 h-4 bg-emerald-300 rounded-full animate-float-slow opacity-30" style="animation-delay: 0.5s;"></div>
            <div class="absolute top-1/2 left-20 w-2 h-2 bg-white rounded-full animate-float opacity-25" style="animation-delay: 3s;"></div>
            <div class="absolute bottom-40 left-1/4 w-3 h-3 bg-emerald-300 rounded-full animate-float-reverse opacity-35" style="animation-delay: 2s;"></div>
            <div class="absolute top-24 right-16 w-2 h-2 bg-white rounded-full animate-float-slow opacity-20" style="animation-delay: 4s;"></div>
            <div class="absolute bottom-16 right-1/4 w-2 h-2 bg-emerald-200 rounded-full animate-float opacity-30" style="animation-delay: 1.5s;"></div>
            <div class="absolute top-40 left-1/4 w-3 h-3 bg-white rounded-full animate-float-reverse opacity-25" style="animation-delay: 2.5s;"></div>

            {{-- Grid Pattern Overlay --}}
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8 py-24 lg:py-36">
                <div class="max-w-3xl mx-auto text-center">
                    {{-- Badge --}}
                    <div class="animate-slide-up inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm text-white text-sm font-semibold px-5 py-2.5 rounded-full mb-8 border border-white/20 shadow-lg hover:bg-white/20 transition-all duration-300">
                        <x-ui::icon name="sparkles" size="sm" class="animate-pulse" />
                        AI-Powered Resume Building
                    </div>

                    {{-- Main Heading --}}
                    <h1 class="animate-slide-up delay-100 text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                        Build Your Perfect CV
                        <span class="relative inline-block mt-2">
                            <span class="relative z-10">with AI</span>
                            <svg class="absolute -bottom-2 left-0 w-full" viewBox="0 0 200 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 8C50 2 150 2 198 8" stroke="rgba(255,255,255,0.6)" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </h1>

                    {{-- Subtitle --}}
                    <p class="animate-slide-up delay-200 text-lg md:text-xl text-emerald-100 leading-relaxed mb-10 max-w-2xl mx-auto">
                        Create professional, ATS-optimized resumes in minutes. Our AI analyzes job descriptions and tailors your CV to maximize your chances of landing interviews.
                    </p>

                    {{-- CTA Buttons --}}
                    <div class="animate-slide-up delay-300 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <x-ui::button variant="primary" :href="route('cv.builder')" icon="arrow-right" class="bg-white text-emerald-700 hover:bg-emerald-50 shadow-2xl shadow-emerald-900/30 w-full sm:w-auto hover:shadow-emerald-900/50 hover:-translate-y-1 transition-all duration-300 text-base px-8 py-3">
                            Get Started Free
                        </x-ui::button>
                        <x-ui::button variant="ghost" href="#features" icon="graduation-cap" class="text-white hover:bg-white/15 border-2 border-white/30 w-full sm:w-auto hover:-translate-y-1 transition-all duration-300 text-base px-8 py-3">
                            Learn More
                        </x-ui::button>
                    </div>

                    {{-- Trust Indicators --}}
                    <div class="animate-fade-in delay-500 mt-16 flex flex-wrap items-center justify-center gap-6 text-white/90 text-sm">
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                            <x-ui::icon name="check-circle" size="sm" class="text-emerald-300" />
                            Free to start
                        </div>
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                            <x-ui::icon name="zap" size="sm" class="text-yellow-300" />
                            AI-powered
                        </div>
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
                            <x-ui::icon name="shield-check" size="sm" class="text-blue-300" />
                            ATS-friendly
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom Gradient Fade --}}
            <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-white dark:from-zinc-950 to-transparent pointer-events-none"></div>
        </section>

        {{-- Features Section --}}
        <section id="features" class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950 overflow-hidden">
            {{-- Background Decorations --}}
            <div class="absolute top-0 left-0 w-96 h-96 bg-emerald-100 dark:bg-emerald-900/10 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-100 dark:bg-blue-900/10 rounded-full blur-3xl opacity-50"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                        <x-ui::icon name="sparkles" size="sm" />
                        Features
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Everything You Need to Land Your Dream Job
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        Our comprehensive toolkit helps you create, optimize, and manage professional resumes with ease.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach([
                        ['icon' => 'sparkles', 'title' => 'AI-Powered Writing', 'desc' => 'Generate compelling bullet points, summaries, and cover letters tailored to your industry and experience level using advanced AI models.', 'color' => 'emerald'],
                        ['icon' => 'shield-check', 'title' => 'ATS Optimization', 'desc' => 'Ensure your CV passes Applicant Tracking Systems with keyword analysis, formatting checks, and real-time compatibility scoring.', 'color' => 'blue'],
                        ['icon' => 'document-text', 'title' => 'Professional Templates', 'desc' => 'Choose from over 50 professionally designed templates, each crafted by hiring managers and career experts.', 'color' => 'purple'],
                        ['icon' => 'eye', 'title' => 'Real-Time Preview', 'desc' => 'See exactly how your CV will look as you edit. Our live preview updates instantly so you can perfect every detail.', 'color' => 'amber'],
                        ['icon' => 'lightbulb', 'title' => 'Smart Suggestions', 'desc' => 'Get intelligent recommendations for skills, action verbs, and content improvements based on hiring trends.', 'color' => 'pink'],
                        ['icon' => 'arrow-down-tray', 'title' => 'Export Ready', 'desc' => 'Download your polished CV in PDF format, perfectly formatted and ready to submit. Share via link or email.', 'color' => 'teal']
                    ] as $feature)
                    <x-ui::card class="group hover:shadow-2xl hover:shadow-{{ $feature['color'] }}-500/20 transition-all duration-500 hover:-translate-y-2 border-2 hover:border-{{ $feature['color'] }}-300 dark:hover:border-{{ $feature['color'] }}-700">
                        <div class="p-6 lg:p-8">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-{{ $feature['color'] }}-500 to-{{ $feature['color'] }}-600 flex items-center justify-center mb-6 shadow-xl shadow-{{ $feature['color'] }}-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                <x-ui::icon name="{{ $feature['icon'] }}" size="lg" class="text-white" />
                            </div>
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-3">{{ $feature['title'] }}</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $feature['desc'] }}</p>
                        </div>
                    </x-ui::card>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- How It Works Section --}}
        <section class="relative py-20 lg:py-28 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 overflow-hidden">
            {{-- Background Decorations --}}
            <div class="absolute top-0 right-0 w-80 h-80 bg-emerald-200 dark:bg-emerald-900/20 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-200 dark:bg-blue-900/20 rounded-full blur-3xl opacity-50"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                        <x-ui::icon name="bolt" size="sm" />
                        How It Works
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Three Simple Steps to Your Perfect CV
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        Creating a professional resume has never been easier. Our streamlined process gets you from zero to polished in minutes.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                    @foreach([
                        ['step' => 1, 'title' => 'Fill Your Info', 'desc' => 'Enter your work experience, education, skills, and certifications. Our guided form makes it easy to capture every detail.'],
                        ['step' => 2, 'title' => 'AI Enhances', 'desc' => 'Our AI analyzes your content and optimizes it for impact. Get stronger action verbs, better descriptions, and ATS-friendly formatting.'],
                        ['step' => 3, 'title' => 'Download & Apply', 'desc' => 'Preview your finished CV, make any final adjustments, then export as a clean PDF ready to send to employers.']
                    ] as $item)
                    <div class="group relative text-center">
                        {{-- Step Number Circle --}}
                        <div class="relative mb-6">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center mx-auto shadow-2xl shadow-emerald-500/40 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                <span class="text-3xl font-bold text-white">{{ $item['step'] }}</span>
                            </div>
                            <div class="absolute inset-0 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 blur-xl opacity-30 group-hover:opacity-50 transition-opacity duration-300 animate-pulse"></div>
                        </div>
                        <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-3">{{ $item['title'] }}</h3>
                        <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Stats Section --}}
        <section class="relative bg-gradient-to-r from-emerald-600 via-emerald-700 to-emerald-800 overflow-hidden">
            {{-- Animated Background --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-20 -right-20 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse-glow"></div>
                <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-emerald-300 rounded-full blur-3xl animate-pulse-glow" style="animation-delay: 2s;"></div>
            </div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8 py-16 lg:py-20">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                    @foreach([
                        ['value' => '10,000+', 'label' => 'CVs Created', 'color' => 'white'],
                        ['value' => '95%', 'label' => 'ATS Pass Rate', 'color' => 'emerald-200'],
                        ['value' => '50+', 'label' => 'Templates', 'color' => 'white'],
                        ['value' => '4.9/5', 'label' => 'User Rating', 'color' => 'amber-300']
                    ] as $stat)
                    <div class="group">
                        <div class="text-4xl md:text-5xl font-extrabold text-{{ $stat['color'] }} mb-2 group-hover:scale-110 transition-transform duration-300">{{ $stat['value'] }}</div>
                        <div class="text-emerald-100 text-sm md:text-base font-medium">{{ $stat['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Testimonials Section --}}
        <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950 overflow-hidden">
            {{-- Background Decorations --}}
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-amber-100 dark:bg-amber-900/10 rounded-full blur-3xl opacity-30"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-100 dark:bg-purple-900/10 rounded-full blur-3xl opacity-30"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                        <x-ui::icon name="chat-bubble-left-right" size="sm" />
                        Testimonials
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Trusted by Professionals Worldwide
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        Hear from people who landed their dream jobs using SeratyAI.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    @foreach([
                        ['name' => 'Sarah Rodriguez', 'role' => 'Software Engineer at Google', 'initials' => 'SR', 'color' => 'emerald', 'text' => 'SeratyAI completely transformed my job search. The AI suggestions made my experience descriptions so much stronger, and I started getting interview calls within a week. The ATS checker was a game-changer.'],
                        ['name' => 'Marcus Kim', 'role' => 'Product Manager at Stripe', 'initials' => 'MK', 'color' => 'blue', 'text' => 'As a career changer, I struggled to present my transferable skills effectively. SeratyAI\'s AI helped me reframe my experience and within two months I landed a role in product management.'],
                        ['name' => 'Elena Petrova', 'role' => 'UX Designer at Figma', 'initials' => 'EP', 'color' => 'purple', 'text' => 'The templates are stunning and the real-time preview is incredibly useful. I customized a design that perfectly matched my personal brand. Recruiters frequently compliment my CV format.']
                    ] as $testimonial)
                    <x-ui::card class="group hover:shadow-2xl hover:shadow-{{ $testimonial['color'] }}-500/20 transition-all duration-500 hover:-translate-y-2 border-2 hover:border-{{ $testimonial['color'] }}-300 dark:hover:border-{{ $testimonial['color'] }}-700">
                        <div class="p-6 lg:p-8">
                            {{-- Stars --}}
                            <div class="flex items-center gap-1 mb-6">
                                @for($i = 0; $i < 5; $i++)
                                <x-ui::icon name="star" size="md" class="text-amber-400" />
                                @endfor
                            </div>

                            {{-- Quote --}}
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6 text-sm">{{ $testimonial['text'] }}</p>

                            {{-- Author --}}
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-{{ $testimonial['color'] }}-500 to-{{ $testimonial['color'] }}-600 flex items-center justify-center shadow-lg">
                                    <span class="text-sm font-bold text-white">{{ $testimonial['initials'] }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-zinc-900 dark:text-white">{{ $testimonial['name'] }}</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400 font-medium">{{ $testimonial['role'] }}</div>
                                </div>
                            </div>
                        </div>
                    </x-ui::card>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Pricing Section --}}
        <section id="pricing" class="relative py-20 lg:py-28 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 overflow-hidden">
            {{-- Background Decorations --}}
            <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-200 dark:bg-emerald-900/20 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-200 dark:bg-blue-900/20 rounded-full blur-3xl opacity-50"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                        <x-ui::icon name="currency-dollar" size="sm" />
                        Pricing
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Simple, Transparent Pricing
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        Start free and upgrade when you need more. No hidden fees, cancel anytime.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    @foreach([
                        [
                            'name' => 'Free',
                            'desc' => 'Perfect for getting started',
                            'price' => '$0',
                            'period' => '/month',
                            'btnText' => 'Get Started',
                            'btnVariant' => 'outline',
                            'features' => [
                                ['text' => '1 CV with basic template', 'available' => true],
                                ['text' => 'PDF export with watermark', 'available' => true],
                                ['text' => 'Basic ATS compatibility check', 'available' => true],
                                ['text' => '5 AI suggestions per CV', 'available' => true],
                                ['text' => 'Unlimited CVs', 'available' => false],
                                ['text' => 'AI cover letter generator', 'available' => false]
                            ],
                            'popular' => false,
                            'color' => 'zinc'
                        ],
                        [
                            'name' => 'Pro',
                            'desc' => 'For serious job seekers',
                            'price' => '$9',
                            'period' => '/month',
                            'btnText' => 'Start Free Trial',
                            'btnVariant' => 'primary',
                            'features' => [
                                ['text' => 'Unlimited CVs', 'available' => true],
                                ['text' => 'All 50+ premium templates', 'available' => true],
                                ['text' => 'Clean PDF export, no watermark', 'available' => true],
                                ['text' => 'Advanced ATS optimization', 'available' => true],
                                ['text' => 'Unlimited AI suggestions', 'available' => true],
                                ['text' => 'AI cover letter generator', 'available' => true]
                            ],
                            'popular' => true,
                            'color' => 'emerald'
                        ],
                        [
                            'name' => 'Enterprise',
                            'desc' => 'For teams and organizations',
                            'price' => '$29',
                            'period' => '/month',
                            'btnText' => 'Contact Sales',
                            'btnVariant' => 'outline',
                            'features' => [
                                ['text' => 'Everything in Pro', 'available' => true],
                                ['text' => 'Team management dashboard', 'available' => true],
                                ['text' => 'Custom branding on templates', 'available' => true],
                                ['text' => 'Priority support and onboarding', 'available' => true],
                                ['text' => 'API access and integrations', 'available' => true],
                                ['text' => 'SSO and advanced security', 'available' => true]
                            ],
                            'popular' => false,
                            'color' => 'blue'
                        ]
                    ] as $plan)
                    <x-ui::card class="{{ $plan['popular'] ? 'ring-2 ring-' . $plan['color'] . '-500 relative scale-105 shadow-2xl shadow-' . $plan['color'] . '-500/20' : '' }} hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                        @if($plan['popular'])
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                            <span class="bg-{{ $plan['color'] }}-600 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg">Most Popular</span>
                        </div>
                        @endif
                        <div class="p-6 lg:p-8 flex flex-col flex-1">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-1">{{ $plan['name'] }}</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">{{ $plan['desc'] }}</p>
                            <div class="mb-8">
                                <span class="text-5xl font-extrabold text-zinc-900 dark:text-white">{{ $plan['price'] }}</span>
                                <span class="text-zinc-500 dark:text-zinc-400">{{ $plan['period'] }}</span>
                            </div>
                            <x-ui::button variant="{{ $plan['btnVariant'] }}" :href="route('register')" class="w-full mb-8 py-3">
                                {{ $plan['btnText'] }}
                            </x-ui::button>
                            <ul class="space-y-4 mt-auto">
                                @foreach($plan['features'] as $feature)
                                <li class="flex items-start gap-3 text-sm {{ $feature['available'] ? 'text-zinc-600 dark:text-zinc-400' : 'text-zinc-400 dark:text-zinc-500' }}">
                                    <x-ui::icon name="{{ $feature['available'] ? 'check-circle' : 'x-circle' }}" size="md" class="{{ $feature['available'] ? 'text-emerald-500' : 'text-zinc-300 dark:text-zinc-600' }} shrink-0 mt-0.5" />
                                    {{ $feature['text'] }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </x-ui::card>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA Section --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800">
            {{-- Animated Background --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-20 -right-20 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse-glow"></div>
                <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-emerald-300 rounded-full blur-3xl animate-pulse-glow" style="animation-delay: 2s;"></div>
            </div>

            {{-- Floating Particles --}}
            <div class="absolute top-16 left-1/4 w-3 h-3 bg-white rounded-full animate-float opacity-30"></div>
            <div class="absolute bottom-20 right-1/4 w-2 h-2 bg-emerald-200 rounded-full animate-float-reverse opacity-40" style="animation-delay: 1.5s;"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8 py-20 lg:py-28 text-center">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-6 leading-tight">
                    Ready to Stand Out?
                </h2>
                <p class="text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto mb-10 leading-relaxed">
                    Join thousands of professionals who have already transformed their job search. Your next career move starts with a great CV.
                </p>
                <x-ui::button variant="primary" :href="route('register')" icon="arrow-right" size="lg" class="bg-white text-emerald-700 hover:bg-emerald-50 shadow-2xl shadow-emerald-900/40 hover:shadow-emerald-900/60 hover:-translate-y-1 transition-all duration-300 px-10 py-4 text-base">
                    Create Your CV Now
                </x-ui::button>
            </div>
        </section>
    </main>
</x-layouts::landing>
