<x-layouts::landing>
    <main class="relative">
        {{-- Animated Background --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-40 left-10 w-80 h-80 bg-emerald-200 dark:bg-emerald-900/20 rounded-full blur-3xl animate-pulse-glow opacity-50"></div>
            <div class="absolute bottom-40 right-10 w-96 h-96 bg-blue-200 dark:bg-blue-900/20 rounded-full blur-3xl animate-pulse-glow opacity-50" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[900px] h-[900px] bg-gradient-to-r from-emerald-500/5 to-blue-500/5 rounded-full blur-3xl"></div>
        </div>

        {{-- Hero Section --}}
        <section class="relative bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 py-24 lg:py-32 overflow-hidden">
            {{-- Pattern Overlay --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 48px 48px;"></div>
            </div>

            {{-- Floating Elements --}}
            <div class="absolute top-20 left-1/4 w-3 h-3 bg-white rounded-full animate-float opacity-30"></div>
            <div class="absolute bottom-32 right-1/4 w-2 h-2 bg-emerald-200 rounded-full animate-float-reverse opacity-40" style="animation-delay: 1.5s;"></div>
            <div class="absolute top-1/3 right-20 w-4 h-4 bg-emerald-300 rounded-full animate-float-slow opacity-25" style="animation-delay: 0.8s;"></div>
            <div class="absolute bottom-1/3 left-16 w-2 h-2 bg-white rounded-full animate-float opacity-30" style="animation-delay: 2.5s;"></div>

            <div class="relative mx-auto max-w-4xl px-6 lg:px-8 text-center">
                <div class="animate-slide-up inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm text-white text-sm font-medium px-5 py-2.5 rounded-full mb-6 border border-white/20 shadow-lg">
                    <x-ui::icon name="building-office" size="sm" />
                    Our Story
                </div>
                <h1 class="animate-slide-up delay-100 text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    About SeratyAI
                </h1>
                <p class="animate-slide-up delay-200 text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto leading-relaxed">
                    We are on a mission to help every job seeker present their best professional self through AI-powered resume building.
                </p>
            </div>
        </section>

        {{-- Mission Section --}}
        <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <div class="order-2 lg:order-1">
                        <div class="inline-flex items-center gap-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                            <x-ui::icon name="heart" size="sm" />
                            Our Mission
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-6 leading-tight">
                            Empowering Careers Through Technology
                        </h2>
                        <div class="space-y-4">
                            <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                SeratyAI was founded in 2024 by a team of hiring managers, career coaches, and software engineers who saw a critical gap in the job application process. Millions of talented professionals were being filtered out by ATS systems, not because they lacked qualifications, but because their CVs were not optimized for the technology screening them.
                            </p>
                            <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                We built SeratyAI to level the playing field. Our AI analyzes job descriptions in real time, identifies the keywords and competencies that ATS systems prioritize, and helps candidates present their experience in the most impactful way possible.
                            </p>
                            <p class="text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Today, over <span class="font-bold text-emerald-600 dark:text-emerald-400">10,000 professionals</span> trust SeratyAI to power their job searches, and we are proud to report a <span class="font-bold text-emerald-600 dark:text-emerald-400">95% ATS pass rate</span> among our users. We're just getting started.
                            </p>
                        </div>
                    </div>
                    <div class="order-1 lg:order-2 flex items-center justify-center">
                        <div class="relative w-full max-w-md">
                            {{-- Main Stats Card --}}
                            <div class="aspect-square rounded-3xl bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/30 dark:to-emerald-800/30 flex items-center justify-center shadow-2xl shadow-emerald-500/20 hover:shadow-emerald-500/30 transition-all duration-500 hover:-translate-y-2">
                                <div class="text-center p-8">
                                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-500/40 animate-pulse">
                                        <x-ui::icon name="trophy" size="xl" class="text-white" />
                                    </div>
                                    <div class="text-5xl md:text-6xl font-bold bg-gradient-to-r from-emerald-600 to-emerald-400 bg-clip-text text-transparent mb-3">10,000+</div>
                                    <div class="text-lg text-zinc-700 dark:text-zinc-300 font-semibold">Careers Transformed</div>
                                </div>
                            </div>

                            {{-- Floating Badge --}}
                            <div class="absolute -bottom-6 -right-6 bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl p-5 border-2 border-amber-400 dark:border-amber-600 hover:scale-110 transition-transform duration-300">
                                <div class="flex items-center gap-3">
                                    <div class="flex -space-x-2">
                                        <x-ui::icon name="star" size="lg" class="text-amber-400" />
                                        <x-ui::icon name="star" size="lg" class="text-amber-400" />
                                        <x-ui::icon name="star" size="lg" class="text-amber-400" />
                                        <x-ui::icon name="star" size="lg" class="text-amber-400" />
                                        <x-ui::icon name="star" size="lg" class="text-amber-400" />
                                    </div>
                                    <div>
                                        <div class="text-2xl font-bold text-zinc-900 dark:text-white">4.9/5</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400 font-medium">User Rating</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Why Choose Us --}}
        <section class="relative py-20 lg:py-28 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                        <x-ui::icon name="sparkles" size="sm" />
                        Why Choose Us
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Built by Experts, for Professionals
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        Our platform combines deep industry expertise with cutting-edge technology to deliver results that matter.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                    @foreach([
                        ['icon' => 'graduation-cap', 'title' => 'Industry Expertise', 'desc' => 'Our templates and AI models are trained on data from real hiring managers across tech, finance, healthcare, and more.', 'color' => 'emerald'],
                        ['icon' => 'zap', 'title' => 'Lightning Fast', 'desc' => 'Build a polished, professional CV in under 10 minutes. Our streamlined workflow eliminates the back-and-forth.', 'color' => 'blue'],
                        ['icon' => 'heart', 'title' => 'User Focused', 'desc' => 'Every feature is designed around real user feedback. We continuously improve based on what job seekers need.', 'color' => 'purple'],
                        ['icon' => 'shield-check', 'title' => 'Privacy First', 'desc' => 'Your data stays yours. We use end-to-end encryption and comply with GDPR and SOC 2 standards.', 'color' => 'amber']
                    ] as $feature)
                    <x-ui::card class="group hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border-2 hover:border-{{ $feature['color'] }}-300 dark:hover:border-{{ $feature['color'] }}-700">
                        <div class="p-6 lg:p-8 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-{{ $feature['color'] }}-500 to-{{ $feature['color'] }}-600 flex items-center justify-center mx-auto mb-6 shadow-xl shadow-{{ $feature['color'] }}-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                <x-ui::icon name="{{ $feature['icon'] }}" size="xl" class="text-white" />
                            </div>
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-3">{{ $feature['title'] }}</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">{{ $feature['desc'] }}</p>
                        </div>
                    </x-ui::card>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Team Section --}}
        <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-sm font-semibold px-4 py-2 rounded-full mb-6">
                        <x-ui::icon name="users" size="sm" />
                        Our Team
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Meet the People Behind SeratyAI
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        A passionate team of engineers, designers, and career experts dedicated to transforming how people apply for jobs.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                    @foreach([
                        ['initials' => 'AJ', 'name' => 'Alex Johnson', 'role' => 'CEO & Co-Founder', 'desc' => 'Former VP of Talent Acquisition at a Fortune 500 company with 15 years of recruiting experience.', 'color' => 'emerald'],
                        ['initials' => 'MW', 'name' => 'Maria Williams', 'role' => 'CTO & Co-Founder', 'desc' => 'Full-stack engineer with a background in NLP and machine learning, previously at a leading AI research lab.', 'color' => 'blue'],
                        ['initials' => 'DL', 'name' => 'David Lee', 'role' => 'Head of Product', 'desc' => 'Product leader with experience building career platforms used by millions of professionals worldwide.', 'color' => 'purple'],
                        ['initials' => 'RN', 'name' => 'Rachel Nguyen', 'role' => 'Head of Design', 'desc' => 'Award-winning UX designer specializing in document design and user experience for productivity tools.', 'color' => 'amber']
                    ] as $member)
                    <div class="group text-center">
                        <div class="relative mb-6">
                            <div class="w-28 h-28 rounded-full bg-gradient-to-br from-{{ $member['color'] }}-500 to-{{ $member['color'] }}-600 flex items-center justify-center mx-auto shadow-2xl shadow-{{ $member['color'] }}-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                                <span class="text-3xl font-bold text-white">{{ $member['initials'] }}</span>
                            </div>
                            <div class="absolute inset-0 rounded-full bg-gradient-to-br from-{{ $member['color'] }}-500 to-{{ $member['color'] }}-600 blur-xl opacity-30 group-hover:opacity-50 transition-opacity duration-300 animate-pulse"></div>
                        </div>
                        <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">{{ $member['name'] }}</h3>
                        <p class="text-sm font-bold text-{{ $member['color'] }}-600 dark:text-{{ $member['color'] }}-400 mb-3">{{ $member['role'] }}</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed max-w-xs mx-auto">{{ $member['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
</x-layouts::landing>
