<x-layouts::landing>
    <main class="relative">
        {{-- Hero: Full-viewport split with bento grid preview --}}
        <section class="relative min-h-screen flex items-center bg-zinc-950 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/40 via-zinc-950 to-zinc-950"></div>
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 32px 32px;"></div>

            <div class="absolute top-20 right-20 w-2 h-2 bg-emerald-400 rounded-full animate-float opacity-60"></div>
            <div class="absolute bottom-40 left-1/3 w-3 h-3 bg-emerald-500 rounded-full animate-float-reverse opacity-40" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/3 left-20 w-2 h-2 bg-white rounded-full animate-float-slow opacity-30" style="animation-delay: 2s;"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8 py-24 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <div>
                        <div class="animate-slide-up inline-flex items-center gap-2 bg-emerald-500/15 text-emerald-400 text-sm font-semibold px-4 py-2 rounded-full mb-8 border border-emerald-500/20">
                            <x-ui::icon name="sparkles" size="sm" />
                            AI-Powered Resume Building
                        </div>

                        <h1 class="animate-slide-up delay-100 text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-[1.05] mb-6">
                            Build Your
                            <span class="bg-gradient-to-r from-emerald-400 to-emerald-300 bg-clip-text text-transparent">Perfect CV</span>
                            with AI
                        </h1>

                        <p class="animate-slide-up delay-200 text-lg text-zinc-400 leading-relaxed mb-10 max-w-xl">
                            Create professional, ATS-optimized resumes in minutes. Our AI analyzes job descriptions and tailors your CV to maximize your chances of landing interviews.
                        </p>

                        <div class="animate-slide-up delay-300 flex flex-col sm:flex-row items-start gap-4">
                            <x-ui::button variant="primary" :href="route('cv.builder')" icon="arrow-right" class="bg-emerald-600 hover:bg-emerald-500 text-white shadow-2xl shadow-emerald-600/30 hover:shadow-emerald-500/50 hover:-translate-y-0.5 transition-all duration-300 px-8 py-3 text-base">
                                Get Started Free
                            </x-ui::button>
                            <x-ui::button variant="ghost" href="#features" class="text-zinc-300 hover:text-white hover:bg-white/10 border border-zinc-700 px-8 py-3 text-base">
                                See Features
                            </x-ui::button>
                        </div>

                        <div class="animate-fade-in delay-500 mt-12 flex items-center gap-6">
                            <div class="flex -space-x-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 border-2 border-zinc-950 flex items-center justify-center text-[10px] font-bold text-white">SR</div>
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 border-2 border-zinc-950 flex items-center justify-center text-[10px] font-bold text-white">MK</div>
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 border-2 border-zinc-950 flex items-center justify-center text-[10px] font-bold text-white">EP</div>
                            </div>
                            <p class="text-sm text-zinc-500">Trusted by <span class="text-emerald-400 font-semibold">10,000+</span> professionals</p>
                        </div>
                    </div>

                    {{-- Bento Grid Preview --}}
                    <div class="animate-slide-in-right delay-300 hidden lg:block">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl p-6 shadow-2xl shadow-emerald-600/20 hover:shadow-emerald-500/30 transition-all duration-500 hover:-translate-y-1">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                        <x-ui::icon name="sparkles" size="md" class="text-white" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-white">AI Writing Assistant</div>
                                        <div class="text-xs text-emerald-200">Powered by GPT-4</div>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="h-2 bg-white/20 rounded-full w-full"></div>
                                    <div class="h-2 bg-white/15 rounded-full w-4/5"></div>
                                    <div class="h-2 bg-white/10 rounded-full w-3/5"></div>
                                </div>
                            </div>
                            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 hover:border-emerald-700 transition-all duration-300">
                                <x-ui::icon name="shield-check" size="lg" class="text-emerald-400 mb-3" />
                                <div class="text-sm font-semibold text-white mb-1">ATS Score</div>
                                <div class="text-3xl font-bold text-emerald-400">95%</div>
                                <div class="text-xs text-zinc-500 mt-1">Pass rate</div>
                            </div>
                            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 hover:border-emerald-700 transition-all duration-300">
                                <x-ui::icon name="document-text" size="lg" class="text-blue-400 mb-3" />
                                <div class="text-sm font-semibold text-white mb-1">Templates</div>
                                <div class="text-3xl font-bold text-blue-400">50+</div>
                                <div class="text-xs text-zinc-500 mt-1">Professional</div>
                            </div>
                            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 hover:border-emerald-700 transition-all duration-300">
                                <x-ui::icon name="star" size="lg" class="text-amber-400 mb-3" />
                                <div class="text-sm font-semibold text-white mb-1">User Rating</div>
                                <div class="text-3xl font-bold text-amber-400">4.9</div>
                                <div class="text-xs text-zinc-500 mt-1">Out of 5</div>
                            </div>
                            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 hover:border-emerald-700 transition-all duration-300">
                                <div class="flex items-center gap-2 mb-2">
                                    <x-ui::icon name="zap" size="md" class="text-purple-400" />
                                    <span class="text-xs font-medium text-purple-400">Live Preview</span>
                                </div>
                                <div class="space-y-1.5">
                                    <div class="h-1.5 bg-zinc-800 rounded-full w-full"></div>
                                    <div class="h-1.5 bg-emerald-600/50 rounded-full w-4/5"></div>
                                    <div class="h-1.5 bg-zinc-800 rounded-full w-full"></div>
                                    <div class="h-1.5 bg-emerald-600/30 rounded-full w-3/5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features: Bento Grid --}}
        <section id="features" class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950 overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-100 dark:bg-emerald-900/10 rounded-full blur-3xl opacity-40"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-100 dark:bg-blue-900/10 rounded-full blur-3xl opacity-40"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="max-w-2xl mb-16">
                    <div class="inline-flex items-center gap-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                        <x-ui::icon name="sparkles" size="sm" />
                        Features
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">Everything You Need to Land Your Dream Job</h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">Our comprehensive toolkit helps you create, optimize, and manage professional resumes with ease.</p>
                </div>

                {{-- Bento Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-5">
                    {{-- Large card spanning 2 cols --}}
                    <div class="lg:col-span-2 group bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl p-8 lg:p-10 text-white hover:shadow-2xl hover:shadow-emerald-500/20 transition-all duration-500 hover:-translate-y-1">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-8">
                            <div class="flex-1">
                                <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                    <x-ui::icon name="sparkles" size="xl" class="text-white" />
                                </div>
                                <h3 class="text-2xl font-bold mb-3">AI-Powered Writing</h3>
                                <p class="text-emerald-100 leading-relaxed max-w-md">Generate compelling bullet points, summaries, and cover letters tailored to your industry and experience level using advanced AI models.</p>
                            </div>
                            <div class="w-full lg:w-48 h-32 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <div class="text-center">
                                    <div class="text-4xl font-extrabold mb-1">GPT-4</div>
                                    <div class="text-xs text-emerald-200">Powered</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <x-ui::card class="group hover:shadow-2xl hover:shadow-blue-500/20 transition-all duration-500 hover:-translate-y-2 border-2 hover:border-blue-300 dark:hover:border-blue-700">
                        <div class="p-6 lg:p-8">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mb-6 shadow-xl shadow-blue-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                <x-ui::icon name="shield-check" size="lg" class="text-white" />
                            </div>
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-3">ATS Optimization</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Ensure your CV passes Applicant Tracking Systems with keyword analysis, formatting checks, and real-time compatibility scoring.</p>
                        </div>
                    </x-ui::card>

                    <x-ui::card class="group hover:shadow-2xl hover:shadow-purple-500/20 transition-all duration-500 hover:-translate-y-2 border-2 hover:border-purple-300 dark:hover:border-purple-700">
                        <div class="p-6 lg:p-8">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mb-6 shadow-xl shadow-purple-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                <x-ui::icon name="document-text" size="lg" class="text-white" />
                            </div>
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-3">Professional Templates</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Choose from over 50 professionally designed templates, each crafted by hiring managers and career experts.</p>
                        </div>
                    </x-ui::card>

                    <x-ui::card class="group hover:shadow-2xl hover:shadow-amber-500/20 transition-all duration-500 hover:-translate-y-2 border-2 hover:border-amber-300 dark:hover:border-amber-700">
                        <div class="p-6 lg:p-8">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center mb-6 shadow-xl shadow-amber-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                <x-ui::icon name="eye" size="lg" class="text-white" />
                            </div>
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-3">Real-Time Preview</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">See exactly how your CV will look as you edit. Our live preview updates instantly so you can perfect every detail.</p>
                        </div>
                    </x-ui::card>

                    {{-- Large card spanning 2 cols --}}
                    <div class="lg:col-span-2 group bg-gradient-to-br from-zinc-900 to-zinc-800 dark:from-zinc-800 dark:to-zinc-700 rounded-2xl p-8 lg:p-10 text-white hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 border border-zinc-700 dark:border-zinc-600">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-8">
                            <div class="flex-1">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center mb-6 shadow-xl shadow-pink-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                    <x-ui::icon name="lightbulb" size="lg" class="text-white" />
                                </div>
                                <h3 class="text-2xl font-bold mb-3">Smart Suggestions</h3>
                                <p class="text-zinc-400 leading-relaxed max-w-md">Get intelligent recommendations for skills, action verbs, and content improvements based on hiring trends.</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3 w-full lg:w-56">
                                @foreach(['Action Verbs', 'Skills', 'Keywords', 'Formatting'] as $suggestion)
                                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 text-center">
                                    <div class="text-xs text-zinc-400">{{ $suggestion }}</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <x-ui::card class="group hover:shadow-2xl hover:shadow-teal-500/20 transition-all duration-500 hover:-translate-y-2 border-2 hover:border-teal-300 dark:hover:border-teal-700">
                        <div class="p-6 lg:p-8">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center mb-6 shadow-xl shadow-teal-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                <x-ui::icon name="arrow-down-tray" size="lg" class="text-white" />
                            </div>
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-3">Export Ready</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Download your polished CV in PDF format, perfectly formatted and ready to submit. Share via link or email.</p>
                        </div>
                    </x-ui::card>
                </div>
            </div>
        </section>

        {{-- How It Works: Horizontal Stepper --}}
        <section class="relative py-20 lg:py-28 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 overflow-hidden">
            <div class="absolute top-0 left-1/4 w-80 h-80 bg-emerald-200 dark:bg-emerald-900/20 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-blue-200 dark:bg-blue-900/20 rounded-full blur-3xl opacity-50"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                        <x-ui::icon name="zap" size="sm" />
                        How It Works
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">Three Simple Steps to Your Perfect CV</h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">Creating a professional resume has never been easier.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach([
                        ['step' => 1, 'title' => 'Fill Your Info', 'desc' => 'Enter your work experience, education, skills, and certifications. Our guided form makes it easy to capture every detail.', 'icon' => 'pencil'],
                        ['step' => 2, 'title' => 'AI Enhances', 'desc' => 'Our AI analyzes your content and optimizes it for impact. Get stronger action verbs, better descriptions, and ATS-friendly formatting.', 'icon' => 'sparkles'],
                        ['step' => 3, 'title' => 'Download & Apply', 'desc' => 'Preview your finished CV, make any final adjustments, then export as a clean PDF ready to send to employers.', 'icon' => 'arrow-down-tray']
                    ] as $index => $item)
                    <div class="relative group">
                        @if($index < 2)
                        <div class="hidden md:block absolute top-12 left-[60%] w-[calc(80%-10px)] h-0.5 bg-gradient-to-r from-emerald-500 to-emerald-300 dark:to-emerald-700 z-0"></div>
                        @endif
                        <div class="relative bg-white dark:bg-zinc-800 rounded-2xl p-8 shadow-lg border border-zinc-200 dark:border-zinc-700 group-hover:shadow-2xl group-hover:shadow-emerald-500/10 group-hover:border-emerald-300 dark:group-hover:border-emerald-700 transition-all duration-500 group-hover:-translate-y-2 z-10">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center mb-6 shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                                <span class="text-xl font-bold text-white">{{ $item['step'] }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-3">{{ $item['title'] }}</h3>
                            <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Stats: Full-width dark band --}}
        <section class="relative bg-zinc-950 overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-20 -right-20 w-96 h-96 bg-emerald-500 rounded-full blur-3xl animate-pulse-glow"></div>
                <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-blue-500 rounded-full blur-3xl animate-pulse-glow" style="animation-delay: 2s;"></div>
            </div>
            <div class="relative mx-auto max-w-7xl px-6 lg:px-8 py-16 lg:py-20">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach([
                        ['value' => '10,000+', 'label' => 'CVs Created'],
                        ['value' => '95%', 'label' => 'ATS Pass Rate'],
                        ['value' => '50+', 'label' => 'Templates'],
                        ['value' => '4.9/5', 'label' => 'User Rating']
                    ] as $index => $stat)
                    <div class="group text-center">
                        <div class="text-4xl md:text-5xl font-extrabold text-emerald-400 mb-2 group-hover:scale-110 transition-transform duration-300">{{ $stat['value'] }}</div>
                        <div class="text-zinc-500 text-sm md:text-base font-medium">{{ $stat['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- About/Mission --}}
        <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950 overflow-hidden">
            <div class="absolute top-0 left-0 w-96 h-96 bg-emerald-100 dark:bg-emerald-900/10 rounded-full blur-3xl opacity-40"></div>
            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <div>
                        <div class="inline-flex items-center gap-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                            <x-ui::icon name="heart" size="sm" />
                            Our Mission
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-6 leading-tight">Empowering Careers Through Technology</h2>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed mb-4">SeratyAI was founded in 2024 by a team of hiring managers, career coaches, and software engineers who saw a critical gap in the job application process.</p>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed mb-4">We built SeratyAI to level the playing field. Our AI analyzes job descriptions in real time, identifies the keywords and competencies that ATS systems prioritize, and helps candidates present their experience in the most impactful way possible.</p>
                        <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">Today, over <span class="font-bold text-emerald-600 dark:text-emerald-400">10,000 professionals</span> trust SeratyAI with a <span class="font-bold text-emerald-600 dark:text-emerald-400">95% ATS pass rate</span>.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach([
                            ['icon' => 'graduation-cap', 'title' => 'Industry Expertise', 'desc' => 'Templates trained on data from real hiring managers.', 'color' => 'emerald'],
                            ['icon' => 'zap', 'title' => 'Lightning Fast', 'desc' => 'Build a polished CV in under 10 minutes.', 'color' => 'blue'],
                            ['icon' => 'heart', 'title' => 'User Focused', 'desc' => 'Every feature designed around real user feedback.', 'color' => 'purple'],
                            ['icon' => 'shield-check', 'title' => 'Privacy First', 'desc' => 'End-to-end encryption, GDPR & SOC 2 compliant.', 'color' => 'amber']
                        ] as $feature)
                        <x-ui::card class="group hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border hover:border-{{ $feature['color'] }}-300 dark:hover:border-{{ $feature['color'] }}-700">
                            <div class="p-5">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-{{ $feature['color'] }}-500 to-{{ $feature['color'] }}-600 flex items-center justify-center mb-4 shadow-lg shadow-{{ $feature['color'] }}-500/20 group-hover:scale-110 transition-transform duration-300">
                                    <x-ui::icon name="{{ $feature['icon'] }}" size="md" class="text-white" />
                                </div>
                                <h3 class="text-sm font-bold text-zinc-900 dark:text-white mb-2">{{ $feature['title'] }}</h3>
                                <p class="text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $feature['desc'] }}</p>
                            </div>
                        </x-ui::card>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- Team --}}
        <section class="relative py-20 lg:py-28 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 overflow-hidden">
            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                        <x-ui::icon name="users" size="sm" />
                        Our Team
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">Meet the People Behind SeratyAI</h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">A passionate team dedicated to transforming how people apply for jobs.</p>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach([
                        ['initials' => 'AJ', 'name' => 'Alex Johnson', 'role' => 'CEO & Co-Founder', 'desc' => 'Former VP of Talent Acquisition, 15 years recruiting.', 'color' => 'emerald'],
                        ['initials' => 'MW', 'name' => 'Maria Williams', 'role' => 'CTO & Co-Founder', 'desc' => 'Full-stack engineer, NLP & ML background.', 'color' => 'blue'],
                        ['initials' => 'DL', 'name' => 'David Lee', 'role' => 'Head of Product', 'desc' => 'Product leader, career platforms for millions.', 'color' => 'purple'],
                        ['initials' => 'RN', 'name' => 'Rachel Nguyen', 'role' => 'Head of Design', 'desc' => 'Award-winning UX designer.', 'color' => 'amber']
                    ] as $member)
                    <div class="group text-center">
                        <div class="relative mb-4">
                            <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-br from-{{ $member['color'] }}-500 to-{{ $member['color'] }}-600 flex items-center justify-center shadow-xl shadow-{{ $member['color'] }}-500/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                <span class="text-2xl font-bold text-white">{{ $member['initials'] }}</span>
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-white">{{ $member['name'] }}</h3>
                        <p class="text-xs font-semibold text-{{ $member['color'] }}-600 dark:text-{{ $member['color'] }}-400 mb-2">{{ $member['role'] }}</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $member['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Testimonials --}}
        <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950 overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-amber-100 dark:bg-amber-900/10 rounded-full blur-3xl opacity-30"></div>
            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                        <x-ui::icon name="chat-bubble-left-right" size="sm" />
                        Testimonials
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">Trusted by Professionals Worldwide</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach([
                        ['name' => 'Sarah Rodriguez', 'role' => 'Software Engineer at Google', 'initials' => 'SR', 'color' => 'emerald', 'text' => 'SeratyAI completely transformed my job search. The AI suggestions made my experience descriptions so much stronger, and I started getting interview calls within a week.'],
                        ['name' => 'Marcus Kim', 'role' => 'Product Manager at Stripe', 'initials' => 'MK', 'color' => 'blue', 'text' => 'As a career changer, I struggled to present my transferable skills effectively. SeratyAI\'s AI helped me reframe my experience and within two months I landed a role in product management.'],
                        ['name' => 'Elena Petrova', 'role' => 'UX Designer at Figma', 'initials' => 'EP', 'color' => 'purple', 'text' => 'The templates are stunning and the real-time preview is incredibly useful. I customized a design that perfectly matched my personal brand.']
                    ] as $testimonial)
                    <x-ui::card class="group hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border-2 hover:border-{{ $testimonial['color'] }}-300 dark:hover:border-{{ $testimonial['color'] }}-700">
                        <div class="p-6 lg:p-8">
                            <div class="flex items-center gap-1 mb-4">
                                @for($i = 0; $i < 5; $i++)
                                <x-ui::icon name="star" size="sm" class="text-amber-400" />
                                @endfor
                            </div>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed mb-6">"{{ $testimonial['text'] }}"</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-{{ $testimonial['color'] }}-500 to-{{ $testimonial['color'] }}-600 flex items-center justify-center shadow-lg">
                                    <span class="text-xs font-bold text-white">{{ $testimonial['initials'] }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-zinc-900 dark:text-white">{{ $testimonial['name'] }}</div>
                                    <div class="text-xs text-zinc-500">{{ $testimonial['role'] }}</div>
                                </div>
                            </div>
                        </div>
                    </x-ui::card>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Pricing --}}
        <section class="relative py-20 lg:py-28 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 overflow-hidden">
            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                        <x-ui::icon name="briefcase" size="sm" />
                        Pricing
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">Simple, Transparent Pricing</h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">Start free and upgrade when you need more. No hidden fees.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    @foreach([
                        ['name' => 'Free', 'price' => '$0', 'period' => '/month', 'btn' => 'Get Started', 'features' => ['1 CV with basic template', 'PDF export with watermark', 'Basic ATS check', '5 AI suggestions per CV'], 'unavailable' => ['Unlimited CVs', 'AI cover letter generator'], 'popular' => false],
                        ['name' => 'Pro', 'price' => '$9', 'period' => '/month', 'btn' => 'Start Free Trial', 'features' => ['Unlimited CVs', 'All 50+ premium templates', 'Clean PDF export', 'Advanced ATS optimization', 'Unlimited AI suggestions', 'AI cover letter generator'], 'unavailable' => [], 'popular' => true],
                        ['name' => 'Enterprise', 'price' => '$29', 'period' => '/month', 'btn' => 'Contact Sales', 'features' => ['Everything in Pro', 'Team management dashboard', 'Custom branding', 'Priority support', 'API access', 'SSO & security'], 'unavailable' => [], 'popular' => false]
                    ] as $plan)
                    <x-ui::card class="{{ $plan['popular'] ? 'ring-2 ring-emerald-500 relative scale-[1.02] shadow-2xl shadow-emerald-500/20' : '' }} hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                        @if($plan['popular'])
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                            <span class="bg-emerald-600 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg">Most Popular</span>
                        </div>
                        @endif
                        <div class="p-6 lg:p-8 flex flex-col flex-1">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-1">{{ $plan['name'] }}</h3>
                            <div class="mb-8 mt-4">
                                <span class="text-5xl font-extrabold text-zinc-900 dark:text-white">{{ $plan['price'] }}</span>
                                <span class="text-zinc-500">{{ $plan['period'] }}</span>
                            </div>
                            <x-ui::button variant="{{ $plan['popular'] ? 'primary' : 'outline' }}" :href="route('register')" class="w-full mb-8 py-3">{{ $plan['btn'] }}</x-ui::button>
                            <ul class="space-y-3 mt-auto">
                                @foreach($plan['features'] as $feature)
                                <li class="flex items-start gap-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    <x-ui::icon name="check-circle" size="md" class="text-emerald-500 shrink-0 mt-0.5" />
                                    {{ $feature }}
                                </li>
                                @endforeach
                                @foreach($plan['unavailable'] as $feature)
                                <li class="flex items-start gap-3 text-sm text-zinc-400 dark:text-zinc-500">
                                    <x-ui::icon name="x-circle" size="md" class="text-zinc-300 dark:text-zinc-600 shrink-0 mt-0.5" />
                                    {{ $feature }}
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
        <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-3xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center gap-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                        <x-ui::icon name="help" size="sm" />
                        FAQ
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">Frequently Asked Questions</h2>
                </div>

                <div class="space-y-3" x-data="{ open: null }">
                    @foreach([
                        ['id' => 'd1q1', 'q' => 'What is SeratyAI?', 'a' => 'SeratyAI is an AI-powered CV builder that helps you create professional, ATS-optimized resumes. Our platform analyzes job descriptions and provides real-time compatibility scoring.'],
                        ['id' => 'd1q2', 'q' => 'Is my data secure?', 'a' => 'Absolutely. All personal information is encrypted at rest and in transit. We never sell or share your data. SeratyAI is fully GDPR and SOC 2 compliant.'],
                        ['id' => 'd1q3', 'q' => 'Is there a free trial?', 'a' => 'Yes. Every new account starts with a 7-day free trial of the Pro plan. No credit card required.'],
                        ['id' => 'd1q4', 'q' => 'Can I cancel anytime?', 'a' => 'Absolutely. Cancel anytime from your account settings. No cancellation fees. You retain access until the end of your billing period.'],
                        ['id' => 'd1q5', 'q' => 'What file formats are supported?', 'a' => 'You can export your CV as a high-quality PDF, which is the industry standard for job applications and universally accepted by ATS systems.'],
                        ['id' => 'd1q6', 'q' => 'Can I create multiple CVs?', 'a' => 'Yes. Pro and Enterprise users can create unlimited CVs, each tailored to specific job applications.']
                    ] as $faq)
                    <div class="border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300">
                        <button @click="open = open === '{{ $faq['id'] }}' ? null : '{{ $faq['id'] }}'" class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-zinc-50 dark:hover:bg-zinc-900/50 transition-colors">
                            <span class="text-sm font-semibold text-zinc-900 dark:text-white pr-4">{{ $faq['q'] }}</span>
                            <x-ui::icon name="chevron-down" size="md" class="text-zinc-400 shrink-0 transition-transform duration-300" x-bind:class="open === '{{ $faq['id'] }}' ? 'rotate-180' : ''" />
                        </button>
                        <div x-show="open === '{{ $faq['id'] }}'" x-collapse x-cloak>
                            <div class="px-6 pb-4 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $faq['a'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Contact --}}
        <section class="relative py-20 lg:py-28 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                        <x-ui::icon name="mail" size="sm" />
                        Contact
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">Get in Touch</h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">Have a question or need help? Our team responds within 24 hours.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <x-ui::card class="p-6 lg:p-8 border-2 border-emerald-200 dark:border-emerald-800">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                    <x-ui::icon name="send" size="md" class="text-white" />
                                </div>
                                <h3 class="text-xl font-bold text-zinc-900 dark:text-white">Send Us a Message</h3>
                            </div>
                            <livewire:contact-form />
                        </x-ui::card>
                    </div>
                    <div class="space-y-4">
                        <x-ui::card class="group hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                            <div class="p-5 flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/20 group-hover:scale-110 transition-transform duration-300">
                                    <x-ui::icon name="mail" size="md" class="text-white" />
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-zinc-900 dark:text-white">Email</div>
                                    <a href="mailto:support@seratyai.com" class="text-sm text-emerald-600 dark:text-emerald-400 hover:underline">support@seratyai.com</a>
                                </div>
                            </div>
                        </x-ui::card>
                        <x-ui::card class="group hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                            <div class="p-5 flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shrink-0 shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform duration-300">
                                    <x-ui::icon name="phone" size="md" class="text-white" />
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-zinc-900 dark:text-white">Phone</div>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">+1 (555) 123-4567</p>
                                </div>
                            </div>
                        </x-ui::card>
                        <x-ui::card class="group hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                            <div class="p-5 flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shrink-0 shadow-lg shadow-purple-500/20 group-hover:scale-110 transition-transform duration-300">
                                    <x-ui::icon name="map-pin" size="md" class="text-white" />
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-zinc-900 dark:text-white">Office</div>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">123 Innovation Drive<br>San Francisco, CA 94107</p>
                                </div>
                            </div>
                        </x-ui::card>
                        <x-ui::card class="group hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                            <div class="p-5">
                                <div class="flex items-center gap-2 mb-3">
                                    <x-ui::icon name="clock" size="md" class="text-amber-500" />
                                    <span class="text-sm font-semibold text-zinc-900 dark:text-white">Hours</span>
                                </div>
                                <div class="space-y-2 text-xs">
                                    <div class="flex justify-between"><span class="text-zinc-500">Mon-Fri</span><span class="font-medium text-zinc-900 dark:text-white">9AM-6PM PST</span></div>
                                    <div class="flex justify-between"><span class="text-zinc-500">Saturday</span><span class="font-medium text-zinc-900 dark:text-white">10AM-2PM</span></div>
                                    <div class="flex justify-between"><span class="text-zinc-500">Sunday</span><span class="font-medium text-red-500">Closed</span></div>
                                </div>
                            </div>
                        </x-ui::card>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-20 -right-20 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse-glow"></div>
                <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-emerald-300 rounded-full blur-3xl animate-pulse-glow" style="animation-delay: 2s;"></div>
            </div>
            <div class="relative mx-auto max-w-4xl px-6 lg:px-8 py-20 lg:py-28 text-center">
                <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6 leading-tight">Ready to Stand Out?</h2>
                <p class="text-lg text-emerald-100 max-w-2xl mx-auto mb-10 leading-relaxed">Join thousands of professionals who have already transformed their job search. Your next career move starts with a great CV.</p>
                <x-ui::button variant="primary" :href="route('register')" icon="arrow-right" size="lg" class="bg-white text-emerald-700 hover:bg-emerald-50 shadow-2xl shadow-emerald-900/40 hover:-translate-y-1 transition-all duration-300 px-10 py-4 text-base">
                    Create Your CV Now
                </x-ui::button>
            </div>
        </section>
    </main>
</x-layouts::landing>
