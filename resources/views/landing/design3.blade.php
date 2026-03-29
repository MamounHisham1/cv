<x-layouts::landing>
    <main class="relative">
        {{-- Hero: Clean Swiss layout with geometric accent --}}
        <section class="relative min-h-screen flex items-center bg-white dark:bg-zinc-950 overflow-hidden">
            {{-- Geometric accent --}}
            <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-bl from-emerald-50 to-transparent dark:from-emerald-950/30 dark:to-transparent"></div>
            <div class="absolute top-0 right-0 w-px h-full bg-zinc-200 dark:bg-zinc-800"></div>

            {{-- Floating geometric shapes --}}
            <div class="absolute top-32 right-32 w-16 h-16 border-2 border-emerald-300 dark:border-emerald-700 rounded-lg rotate-12 animate-float opacity-60"></div>
            <div class="absolute bottom-40 right-64 w-8 h-8 bg-emerald-500 rounded-full animate-float-reverse opacity-40" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 right-20 w-24 h-24 border border-zinc-200 dark:border-zinc-700 rounded-full animate-float-slow opacity-30" style="animation-delay: 1s;"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8 py-24 w-full">
                <div class="max-w-2xl">
                    <div class="animate-slide-up flex items-center gap-3 mb-10">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                        <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">AI-Powered Resume Builder</span>
                    </div>

                    <h1 class="animate-slide-up delay-100 text-5xl md:text-6xl lg:text-7xl font-bold text-zinc-900 dark:text-white leading-[1.05] mb-8 tracking-tight">
                        The modern way to build
                        <span class="text-emerald-600 dark:text-emerald-400">your CV.</span>
                    </h1>

                    <p class="animate-slide-up delay-200 text-lg text-zinc-500 dark:text-zinc-400 leading-relaxed mb-12 max-w-lg">
                        Create professional, ATS-optimized resumes in minutes with AI. Join 10,000+ professionals who landed their dream jobs.
                    </p>

                    <div class="animate-slide-up delay-300 flex items-center gap-4 mb-16">
                        <x-ui::button variant="primary" :href="route('cv.builder')" icon="arrow-right" class="bg-emerald-600 hover:bg-emerald-500 shadow-lg shadow-emerald-600/20 hover:shadow-emerald-500/30 hover:-translate-y-0.5 transition-all duration-300 px-8 py-3">
                            Get Started
                        </x-ui::button>
                        <x-ui::button variant="ghost" href="#features" class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white">
                            View Features
                        </x-ui::button>
                    </div>

                    <div class="animate-fade-in delay-500 flex items-center gap-8 text-sm text-zinc-400">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                            Free to start
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                            No credit card
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                            7-day trial
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Divider --}}
        <div class="border-t border-zinc-200 dark:border-zinc-800"></div>

        {{-- Features: Precise 3-col grid --}}
        <section id="features" class="relative py-24 lg:py-32 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex items-start justify-between mb-20">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                            <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Features</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white">Everything you need.</h2>
                    </div>
                    <p class="text-zinc-500 dark:text-zinc-400 max-w-sm text-right hidden md:block">Comprehensive toolkit for creating, optimizing, and managing professional resumes.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-px bg-zinc-200 dark:bg-zinc-800 rounded-xl overflow-hidden">
                    @foreach([
                        ['icon' => 'sparkles', 'title' => 'AI-Powered Writing', 'desc' => 'Generate compelling bullet points, summaries, and cover letters tailored to your industry.', 'color' => 'emerald'],
                        ['icon' => 'shield-check', 'title' => 'ATS Optimization', 'desc' => 'Ensure your CV passes Applicant Tracking Systems with keyword analysis and compatibility scoring.', 'color' => 'blue'],
                        ['icon' => 'document-text', 'title' => 'Professional Templates', 'desc' => 'Over 50 professionally designed templates crafted by hiring managers and career experts.', 'color' => 'purple'],
                        ['icon' => 'eye', 'title' => 'Real-Time Preview', 'desc' => 'See exactly how your CV will look as you edit with instant live preview updates.', 'color' => 'amber'],
                        ['icon' => 'lightbulb', 'title' => 'Smart Suggestions', 'desc' => 'Intelligent recommendations for skills, action verbs, and content improvements.', 'color' => 'pink'],
                        ['icon' => 'arrow-down-tray', 'title' => 'Export Ready', 'desc' => 'Download polished CVs in PDF format, ready to submit. Share via link or email.', 'color' => 'teal']
                    ] as $feature)
                    <div class="group bg-white dark:bg-zinc-950 p-8 lg:p-10 hover:bg-zinc-50 dark:hover:bg-zinc-900 transition-colors duration-300">
                        <div class="w-10 h-10 rounded-lg bg-{{ $feature['color'] }}-100 dark:bg-{{ $feature['color'] }}-900/30 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <x-ui::icon name="{{ $feature['icon'] }}" size="lg" class="text-{{ $feature['color'] }}-600 dark:text-{{ $feature['color'] }}-400" />
                        </div>
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-3">{{ $feature['title'] }}</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- How It Works: Numbered list --}}
        <section class="relative py-24 lg:py-32 bg-zinc-50 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                    <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Process</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-20">How it works.</h2>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
                    @foreach([
                        ['num' => '01', 'title' => 'Fill Your Info', 'desc' => 'Enter your work experience, education, skills, and certifications. Our guided form makes it easy.', 'color' => 'emerald'],
                        ['num' => '02', 'title' => 'AI Enhances', 'desc' => 'Our AI analyzes your content and optimizes it with stronger action verbs and ATS formatting.', 'color' => 'blue'],
                        ['num' => '03', 'title' => 'Download & Apply', 'desc' => 'Preview your CV, make final adjustments, then export as a clean PDF.', 'color' => 'purple']
                    ] as $item)
                    <div class="group">
                        <div class="text-7xl font-extrabold text-zinc-100 dark:text-zinc-800 mb-6 group-hover:text-{{ $item['color'] }}-200 dark:group-hover:text-{{ $item['color'] }}-900 transition-colors duration-500">{{ $item['num'] }}</div>
                        <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-3">{{ $item['title'] }}</h3>
                        <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Stats: Minimal bar --}}
        <section class="border-y border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 py-16">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach([
                        ['value' => '10,000+', 'label' => 'CVs Created'],
                        ['value' => '95%', 'label' => 'ATS Pass Rate'],
                        ['value' => '50+', 'label' => 'Templates'],
                        ['value' => '4.9/5', 'label' => 'User Rating']
                    ] as $stat)
                    <div class="group text-center">
                        <div class="text-4xl font-extrabold text-zinc-900 dark:text-white mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors duration-300">{{ $stat['value'] }}</div>
                        <div class="text-xs text-zinc-400 uppercase tracking-widest font-medium">{{ $stat['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- About: Two-column clean --}}
        <section class="relative py-24 lg:py-32 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                            <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">About</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-8 leading-tight">Empowering careers through technology.</h2>
                        <div class="space-y-6">
                            <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed">SeratyAI was founded in 2024 by a team of hiring managers, career coaches, and software engineers who saw a critical gap in the job application process.</p>
                            <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed">We built SeratyAI to level the playing field. Our AI analyzes job descriptions in real time and helps candidates present their experience in the most impactful way.</p>
                            <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed">Today, over <span class="text-emerald-600 dark:text-emerald-400 font-semibold">10,000 professionals</span> trust SeratyAI with a <span class="text-emerald-600 dark:text-emerald-400 font-semibold">95% ATS pass rate</span>.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach([
                            ['icon' => 'graduation-cap', 'title' => 'Industry Expertise', 'desc' => 'Data from real hiring managers.', 'color' => 'emerald'],
                            ['icon' => 'zap', 'title' => 'Lightning Fast', 'desc' => 'Polished CV in under 10 minutes.', 'color' => 'blue'],
                            ['icon' => 'heart', 'title' => 'User Focused', 'desc' => 'Designed around real feedback.', 'color' => 'purple'],
                            ['icon' => 'shield-check', 'title' => 'Privacy First', 'desc' => 'GDPR & SOC 2 compliant.', 'color' => 'amber']
                        ] as $f)
                        <div class="group p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 hover:-translate-y-1">
                            <x-ui::icon name="{{ $f['icon'] }}" size="lg" class="text-{{ $f['color'] }}-500 mb-4" />
                            <h3 class="text-sm font-bold text-zinc-900 dark:text-white mb-1">{{ $f['title'] }}</h3>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $f['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- Team: Clean grid --}}
        <section class="relative py-24 lg:py-32 bg-zinc-50 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                    <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Team</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-16">Our team.</h2>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach([
                        ['initials' => 'AJ', 'name' => 'Alex Johnson', 'role' => 'CEO & Co-Founder', 'desc' => '15 years in talent acquisition.', 'color' => 'emerald'],
                        ['initials' => 'MW', 'name' => 'Maria Williams', 'role' => 'CTO & Co-Founder', 'desc' => 'NLP & ML specialist.', 'color' => 'blue'],
                        ['initials' => 'DL', 'name' => 'David Lee', 'role' => 'Head of Product', 'desc' => 'Career platform veteran.', 'color' => 'purple'],
                        ['initials' => 'RN', 'name' => 'Rachel Nguyen', 'role' => 'Head of Design', 'desc' => 'Award-winning UX designer.', 'color' => 'amber']
                    ] as $m)
                    <div class="group">
                        <div class="w-16 h-16 rounded-xl bg-{{ $m['color'] }}-100 dark:bg-{{ $m['color'] }}-900/30 flex items-center justify-center mb-4 group-hover:bg-{{ $m['color'] }}-500 transition-colors duration-300">
                            <span class="text-lg font-bold text-{{ $m['color'] }}-600 dark:text-{{ $m['color'] }}-400 group-hover:text-white transition-colors duration-300">{{ $m['initials'] }}</span>
                        </div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white mb-0.5">{{ $m['name'] }}</h3>
                        <p class="text-xs text-{{ $m['color'] }}-600 dark:text-{{ $m['color'] }}-400 font-medium mb-1">{{ $m['role'] }}</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $m['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Testimonials: Quoted --}}
        <section class="relative py-24 lg:py-32 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                    <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Testimonials</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-16">What people say.</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach([
                        ['name' => 'Sarah Rodriguez', 'role' => 'Software Engineer at Google', 'initials' => 'SR', 'text' => 'SeratyAI completely transformed my job search. The AI suggestions made my experience descriptions so much stronger.', 'color' => 'emerald'],
                        ['name' => 'Marcus Kim', 'role' => 'Product Manager at Stripe', 'initials' => 'MK', 'text' => 'As a career changer, I struggled to present my transferable skills. SeratyAI\'s AI helped me reframe my experience effectively.', 'color' => 'blue'],
                        ['name' => 'Elena Petrova', 'role' => 'UX Designer at Figma', 'initials' => 'EP', 'text' => 'The templates are stunning and the real-time preview is incredibly useful. Recruiters frequently compliment my CV format.', 'color' => 'purple']
                    ] as $t)
                    <div class="group">
                        <div class="mb-6">
                            <div class="flex items-center gap-1 mb-4">
                                @for($i = 0; $i < 5; $i++)
                                <x-ui::icon name="star" size="sm" class="text-amber-400" />
                                @endfor
                            </div>
                            <p class="text-zinc-600 dark:text-zinc-300 leading-relaxed">"{{ $t['text'] }}"</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-{{ $t['color'] }}-100 dark:bg-{{ $t['color'] }}-900/30 flex items-center justify-center">
                                <span class="text-xs font-bold text-{{ $t['color'] }}-600 dark:text-{{ $t['color'] }}-400">{{ $t['initials'] }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $t['name'] }}</div>
                                <div class="text-xs text-zinc-400">{{ $t['role'] }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Pricing: Clean table-like --}}
        <section class="relative py-24 lg:py-32 bg-zinc-50 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                    <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Pricing</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-16">Simple pricing.</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach([
                        ['name' => 'Free', 'price' => '$0', 'period' => '/month', 'btn' => 'Get Started', 'features' => ['1 CV with basic template', 'PDF export with watermark', 'Basic ATS check', '5 AI suggestions per CV'], 'unavailable' => ['Unlimited CVs', 'AI cover letter generator'], 'popular' => false],
                        ['name' => 'Pro', 'price' => '$9', 'period' => '/month', 'btn' => 'Start Free Trial', 'features' => ['Unlimited CVs', 'All 50+ premium templates', 'Clean PDF export', 'Advanced ATS optimization', 'Unlimited AI suggestions', 'AI cover letter generator'], 'unavailable' => [], 'popular' => true],
                        ['name' => 'Enterprise', 'price' => '$29', 'period' => '/month', 'btn' => 'Contact Sales', 'features' => ['Everything in Pro', 'Team management', 'Custom branding', 'Priority support', 'API access', 'SSO & security'], 'unavailable' => [], 'popular' => false]
                    ] as $plan)
                    <div class="{{ $plan['popular'] ? 'bg-white dark:bg-zinc-950 ring-2 ring-emerald-500 shadow-xl' : 'bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800' }} rounded-xl p-8 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 relative">
                        @if($plan['popular'])
                        <div class="absolute -top-3 left-8">
                            <span class="bg-emerald-600 text-white text-xs font-bold px-3 py-1 rounded-full">Popular</span>
                        </div>
                        @endif
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2">{{ $plan['name'] }}</h3>
                            <span class="text-4xl font-extrabold text-zinc-900 dark:text-white">{{ $plan['price'] }}</span>
                            <span class="text-zinc-400 text-sm">{{ $plan['period'] }}</span>
                        </div>
                        <x-ui::button variant="{{ $plan['popular'] ? 'primary' : 'outline' }}" :href="route('register')" class="w-full mb-8 py-2.5">{{ $plan['btn'] }}</x-ui::button>
                        <ul class="space-y-3">
                            @foreach($plan['features'] as $f)
                            <li class="flex items-center gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full shrink-0"></div>
                                {{ $f }}
                            </li>
                            @endforeach
                            @foreach($plan['unavailable'] as $f)
                            <li class="flex items-center gap-3 text-sm text-zinc-300 dark:text-zinc-600 line-through">
                                <div class="w-1.5 h-1.5 bg-zinc-300 dark:bg-zinc-600 rounded-full shrink-0"></div>
                                {{ $f }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- FAQ: Minimal accordion --}}
        <section class="relative py-24 lg:py-32 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-3xl px-6 lg:px-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                    <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">FAQ</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-16">Common questions.</h2>

                <div class="divide-y divide-zinc-200 dark:divide-zinc-800" x-data="{ open: null }">
                    @foreach([
                        ['id' => 'd3q1', 'q' => 'What is SeratyAI?', 'a' => 'An AI-powered CV builder that creates professional, ATS-optimized resumes with real-time compatibility scoring.'],
                        ['id' => 'd3q2', 'q' => 'Is my data secure?', 'a' => 'Yes. All data encrypted at rest and in transit. We never sell or share your data. GDPR and SOC 2 compliant.'],
                        ['id' => 'd3q3', 'q' => 'Is there a free trial?', 'a' => 'Every new account gets a 7-day Pro trial. No credit card required.'],
                        ['id' => 'd3q4', 'q' => 'Can I cancel anytime?', 'a' => 'Yes. Cancel anytime from settings. No fees. Access continues until end of billing period.'],
                        ['id' => 'd3q5', 'q' => 'What export formats?', 'a' => 'High-quality PDF, the industry standard accepted by all ATS systems.'],
                        ['id' => 'd3q6', 'q' => 'Multiple CVs?', 'a' => 'Pro and Enterprise users create unlimited CVs tailored to different applications.']
                    ] as $faq)
                    <div class="py-5">
                        <button @click="open = open === '{{ $faq['id'] }}' ? null : '{{ $faq['id'] }}'" class="w-full flex items-center justify-between text-left group">
                            <span class="text-base font-medium text-zinc-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors pr-4">{{ $faq['q'] }}</span>
                            <div class="w-6 h-6 rounded-full border border-zinc-300 dark:border-zinc-600 flex items-center justify-center shrink-0 transition-all duration-300" :class="open === '{{ $faq['id'] }}' ? 'bg-emerald-500 border-emerald-500 rotate-45' : ''">
                                <div class="w-2.5 h-0.5 bg-zinc-400 transition-colors duration-300" :class="open === '{{ $faq['id'] }}' ? 'bg-white' : ''"></div>
                            </div>
                        </button>
                        <div x-show="open === '{{ $faq['id'] }}'" x-collapse x-cloak>
                            <p class="pt-4 text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Contact: Split layout --}}
        <section class="relative py-24 lg:py-32 bg-zinc-50 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                            <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">Contact</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-8">Get in touch.</h2>

                        <div class="space-y-6">
                            @foreach([
                                ['icon' => 'mail', 'title' => 'Email', 'value' => 'support@seratyai.com'],
                                ['icon' => 'phone', 'title' => 'Phone', 'value' => '+1 (555) 123-4567'],
                                ['icon' => 'map-pin', 'title' => 'Office', 'value' => '123 Innovation Drive, San Francisco, CA 94107']
                            ] as $info)
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-lg bg-zinc-200 dark:bg-zinc-800 flex items-center justify-center shrink-0">
                                    <x-ui::icon name="{{ $info['icon'] }}" size="md" class="text-zinc-500 dark:text-zinc-400" />
                                </div>
                                <div>
                                    <div class="text-xs text-zinc-400 uppercase tracking-wider mb-1">{{ $info['title'] }}</div>
                                    <div class="text-sm text-zinc-700 dark:text-zinc-300">{{ $info['value'] }}</div>
                                </div>
                            </div>
                            @endforeach

                            <div class="pt-4">
                                <div class="text-xs text-zinc-400 uppercase tracking-wider mb-3">Hours</div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between max-w-xs"><span class="text-zinc-500">Mon - Fri</span><span class="text-zinc-700 dark:text-zinc-300">9:00 AM - 6:00 PM</span></div>
                                    <div class="flex justify-between max-w-xs"><span class="text-zinc-500">Saturday</span><span class="text-zinc-700 dark:text-zinc-300">10:00 AM - 2:00 PM</span></div>
                                    <div class="flex justify-between max-w-xs"><span class="text-zinc-500">Sunday</span><span class="text-red-500">Closed</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="bg-white dark:bg-zinc-950 rounded-xl border border-zinc-200 dark:border-zinc-800 p-8">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-6">Send a message.</h3>
                            <livewire:contact-form />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA: Minimal --}}
        <section class="relative py-24 lg:py-32 bg-emerald-600">
            <div class="mx-auto max-w-4xl px-6 lg:px-8 text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to start?</h2>
                <p class="text-lg text-emerald-100 max-w-xl mx-auto mb-10">Join 10,000+ professionals who transformed their job search with SeratyAI.</p>
                <x-ui::button variant="primary" :href="route('register')" icon="arrow-right" class="bg-white text-emerald-700 hover:bg-emerald-50 shadow-lg hover:-translate-y-0.5 transition-all duration-300 px-10 py-3.5 text-base">
                    Create Your CV
                </x-ui::button>
            </div>
        </section>
    </main>
</x-layouts::landing>
