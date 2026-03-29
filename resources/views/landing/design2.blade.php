<x-layouts::landing>
    <main class="relative">
        {{-- Hero: Immersive full-bleed with giant typography --}}
        <section class="relative min-h-screen flex flex-col justify-center bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-transparent to-black/20"></div>
                <div class="absolute top-1/4 -left-20 w-[600px] h-[600px] bg-emerald-400 rounded-full blur-[120px] opacity-20 animate-pulse-glow"></div>
                <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-emerald-300 rounded-full blur-[100px] opacity-15 animate-pulse-glow" style="animation-delay: 3s;"></div>
            </div>

            <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(white 1px, transparent 1px), linear-gradient(90deg, white 1px, transparent 1px); background-size: 60px 60px;"></div>

            <div class="absolute top-24 right-1/4 w-3 h-3 bg-emerald-200 rounded-full animate-float opacity-50"></div>
            <div class="absolute bottom-1/3 left-1/4 w-4 h-4 bg-white rounded-full animate-float-reverse opacity-30" style="animation-delay: 1.5s;"></div>
            <div class="absolute top-1/2 right-16 w-2 h-2 bg-emerald-300 rounded-full animate-float-slow opacity-40" style="animation-delay: 3s;"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8 py-32 w-full">
                <div class="animate-slide-up text-center mb-12">
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white text-sm font-medium px-5 py-2 rounded-full mb-10 border border-white/15">
                        <x-ui::icon name="sparkles" size="sm" class="animate-pulse" />
                        The Future of Resume Building
                    </div>
                </div>

                <h1 class="animate-slide-up delay-100 text-center text-6xl md:text-7xl lg:text-[8rem] font-extrabold text-white leading-[0.9] tracking-tight mb-8">
                    Build<br>
                    <span class="text-emerald-200">Smarter</span><br>
                    Resumes.
                </h1>

                <p class="animate-slide-up delay-200 text-center text-xl md:text-2xl text-emerald-100/80 max-w-2xl mx-auto leading-relaxed mb-12">
                    AI-powered CV creation that lands interviews. Join 10,000+ professionals who trust SeratyAI.
                </p>

                <div class="animate-slide-up delay-300 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <x-ui::button variant="primary" :href="route('cv.builder')" icon="arrow-right" class="bg-white text-emerald-700 hover:bg-emerald-50 shadow-2xl shadow-black/20 w-full sm:w-auto hover:-translate-y-1 transition-all duration-300 text-base px-10 py-4">
                        Start Building Free
                    </x-ui::button>
                    <x-ui::button variant="ghost" class="text-white/80 hover:text-white hover:bg-white/10 border border-white/20 w-full sm:w-auto px-10 py-4 text-base">
                        Watch Demo
                    </x-ui::button>
                </div>
            </div>

            <div class="relative mx-auto max-w-5xl px-6 lg:px-8 pb-0 w-full">
                <div class="animate-slide-up delay-500 bg-white/10 backdrop-blur-md rounded-t-2xl border border-white/20 border-b-0 p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        <span class="ml-4 text-sm text-white/50 font-mono">seratyai.app/cv-builder</span>
                    </div>
                    <div class="grid grid-cols-3 gap-6">
                        <div class="col-span-2 space-y-3">
                            <div class="h-3 bg-white/15 rounded w-2/3"></div>
                            <div class="h-3 bg-white/10 rounded w-full"></div>
                            <div class="h-3 bg-white/10 rounded w-5/6"></div>
                            <div class="h-8 bg-emerald-500/30 rounded w-1/4 mt-4"></div>
                        </div>
                        <div class="space-y-3">
                            <div class="h-20 bg-white/10 rounded"></div>
                            <div class="h-3 bg-white/10 rounded w-full"></div>
                            <div class="h-3 bg-white/10 rounded w-3/4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Logo Wall / Trust Bar --}}
        <section class="bg-zinc-950 py-12 border-b border-zinc-800">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <p class="text-center text-xs uppercase tracking-widest text-zinc-500 mb-8">Trusted by professionals at</p>
                <div class="flex flex-wrap items-center justify-center gap-x-12 gap-y-6 text-zinc-600">
                    @foreach(['Google', 'Stripe', 'Figma', 'Meta', 'Amazon', 'Apple'] as $company)
                    <span class="text-lg font-bold tracking-tight opacity-40 hover:opacity-70 transition-opacity duration-300">{{ $company }}</span>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Features: Full-width alternating layout --}}
        <section id="features" class="relative bg-white dark:bg-zinc-950">
            @foreach([
                ['icon' => 'sparkles', 'title' => 'AI-Powered Writing', 'desc' => 'Generate compelling bullet points, summaries, and cover letters tailored to your industry and experience level using advanced AI models.', 'color' => 'emerald', 'detail' => 'Powered by state-of-the-art language models fine-tuned on successful resumes across 50+ industries.'],
                ['icon' => 'shield-check', 'title' => 'ATS Optimization', 'desc' => 'Ensure your CV passes Applicant Tracking Systems with keyword analysis, formatting checks, and real-time compatibility scoring.', 'color' => 'blue', 'detail' => '98% of Fortune 500 companies use ATS. Our engine ensures your CV gets through.'],
                ['icon' => 'eye', 'title' => 'Real-Time Preview', 'desc' => 'See exactly how your CV will look as you edit. Our live preview updates instantly so you can perfect every detail.', 'color' => 'purple', 'detail' => 'Pixel-perfect rendering that matches the final PDF export exactly.'],
            ] as $index => $feature)
            <div class="{{ $index % 2 === 0 ? 'bg-white dark:bg-zinc-950' : 'bg-zinc-50 dark:bg-zinc-900' }}">
                <div class="mx-auto max-w-7xl px-6 lg:px-8 py-20 lg:py-28">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center {{ $index % 2 !== 0 ? 'lg:flex-row-reverse' : '' }}">
                        <div class="{{ $index % 2 !== 0 ? 'lg:order-2' : '' }}">
                            <div class="inline-flex items-center gap-2 bg-{{ $feature['color'] }}-100 dark:bg-{{ $feature['color'] }}-900/30 text-{{ $feature['color'] }}-700 dark:text-{{ $feature['color'] }}-300 text-xs font-bold px-3 py-1.5 rounded-full mb-6 uppercase tracking-wider">
                                Feature {{ $index + 1 }}
                            </div>
                            <h3 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-6 leading-tight">{{ $feature['title'] }}</h3>
                            <p class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed mb-4">{{ $feature['desc'] }}</p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-500 leading-relaxed">{{ $feature['detail'] }}</p>
                        </div>
                        <div class="{{ $index % 2 !== 0 ? 'lg:order-1' : '' }}">
                            <div class="aspect-[4/3] rounded-2xl bg-gradient-to-br from-{{ $feature['color'] }}-100 to-{{ $feature['color'] }}-50 dark:from-{{ $feature['color'] }}-900/20 dark:to-{{ $feature['color'] }}-800/10 border border-{{ $feature['color'] }}-200 dark:border-{{ $feature['color'] }}-800 flex items-center justify-center group hover:shadow-2xl hover:shadow-{{ $feature['color'] }}-500/10 transition-all duration-500">
                                <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-{{ $feature['color'] }}-500 to-{{ $feature['color'] }}-600 flex items-center justify-center shadow-2xl shadow-{{ $feature['color'] }}-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                                    <x-ui::icon name="{{ $feature['icon'] }}" size="xl" class="text-white" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Remaining features as compact grid --}}
            <div class="bg-white dark:bg-zinc-950">
                <div class="mx-auto max-w-7xl px-6 lg:px-8 py-20">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach([
                            ['icon' => 'document-text', 'title' => 'Professional Templates', 'desc' => 'Over 50 designs crafted by hiring managers and career experts.', 'color' => 'purple'],
                            ['icon' => 'lightbulb', 'title' => 'Smart Suggestions', 'desc' => 'Intelligent recommendations for skills, action verbs, and improvements.', 'color' => 'pink'],
                            ['icon' => 'arrow-down-tray', 'title' => 'Export Ready', 'desc' => 'Download polished CVs in PDF format. Share via link or email.', 'color' => 'teal']
                        ] as $feature)
                        <x-ui::card class="group hover:shadow-2xl hover:shadow-{{ $feature['color'] }}-500/10 transition-all duration-500 hover:-translate-y-2 border hover:border-{{ $feature['color'] }}-300 dark:hover:border-{{ $feature['color'] }}-700">
                            <div class="p-8">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-{{ $feature['color'] }}-500 to-{{ $feature['color'] }}-600 flex items-center justify-center mb-5 shadow-lg shadow-{{ $feature['color'] }}-500/20 group-hover:scale-110 transition-transform duration-300">
                                    <x-ui::icon name="{{ $feature['icon'] }}" size="lg" class="text-white" />
                                </div>
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-2">{{ $feature['title'] }}</h3>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $feature['desc'] }}</p>
                            </div>
                        </x-ui::card>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- Stats: Editorial counter --}}
        <section class="bg-zinc-950 py-20 lg:py-28">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-12">
                    @foreach([
                        ['value' => '10,000+', 'label' => 'CVs Created', 'color' => 'emerald'],
                        ['value' => '95%', 'label' => 'ATS Pass Rate', 'color' => 'blue'],
                        ['value' => '50+', 'label' => 'Templates', 'color' => 'purple'],
                        ['value' => '4.9/5', 'label' => 'User Rating', 'color' => 'amber']
                    ] as $stat)
                    <div class="group text-center">
                        <div class="text-5xl md:text-6xl font-extrabold text-{{ $stat['color'] }}-400 mb-3 group-hover:scale-110 transition-transform duration-300">{{ $stat['value'] }}</div>
                        <div class="text-sm text-zinc-500 uppercase tracking-widest font-medium">{{ $stat['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- How It Works --}}
        <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950 overflow-hidden">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-20">
                    <p class="text-sm uppercase tracking-widest text-emerald-600 dark:text-emerald-400 font-bold mb-4">Process</p>
                    <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white">How It Works</h2>
                </div>

                <div class="relative">
                    <div class="hidden lg:block absolute top-1/2 left-0 right-0 h-0.5 bg-gradient-to-r from-emerald-500 via-blue-500 to-purple-500 -translate-y-1/2"></div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-8">
                        @foreach([
                            ['step' => '01', 'title' => 'Fill Your Info', 'desc' => 'Enter your work experience, education, skills, and certifications with our guided form.', 'color' => 'emerald'],
                            ['step' => '02', 'title' => 'AI Enhances', 'desc' => 'Our AI optimizes your content for impact with stronger action verbs and ATS-friendly formatting.', 'color' => 'blue'],
                            ['step' => '03', 'title' => 'Download & Apply', 'desc' => 'Export a polished PDF and start applying with confidence.', 'color' => 'purple']
                        ] as $item)
                        <div class="relative text-center group">
                            <div class="relative z-10 w-20 h-20 rounded-full bg-gradient-to-br from-{{ $item['color'] }}-500 to-{{ $item['color'] }}-600 flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-{{ $item['color'] }}-500/30 group-hover:scale-110 transition-transform duration-300">
                                <span class="text-2xl font-extrabold text-white">{{ $item['step'] }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-3">{{ $item['title'] }}</h3>
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed max-w-xs mx-auto">{{ $item['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- About: Full-width editorial --}}
        <section class="relative py-20 lg:py-28 bg-emerald-600 overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-400 rounded-full blur-[100px] opacity-20"></div>

            <div class="relative mx-auto max-w-5xl px-6 lg:px-8 text-center">
                <p class="text-sm uppercase tracking-widest text-emerald-200 font-bold mb-6">Our Story</p>
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-8 leading-tight">Empowering Careers<br>Through Technology</h2>
                <p class="text-lg text-emerald-100/90 leading-relaxed mb-6 max-w-3xl mx-auto">SeratyAI was founded in 2024 by a team of hiring managers, career coaches, and software engineers who saw a critical gap in the job application process. Millions of talented professionals were being filtered out by ATS systems.</p>
                <p class="text-lg text-emerald-100/90 leading-relaxed max-w-3xl mx-auto">Today, over <span class="font-bold text-white">10,000 professionals</span> trust SeratyAI with a <span class="font-bold text-white">95% ATS pass rate</span>. We're just getting started.</p>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mt-16">
                    @foreach([
                        ['icon' => 'graduation-cap', 'title' => 'Expertise', 'color' => 'white'],
                        ['icon' => 'zap', 'title' => 'Speed', 'color' => 'emerald-200'],
                        ['icon' => 'heart', 'title' => 'User Focused', 'color' => 'white'],
                        ['icon' => 'shield-check', 'title' => 'Privacy', 'color' => 'emerald-200']
                    ] as $item)
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/15 hover:bg-white/20 transition-all duration-300 hover:-translate-y-1">
                        <x-ui::icon name="{{ $item['icon'] }}" size="lg" class="text-{{ $item['color'] }} mb-3" />
                        <div class="text-sm font-bold text-white">{{ $item['title'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Team --}}
        <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <p class="text-sm uppercase tracking-widest text-emerald-600 dark:text-emerald-400 font-bold mb-4">Team</p>
                    <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white">The People Behind SeratyAI</h2>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach([
                        ['initials' => 'AJ', 'name' => 'Alex Johnson', 'role' => 'CEO & Co-Founder', 'desc' => 'Former VP of Talent Acquisition, 15 years experience.', 'color' => 'emerald'],
                        ['initials' => 'MW', 'name' => 'Maria Williams', 'role' => 'CTO & Co-Founder', 'desc' => 'Full-stack engineer, NLP & ML specialist.', 'color' => 'blue'],
                        ['initials' => 'DL', 'name' => 'David Lee', 'role' => 'Head of Product', 'desc' => 'Product leader, career platforms.', 'color' => 'purple'],
                        ['initials' => 'RN', 'name' => 'Rachel Nguyen', 'role' => 'Head of Design', 'desc' => 'Award-winning UX designer.', 'color' => 'amber']
                    ] as $member)
                    <div class="group text-center">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-{{ $member['color'] }}-500 to-{{ $member['color'] }}-600 flex items-center justify-center mx-auto mb-5 shadow-xl shadow-{{ $member['color'] }}-500/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                            <span class="text-2xl font-bold text-white">{{ $member['initials'] }}</span>
                        </div>
                        <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-1">{{ $member['name'] }}</h3>
                        <p class="text-xs font-bold text-{{ $member['color'] }}-600 dark:text-{{ $member['color'] }}-400 mb-2">{{ $member['role'] }}</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $member['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Testimonials --}}
        <section class="relative py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-900 overflow-hidden">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <p class="text-sm uppercase tracking-widest text-amber-600 dark:text-amber-400 font-bold mb-4">Testimonials</p>
                    <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white">What People Say</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach([
                        ['name' => 'Sarah Rodriguez', 'role' => 'Software Engineer at Google', 'initials' => 'SR', 'color' => 'emerald', 'text' => 'SeratyAI completely transformed my job search. The AI suggestions made my experience descriptions so much stronger, and I started getting interview calls within a week.'],
                        ['name' => 'Marcus Kim', 'role' => 'Product Manager at Stripe', 'initials' => 'MK', 'color' => 'blue', 'text' => 'As a career changer, I struggled to present my transferable skills. SeratyAI\'s AI helped me reframe my experience and within two months I landed a product management role.'],
                        ['name' => 'Elena Petrova', 'role' => 'UX Designer at Figma', 'initials' => 'EP', 'color' => 'purple', 'text' => 'The templates are stunning and the real-time preview is incredibly useful. I customized a design that perfectly matched my personal brand.']
                    ] as $t)
                    <div class="group bg-white dark:bg-zinc-800 rounded-2xl p-8 shadow-sm border border-zinc-200 dark:border-zinc-700 hover:shadow-2xl hover:border-{{ $t['color'] }}-300 dark:hover:border-{{ $t['color'] }}-700 transition-all duration-500 hover:-translate-y-2">
                        <div class="flex items-center gap-1 mb-6">
                            @for($i = 0; $i < 5; $i++)
                            <x-ui::icon name="star" size="sm" class="text-amber-400" />
                            @endfor
                        </div>
                        <p class="text-zinc-600 dark:text-zinc-300 leading-relaxed mb-8 italic">"{{ $t['text'] }}"</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-{{ $t['color'] }}-500 to-{{ $t['color'] }}-600 flex items-center justify-center shadow-lg">
                                <span class="text-sm font-bold text-white">{{ $t['initials'] }}</span>
                            </div>
                            <div>
                                <div class="font-bold text-zinc-900 dark:text-white text-sm">{{ $t['name'] }}</div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $t['role'] }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Pricing --}}
        <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <p class="text-sm uppercase tracking-widest text-purple-600 dark:text-purple-400 font-bold mb-4">Pricing</p>
                    <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white mb-4">Simple Pricing</h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">Start free, upgrade when you need more.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach([
                        ['name' => 'Free', 'price' => '$0', 'period' => '/month', 'btn' => 'Get Started', 'features' => ['1 CV with basic template', 'PDF export with watermark', 'Basic ATS check', '5 AI suggestions per CV'], 'unavailable' => ['Unlimited CVs', 'AI cover letter generator'], 'popular' => false],
                        ['name' => 'Pro', 'price' => '$9', 'period' => '/month', 'btn' => 'Start Free Trial', 'features' => ['Unlimited CVs', 'All 50+ premium templates', 'Clean PDF export', 'Advanced ATS optimization', 'Unlimited AI suggestions', 'AI cover letter generator'], 'unavailable' => [], 'popular' => true],
                        ['name' => 'Enterprise', 'price' => '$29', 'period' => '/month', 'btn' => 'Contact Sales', 'features' => ['Everything in Pro', 'Team management', 'Custom branding', 'Priority support', 'API access', 'SSO & security'], 'unavailable' => [], 'popular' => false]
                    ] as $plan)
                    <x-ui::card class="{{ $plan['popular'] ? 'ring-2 ring-emerald-500 relative shadow-2xl shadow-emerald-500/20' : '' }} hover:shadow-xl transition-all duration-500 hover:-translate-y-2">
                        @if($plan['popular'])
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                            <span class="bg-emerald-600 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg">Most Popular</span>
                        </div>
                        @endif
                        <div class="p-8 flex flex-col flex-1">
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-1">{{ $plan['name'] }}</h3>
                            <div class="mt-4 mb-8">
                                <span class="text-5xl font-extrabold text-zinc-900 dark:text-white">{{ $plan['price'] }}</span>
                                <span class="text-zinc-500">{{ $plan['period'] }}</span>
                            </div>
                            <x-ui::button variant="{{ $plan['popular'] ? 'primary' : 'outline' }}" :href="route('register')" class="w-full mb-8 py-3">{{ $plan['btn'] }}</x-ui::button>
                            <ul class="space-y-3 mt-auto">
                                @foreach($plan['features'] as $f)
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check-circle" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    {{ $f }}
                                </li>
                                @endforeach
                                @foreach($plan['unavailable'] as $f)
                                <li class="flex items-start gap-3 text-sm text-zinc-400 dark:text-zinc-500 line-through">
                                    <x-ui::icon name="x-circle" size="md" class="text-zinc-300 dark:text-zinc-600 shrink-0 mt-0.5" />
                                    {{ $f }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </x-ui::card>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- FAQ --}}
        <section class="relative py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-900">
            <div class="mx-auto max-w-3xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <p class="text-sm uppercase tracking-widest text-emerald-600 dark:text-emerald-400 font-bold mb-4">FAQ</p>
                    <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white">Common Questions</h2>
                </div>

                <div class="space-y-4" x-data="{ open: null }">
                    @foreach([
                        ['id' => 'd2q1', 'q' => 'What is SeratyAI?', 'a' => 'An AI-powered CV builder that helps you create professional, ATS-optimized resumes with real-time compatibility scoring.'],
                        ['id' => 'd2q2', 'q' => 'Is my data secure?', 'a' => 'Yes. All data is encrypted at rest and in transit. We never sell or share your data. GDPR and SOC 2 compliant.'],
                        ['id' => 'd2q3', 'q' => 'Is there a free trial?', 'a' => 'Every new account gets a 7-day Pro trial. No credit card required.'],
                        ['id' => 'd2q4', 'q' => 'Can I cancel anytime?', 'a' => 'Yes, cancel anytime from settings. No fees. Access continues until end of billing period.'],
                        ['id' => 'd2q5', 'q' => 'What export formats are available?', 'a' => 'High-quality PDF, the industry standard accepted by all ATS systems and hiring managers.'],
                        ['id' => 'd2q6', 'q' => 'Can I create multiple CVs?', 'a' => 'Pro and Enterprise users can create unlimited CVs tailored to different applications.']
                    ] as $faq)
                    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300">
                        <button @click="open = open === '{{ $faq['id'] }}' ? null : '{{ $faq['id'] }}'" class="w-full flex items-center justify-between px-6 py-5 text-left">
                            <span class="font-semibold text-zinc-900 dark:text-white pr-4">{{ $faq['q'] }}</span>
                            <x-ui::icon name="chevron-down" size="md" class="text-zinc-400 shrink-0 transition-transform duration-300" x-bind:class="open === '{{ $faq['id'] }}' ? 'rotate-180' : ''" />
                        </button>
                        <div x-show="open === '{{ $faq['id'] }}'" x-collapse x-cloak>
                            <div class="px-6 pb-5 text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $faq['a'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Contact --}}
        <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <p class="text-sm uppercase tracking-widest text-blue-600 dark:text-blue-400 font-bold mb-4">Contact</p>
                    <h2 class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white mb-4">Get in Touch</h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">We typically respond within 24 hours.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                    <div class="lg:col-span-3">
                        <x-ui::card class="p-8 border-2 border-emerald-200 dark:border-emerald-800">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                    <x-ui::icon name="send" size="md" class="text-white" />
                                </div>
                                <h3 class="text-xl font-bold text-zinc-900 dark:text-white">Send Us a Message</h3>
                            </div>
                            <livewire:contact-form />
                        </x-ui::card>
                    </div>
                    <div class="lg:col-span-2 space-y-4">
                        @foreach([
                            ['icon' => 'mail', 'title' => 'Email', 'value' => 'support@seratyai.com', 'sub' => 'Response within 24h', 'color' => 'emerald'],
                            ['icon' => 'phone', 'title' => 'Phone', 'value' => '+1 (555) 123-4567', 'sub' => 'Mon-Fri, 9AM-6PM PST', 'color' => 'blue'],
                            ['icon' => 'map-pin', 'title' => 'Office', 'value' => '123 Innovation Drive, San Francisco, CA 94107', 'sub' => 'By appointment only', 'color' => 'purple']
                        ] as $info)
                        <x-ui::card class="group hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                            <div class="p-5 flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-{{ $info['color'] }}-500 to-{{ $info['color'] }}-600 flex items-center justify-center shrink-0 shadow-lg shadow-{{ $info['color'] }}-500/20 group-hover:scale-110 transition-transform duration-300">
                                    <x-ui::icon name="{{ $info['icon'] }}" size="md" class="text-white" />
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $info['title'] }}</div>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $info['value'] }}</p>
                                    <p class="text-xs text-zinc-500 mt-1">{{ $info['sub'] }}</p>
                                </div>
                            </div>
                        </x-ui::card>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="relative overflow-hidden bg-zinc-950 py-24 lg:py-32">
            <div class="absolute inset-0">
                <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-emerald-600 rounded-full blur-[120px] opacity-20 animate-pulse-glow"></div>
                <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-emerald-500 rounded-full blur-[100px] opacity-15 animate-pulse-glow" style="animation-delay: 3s;"></div>
            </div>
            <div class="relative mx-auto max-w-4xl px-6 lg:px-8 text-center">
                <h2 class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight">Ready to Build<br>Your Future?</h2>
                <p class="text-xl text-zinc-400 max-w-2xl mx-auto mb-12 leading-relaxed">Your next career move starts with a great CV. Join 10,000+ professionals today.</p>
                <x-ui::button variant="primary" :href="route('register')" icon="arrow-right" size="lg" class="bg-emerald-600 hover:bg-emerald-500 text-white shadow-2xl shadow-emerald-600/30 hover:-translate-y-1 transition-all duration-300 px-12 py-4 text-lg">
                    Create Your CV Now
                </x-ui::button>
            </div>
        </section>
    </main>
</x-layouts::landing>
