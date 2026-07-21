<x-layouts::landing>
    <main class="relative">
        {{-- Hero: Centered with curved bottom --}}
        <section class="relative bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 overflow-hidden">
            <div class="absolute inset-0 opacity-[0.04]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 30px 30px;"></div>
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-400 rounded-full blur-[150px] opacity-15 animate-pulse-glow"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-400 rounded-full blur-[130px] opacity-10 animate-pulse-glow" style="animation-delay: 3s;"></div>

            <div class="absolute top-16 left-1/4 w-3 h-3 bg-white rounded-full animate-float opacity-40"></div>
            <div class="absolute bottom-24 right-1/3 w-2 h-2 bg-emerald-200 rounded-full animate-float-reverse opacity-30" style="animation-delay: 1.5s;"></div>
            <div class="absolute top-1/2 right-20 w-4 h-4 bg-emerald-300 rounded-full animate-float-slow opacity-25" style="animation-delay: 0.8s;"></div>

            <div class="relative mx-auto max-w-4xl px-6 lg:px-8 py-28 lg:py-40 text-center">
                <div class="animate-slide-up inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm text-white text-sm font-semibold px-5 py-2.5 rounded-full mb-8 border border-white/20">
                    <x-ui::icon name="sparkles" size="sm" class="animate-pulse" />
                    {{ __('AI-Powered Resume Building') }}
                </div>

                <h1 class="animate-slide-up delay-100 text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                    {{ __('Your Career Journey') }}<br>{{ __('Starts with a Great CV') }}
                </h1>

                <p class="animate-slide-up delay-200 text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto leading-relaxed mb-10">
                    {{ __('Create professional, ATS-optimized resumes in minutes. Our AI tailors your CV to maximize your chances of landing interviews.') }}
                </p>

                <div class="animate-slide-up delay-300 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <x-ui::button variant="primary" :href="route('cv.builder')" icon="arrow-right" class="bg-white text-emerald-700 hover:bg-emerald-50 shadow-2xl shadow-emerald-900/30 hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto px-8 py-3 text-base">
                        {{ __('Get Started Free') }}
                    </x-ui::button>
                    <x-ui::button variant="ghost" href="#journey" class="text-white hover:bg-white/15 border-2 border-white/30 w-full sm:w-auto px-8 py-3 text-base">
                        {{ __('Explore the Journey') }}
                    </x-ui::button>
                </div>
            </div>

            {{-- Curved bottom --}}
            <div class="relative">
                <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                    <path d="M0 80V40C240 0 480 0 720 20C960 40 1200 60 1440 40V80H0Z" class="fill-white dark:fill-zinc-950"/>
                </svg>
            </div>
        </section>

        {{-- Journey Timeline --}}
        <div id="journey" class="relative bg-white dark:bg-zinc-950">
            {{-- Central vertical line --}}
            <div class="absolute left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-emerald-500 via-blue-500 via-purple-500 to-emerald-500 hidden lg:block"></div>

            {{-- Section 1: Features --}}
            <section class="relative py-20 lg:py-28">
                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    {{-- Timeline marker --}}
                    <div class="hidden lg:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-2xl shadow-emerald-500/40 ring-4 ring-white dark:ring-zinc-950">
                            <x-ui::icon name="sparkles" size="md" class="text-white" />
                        </div>
                    </div>

                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <div class="inline-flex items-center gap-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                            <x-ui::icon name="sparkles" size="sm" />
                            {{ __('Features') }}
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">{{ __('Everything You Need to Land Your Dream Job') }}</h2>
                        <p class="text-lg text-zinc-600 dark:text-zinc-400">{{ __('Our comprehensive toolkit for professional resumes.') }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach([
                            ['icon' => 'sparkles', 'title' => __('AI-Powered Writing'), 'desc' => __('Generate compelling bullet points, summaries, and cover letters tailored to your industry.'), 'color' => 'emerald'],
                            ['icon' => 'shield-check', 'title' => __('ATS Optimization'), 'desc' => __('Pass Applicant Tracking Systems with keyword analysis and real-time scoring.'), 'color' => 'blue'],
                            ['icon' => 'document-text', 'title' => __('Professional Templates'), 'desc' => __('Over 50 designs crafted by hiring managers and career experts.'), 'color' => 'purple'],
                            ['icon' => 'eye', 'title' => __('Real-Time Preview'), 'desc' => __('See how your CV will look as you edit with instant live updates.'), 'color' => 'amber'],
                            ['icon' => 'lightbulb', 'title' => __('Smart Suggestions'), 'desc' => __('Intelligent recommendations for skills, action verbs, and improvements.'), 'color' => 'pink'],
                            ['icon' => 'arrow-down-tray', 'title' => __('Export Ready'), 'desc' => __('Download polished CVs in PDF format, ready to submit.'), 'color' => 'teal']
                        ] as $feature)
                        <x-ui::card class="group hover:shadow-2xl hover:shadow-{{ $feature['color'] }}-500/20 transition-all duration-500 hover:-translate-y-2 border-2 hover:border-{{ $feature['color'] }}-300 dark:hover:border-{{ $feature['color'] }}-700">
                            <div class="p-6">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-{{ $feature['color'] }}-500 to-{{ $feature['color'] }}-600 flex items-center justify-center mb-5 shadow-lg shadow-{{ $feature['color'] }}-500/20 group-hover:scale-110 transition-transform duration-300">
                                    <x-ui::icon name="{{ $feature['icon'] }}" size="lg" class="text-white" />
                                </div>
                                <h3 class="text-base font-bold text-zinc-900 dark:text-white mb-2">{{ $feature['title'] }}</h3>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $feature['desc'] }}</p>
                            </div>
                        </x-ui::card>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Section 2: How It Works - Alternating sides --}}
            <section class="relative py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-900">
                <div class="hidden lg:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-2xl shadow-blue-500/40 ring-4 ring-zinc-50 dark:ring-zinc-900">
                        <x-ui::icon name="zap" size="md" class="text-white" />
                    </div>
                </div>

                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <div class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                            <x-ui::icon name="zap" size="sm" />
                            {{ __('How It Works') }}
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">{{ __('Three Simple Steps') }}</h2>
                    </div>

                    <div class="space-y-12 lg:space-y-0 lg:grid lg:grid-cols-3 lg:gap-8">
                        @foreach([
                            ['step' => 1, 'title' => __('Fill Your Info'), 'desc' => __('Enter your work experience, education, skills, and certifications with our guided form.'), 'icon' => 'pencil', 'color' => 'emerald'],
                            ['step' => 2, 'title' => __('AI Enhances'), 'desc' => __('Our AI optimizes your content with stronger action verbs and ATS-friendly formatting.'), 'icon' => 'sparkles', 'color' => 'blue'],
                            ['step' => 3, 'title' => __('Download & Apply'), 'desc' => __('Export a polished PDF ready to send to employers.'), 'icon' => 'arrow-down-tray', 'color' => 'purple']
                        ] as $item)
                        <div class="group relative">
                            {{-- Connector line --}}
                            @if($item['step'] < 3)
                            <div class="hidden lg:block absolute top-12 left-[60%] w-[calc(80%)] h-0.5 bg-gradient-to-r from-{{ $item['color'] }}-400 to-{{ $item['color'] }}-200 dark:to-{{ $item['color'] }}-800 z-0"></div>
                            @endif

                            <div class="relative z-10 bg-white dark:bg-zinc-800 rounded-2xl p-8 shadow-lg border border-zinc-200 dark:border-zinc-700 group-hover:shadow-2xl group-hover:shadow-{{ $item['color'] }}-500/10 group-hover:border-{{ $item['color'] }}-300 dark:group-hover:border-{{ $item['color'] }}-700 transition-all duration-500 group-hover:-translate-y-2 text-center">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-{{ $item['color'] }}-500 to-{{ $item['color'] }}-600 flex items-center justify-center mx-auto mb-6 shadow-xl shadow-{{ $item['color'] }}-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                    <span class="text-2xl font-bold text-white">{{ $item['step'] }}</span>
                                </div>
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-3">{{ $item['title'] }}</h3>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $item['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Section 3: Stats --}}
            <section class="relative bg-gradient-to-r from-emerald-600 via-emerald-700 to-emerald-800">
                <div class="hidden lg:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-2xl ring-4 ring-emerald-600">
                        <x-ui::icon name="trophy" size="md" class="text-emerald-600" />
                    </div>
                </div>

                <div class="mx-auto max-w-7xl px-6 lg:px-8 py-16 lg:py-20">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach([
                            ['value' => '10,000+', 'label' => __('CVs Created')],
                            ['value' => '95%', 'label' => __('ATS Pass Rate')],
                            ['value' => '50+', 'label' => __('Templates')],
                            ['value' => '4.9/5', 'label' => __('User Rating')]
                        ] as $stat)
                        <div class="group text-center">
                            <div class="text-4xl md:text-5xl font-extrabold text-white mb-2 group-hover:scale-110 transition-transform duration-300">{{ $stat['value'] }}</div>
                            <div class="text-emerald-200 text-sm font-medium">{{ $stat['label'] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Section 4: About --}}
            <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950">
                <div class="hidden lg:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-2xl shadow-emerald-500/40 ring-4 ring-white dark:ring-zinc-950">
                        <x-ui::icon name="heart" size="md" class="text-white" />
                    </div>
                </div>

                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                        <div>
                            <div class="inline-flex items-center gap-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                                <x-ui::icon name="heart" size="sm" />
                                {{ __('Our Mission') }}
                            </div>
                            <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-6">{{ __('Empowering Careers Through Technology') }}</h2>
                            <div class="space-y-4">
                                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ __('SeratyAI was founded in 2024 by a team of hiring managers, career coaches, and software engineers who saw a critical gap in the job application process.') }}</p>
                                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ __('We built SeratyAI to level the playing field. Our AI analyzes job descriptions in real time and helps candidates present their experience in the most impactful way.') }}</p>
                                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ __('Over') }} <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ __('10,000 professionals') }}</span> {{ __('trust SeratyAI with a') }} <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ __('95% ATS pass rate') }}</span>.</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach([
                                ['icon' => 'graduation-cap', 'title' => __('Industry Expertise'), 'desc' => __('Data from real hiring managers.'), 'color' => 'emerald'],
                                ['icon' => 'zap', 'title' => __('Lightning Fast'), 'desc' => __('CV in under 10 minutes.'), 'color' => 'blue'],
                                ['icon' => 'heart', 'title' => __('User Focused'), 'desc' => __('Designed around feedback.'), 'color' => 'purple'],
                                ['icon' => 'shield-check', 'title' => __('Privacy First'), 'desc' => __('GDPR & SOC 2 compliant.'), 'color' => 'amber']
                            ] as $f)
                            <x-ui::card class="group hover:shadow-lg transition-all duration-300 hover:-translate-y-1 hover:border-{{ $f['color'] }}-300 dark:hover:border-{{ $f['color'] }}-700">
                                <div class="p-5">
                                    <div class="w-10 h-10 rounded-lg bg-{{ $f['color'] }}-100 dark:bg-{{ $f['color'] }}-900/30 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                                        <x-ui::icon name="{{ $f['icon'] }}" size="md" class="text-{{ $f['color'] }}-600 dark:text-{{ $f['color'] }}-400" />
                                    </div>
                                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white mb-1">{{ $f['title'] }}</h3>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $f['desc'] }}</p>
                                </div>
                            </x-ui::card>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            {{-- Section 5: Team --}}
            <section class="relative py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-900">
                <div class="hidden lg:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-2xl shadow-purple-500/40 ring-4 ring-zinc-50 dark:ring-zinc-900">
                        <x-ui::icon name="users" size="md" class="text-white" />
                    </div>
                </div>

                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <div class="inline-flex items-center gap-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                            <x-ui::icon name="users" size="sm" />
                            {{ __('Team') }}
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">{{ __('Meet the People Behind SeratyAI') }}</h2>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach([
                            ['initials' => 'AJ', 'name' => 'Alex Johnson', 'role' => __('CEO & Co-Founder'), 'desc' => __('15 years talent acquisition.'), 'color' => 'emerald'],
                            ['initials' => 'MW', 'name' => 'Maria Williams', 'role' => __('CTO & Co-Founder'), 'desc' => __('NLP & ML specialist.'), 'color' => 'blue'],
                            ['initials' => 'DL', 'name' => 'David Lee', 'role' => __('Head of Product'), 'desc' => __('Career platform veteran.'), 'color' => 'purple'],
                            ['initials' => 'RN', 'name' => 'Rachel Nguyen', 'role' => __('Head of Design'), 'desc' => __('Award-winning UX.'), 'color' => 'amber']
                        ] as $m)
                        <div class="group text-center">
                            <div class="relative mb-5">
                                <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-{{ $m['color'] }}-500 to-{{ $m['color'] }}-600 flex items-center justify-center shadow-xl shadow-{{ $m['color'] }}-500/20 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                    <span class="text-2xl font-bold text-white">{{ $m['initials'] }}</span>
                                </div>
                                <div class="absolute inset-0 rounded-full bg-gradient-to-br from-{{ $m['color'] }}-500 to-{{ $m['color'] }}-600 blur-xl opacity-20 group-hover:opacity-40 transition-opacity duration-300 animate-pulse"></div>
                            </div>
                            <h3 class="text-sm font-bold text-zinc-900 dark:text-white">{{ $m['name'] }}</h3>
                            <p class="text-xs font-semibold text-{{ $m['color'] }}-600 dark:text-{{ $m['color'] }}-400 mb-1">{{ $m['role'] }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $m['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Section 6: Testimonials --}}
            <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950">
                <div class="hidden lg:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-2xl shadow-amber-500/40 ring-4 ring-white dark:ring-zinc-950">
                        <x-ui::icon name="chat-bubble-left-right" size="md" class="text-white" />
                    </div>
                </div>

                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <div class="inline-flex items-center gap-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                            <x-ui::icon name="chat-bubble-left-right" size="sm" />
                            {{ __('Testimonials') }}
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">{{ __('Trusted by Professionals Worldwide') }}</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach([
                            ['name' => 'Sarah Rodriguez', 'role' => __('Software Engineer at Google'), 'initials' => 'SR', 'color' => 'emerald', 'text' => __('SeratyAI completely transformed my job search. The AI suggestions made my experience descriptions so much stronger, and I started getting interview calls within a week.')],
                            ['name' => 'Marcus Kim', 'role' => __('Product Manager at Stripe'), 'initials' => 'MK', 'color' => 'blue', 'text' => __("As a career changer, I struggled to present my transferable skills. SeratyAI's AI helped me reframe my experience and within two months I landed a product management role.")],
                            ['name' => 'Elena Petrova', 'role' => __('UX Designer at Figma'), 'initials' => 'EP', 'color' => 'purple', 'text' => __('The templates are stunning and the real-time preview is incredibly useful. Recruiters frequently compliment my CV format.')]
                        ] as $t)
                        <x-ui::card class="group hover:shadow-2xl hover:shadow-{{ $t['color'] }}-500/20 transition-all duration-500 hover:-translate-y-2 border-2 hover:border-{{ $t['color'] }}-300 dark:hover:border-{{ $t['color'] }}-700">
                            <div class="p-6">
                                <div class="flex items-center gap-1 mb-5">
                                    @for($i = 0; $i < 5; $i++)
                                    <x-ui::icon name="star" size="sm" class="text-amber-400" />
                                    @endfor
                                </div>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6">"{{ $t['text'] }}"</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-{{ $t['color'] }}-500 to-{{ $t['color'] }}-600 flex items-center justify-center shadow-lg">
                                        <span class="text-xs font-bold text-white">{{ $t['initials'] }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-zinc-900 dark:text-white">{{ $t['name'] }}</div>
                                        <div class="text-xs text-zinc-500">{{ $t['role'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </x-ui::card>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Section 7: Pricing --}}
            <section class="relative py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-900">
                <div class="hidden lg:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-2xl shadow-emerald-500/40 ring-4 ring-zinc-50 dark:ring-zinc-900">
                        <x-ui::icon name="briefcase" size="md" class="text-white" />
                    </div>
                </div>

                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <div class="inline-flex items-center gap-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                            <x-ui::icon name="briefcase" size="sm" />
                            {{ __('Pricing') }}
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">{{ __('Simple, Transparent Pricing') }}</h2>
                        <p class="text-lg text-zinc-600 dark:text-zinc-400">{{ __('Start free, upgrade when you need more.') }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach([
                            ['name' => __('Free'), 'price' => '$0', 'period' => __('/month'), 'btn' => __('Get Started'), 'features' => [__('1 CV with basic template'), __('PDF export with watermark'), __('Basic ATS check'), __('5 AI suggestions per CV')], 'unavailable' => [__('Unlimited CVs'), __('AI cover letter generator')], 'popular' => false],
                            ['name' => __('Pro'), 'price' => '$9', 'period' => __('/month'), 'btn' => __('Start Free Trial'), 'features' => [__('Unlimited CVs'), __('All 50+ premium templates'), __('Clean PDF export'), __('Advanced ATS optimization'), __('Unlimited AI suggestions'), __('AI cover letter generator')], 'unavailable' => [], 'popular' => true],
                            ['name' => __('Enterprise'), 'price' => '$29', 'period' => __('/month'), 'btn' => __('Contact Sales'), 'features' => [__('Everything in Pro'), __('Team management'), __('Custom branding'), __('Priority support'), __('API access'), __('SSO & security')], 'unavailable' => [], 'popular' => false]
                        ] as $plan)
                        <x-ui::card class="{{ $plan['popular'] ? 'ring-2 ring-emerald-500 relative scale-[1.02] shadow-2xl shadow-emerald-500/20' : '' }} hover:shadow-xl transition-all duration-500 hover:-translate-y-2">
                            @if($plan['popular'])
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                                <span class="bg-emerald-600 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg">{{ __('Most Popular') }}</span>
                            </div>
                            @endif
                            <div class="p-6 lg:p-8 flex flex-col flex-1">
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-1">{{ $plan['name'] }}</h3>
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
                                    <li class="flex items-start gap-3 text-sm text-zinc-400 dark:text-zinc-500">
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

            {{-- Section 8: FAQ --}}
            <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950">
                <div class="hidden lg:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-2xl shadow-blue-500/40 ring-4 ring-white dark:ring-zinc-950">
                        <x-ui::icon name="help" size="md" class="text-white" />
                    </div>
                </div>

                <div class="mx-auto max-w-3xl px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <div class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                            <x-ui::icon name="help" size="sm" />
                            {{ __('FAQ') }}
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white">{{ __('Frequently Asked Questions') }}</h2>
                    </div>

                    <div class="space-y-3" x-data="{ open: null }">
                        @foreach([
                            ['id' => 'd5q1', 'q' => __('What is SeratyAI?'), 'a' => __('An AI-powered CV builder that creates professional, ATS-optimized resumes with real-time compatibility scoring.')],
                            ['id' => 'd5q2', 'q' => __('Is my data secure?'), 'a' => __('Yes. All data encrypted at rest and in transit. GDPR and SOC 2 compliant.')],
                            ['id' => 'd5q3', 'q' => __('Is there a free trial?'), 'a' => __('Every new account gets a 7-day Pro trial. No credit card required.')],
                            ['id' => 'd5q4', 'q' => __('Can I cancel anytime?'), 'a' => __('Yes. Cancel anytime. No fees. Access continues until end of billing period.')],
                            ['id' => 'd5q5', 'q' => __('What export formats?'), 'a' => __('High-quality PDF, universally accepted by ATS systems.')],
                            ['id' => 'd5q6', 'q' => __('Multiple CVs?'), 'a' => __('Pro and Enterprise users create unlimited CVs tailored to different applications.')]
                        ] as $faq)
                        <div class="border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300">
                            <button @click="open = open === '{{ $faq['id'] }}' ? null : '{{ $faq['id'] }}'" class="w-full flex items-center justify-between px-6 py-5 text-left hover:bg-zinc-50 dark:hover:bg-zinc-900/50 transition-colors">
                                <span class="text-sm font-semibold text-zinc-900 dark:text-white pr-4">{{ $faq['q'] }}</span>
                                <x-ui::icon name="chevron-down" size="md" class="text-zinc-400 shrink-0 transition-transform duration-300" x-bind:class="open === '{{ $faq['id'] }}' ? 'rotate-180' : ''" />
                            </button>
                            <div x-show="open === '{{ $faq['id'] }}'" x-collapse x-cloak>
                                <div class="px-6 pb-5 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $faq['a'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- Section 9: Contact --}}
            <section class="relative py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-900">
                <div class="hidden lg:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-2xl shadow-emerald-500/40 ring-4 ring-zinc-50 dark:ring-zinc-900">
                        <x-ui::icon name="mail" size="md" class="text-white" />
                    </div>
                </div>

                <div class="mx-auto max-w-7xl px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <div class="inline-flex items-center gap-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                            <x-ui::icon name="mail" size="sm" />
                            {{ __('Contact') }}
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">{{ __('Get in Touch') }}</h2>
                        <p class="text-lg text-zinc-600 dark:text-zinc-400">{{ __('We respond within 24 hours.') }}</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <x-ui::card class="p-8 border-2 border-emerald-200 dark:border-emerald-800">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                        <x-ui::icon name="send" size="md" class="text-white" />
                                    </div>
                                    <h3 class="text-xl font-bold text-zinc-900 dark:text-white">{{ __('Send Us a Message') }}</h3>
                                </div>
                                <livewire:contact-form />
                            </x-ui::card>
                        </div>
                        <div class="lg:col-span-2 space-y-4">
                            @foreach([
                                ['icon' => 'mail', 'title' => __('Email'), 'value' => 'support@seratyai.com', 'sub' => __('Response within 24h'), 'color' => 'emerald'],
                                ['icon' => 'phone', 'title' => __('Phone'), 'value' => '+1 (555) 123-4567', 'sub' => __('Mon-Fri, 9AM-6PM PST'), 'color' => 'blue'],
                                ['icon' => 'map-pin', 'title' => __('Office'), 'value' => __('123 Innovation Drive, SF, CA 94107'), 'sub' => __('By appointment only'), 'color' => 'purple']
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

                            <x-ui::card class="p-5">
                                <div class="flex items-center gap-2 mb-3">
                                    <x-ui::icon name="clock" size="md" class="text-amber-500" />
                                    <span class="text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Hours') }}</span>
                                </div>
                                <div class="space-y-2 text-xs">
                                    <div class="flex justify-between"><span class="text-zinc-500">{{ __('Mon-Fri') }}</span><span class="font-medium text-zinc-900 dark:text-white">{{ __('9AM-6PM PST') }}</span></div>
                                    <div class="flex justify-between"><span class="text-zinc-500">{{ __('Saturday') }}</span><span class="font-medium text-zinc-900 dark:text-white">{{ __('10AM-2PM') }}</span></div>
                                    <div class="flex justify-between"><span class="text-zinc-500">{{ __('Sunday') }}</span><span class="font-medium text-red-500">{{ __('Closed') }}</span></div>
                                </div>
                            </x-ui::card>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- CTA --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-20 -right-20 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse-glow"></div>
                <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-emerald-300 rounded-full blur-3xl animate-pulse-glow" style="animation-delay: 2s;"></div>
            </div>
            <div class="relative mx-auto max-w-4xl px-6 lg:px-8 py-20 lg:py-28 text-center">
                <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6 leading-tight">{{ __('Ready to Stand Out?') }}</h2>
                <p class="text-lg text-emerald-100 max-w-2xl mx-auto mb-10 leading-relaxed">{{ __('Join :count professionals who transformed their job search. Your next career move starts here.', ['count' => '10,000+']) }}</p>
                <x-ui::button variant="primary" :href="route('register')" icon="arrow-right" size="lg" class="bg-white text-emerald-700 hover:bg-emerald-50 shadow-2xl shadow-emerald-900/40 hover:-translate-y-1 transition-all duration-300 px-10 py-4 text-base">
                    {{ __('Create Your CV Now') }}
                </x-ui::button>
            </div>
        </section>
    </main>
</x-layouts::landing>
