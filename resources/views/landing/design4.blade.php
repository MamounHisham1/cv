<x-layouts::landing>
    <main class="relative bg-zinc-950">
        {{-- Hero: Gradient mesh with glassmorphism --}}
        <section id="home" class="scroll-mt-16 relative min-h-screen flex items-center overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute top-0 left-0 w-[600px] h-[600px] bg-emerald-600/30 rounded-full blur-[150px] animate-pulse-glow"></div>
                <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-blue-600/20 rounded-full blur-[130px] animate-pulse-glow" style="animation-delay: 2s;"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-purple-600/15 rounded-full blur-[120px] animate-pulse-glow" style="animation-delay: 4s;"></div>
            </div>

            <div class="absolute top-20 left-1/4 w-2 h-2 bg-emerald-400 rounded-full animate-float opacity-60"></div>
            <div class="absolute bottom-32 right-1/3 w-3 h-3 bg-blue-400 rounded-full animate-float-reverse opacity-40" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/3 right-16 w-2 h-2 bg-purple-400 rounded-full animate-float-slow opacity-30" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-1/4 left-16 w-1.5 h-1.5 bg-emerald-300 rounded-full animate-float opacity-50" style="animation-delay: 3s;"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8 py-24 w-full">
                <div class="text-center max-w-4xl mx-auto">
                    <div class="animate-slide-up inline-flex items-center gap-2 bg-white/5 backdrop-blur-xl text-emerald-300 text-sm font-medium px-5 py-2.5 rounded-full mb-10 border border-white/10">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                        AI-Powered Resume Building
                    </div>

                    <h1 class="animate-slide-up delay-100 text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-[1.05] mb-8">
                        Craft the CV that
                        <span class="bg-gradient-to-r from-emerald-400 via-blue-400 to-purple-400 bg-clip-text text-transparent">gets you hired.</span>
                    </h1>

                    <p class="animate-slide-up delay-200 text-lg md:text-xl text-zinc-400 leading-relaxed mb-12 max-w-2xl mx-auto">
                        Create professional, ATS-optimized resumes in minutes. Our AI tailors your CV to maximize your chances of landing interviews.
                    </p>

                    <div class="animate-slide-up delay-300 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <x-ui::button variant="primary" :href="route('cv.builder')" icon="arrow-right" class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white shadow-2xl shadow-emerald-500/30 hover:shadow-emerald-400/50 hover:-translate-y-1 transition-all duration-300 px-10 py-4 text-base">
                            Get Started Free
                        </x-ui::button>
                        <x-ui::button variant="ghost" href="#features" class="text-zinc-300 hover:text-white bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 px-10 py-4 text-base">
                            Explore Features
                        </x-ui::button>
                    </div>
                </div>

                {{-- Glassmorphism preview card --}}
                <div class="animate-slide-up delay-500 mt-20 max-w-3xl mx-auto">
                    <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 p-1 shadow-2xl">
                        <div class="bg-zinc-900/80 rounded-xl p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center">
                                    <x-ui::icon name="sparkles" size="sm" class="text-white" />
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-white">AI Writing Active</div>
                                    <div class="text-xs text-zinc-500">Analyzing your experience...</div>
                                </div>
                                <div class="ml-auto flex items-center gap-2">
                                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                                    <span class="text-xs text-emerald-400">Live</span>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="h-3 bg-gradient-to-r from-emerald-500/30 to-transparent rounded-full w-full animate-width-grow"></div>
                                <div class="h-3 bg-zinc-800 rounded-full w-4/5"></div>
                                <div class="h-3 bg-zinc-800 rounded-full w-3/5"></div>
                                <div class="flex items-center gap-2 mt-4">
                                    <span class="text-xs bg-emerald-500/20 text-emerald-300 px-3 py-1 rounded-full border border-emerald-500/30">ATS Score: 95%</span>
                                    <span class="text-xs bg-blue-500/20 text-blue-300 px-3 py-1 rounded-full border border-blue-500/30">50+ Keywords</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @php
            $templates = [
                [
                    'id' => 'professional-classic',
                    'name' => 'Professional Classic',
                    'description' => 'Traditional hierarchy for corporate and formal roles.',
                    'tag' => 'Classic',
                    'badge_classes' => 'border-emerald-500/20 bg-emerald-500/10 text-emerald-300',
                    'glow_classes' => 'from-emerald-500/15 via-emerald-500/5 to-transparent',
                ],
                [
                    'id' => 'technical-ats',
                    'name' => 'Technical ATS',
                    'description' => 'Clean, structured formatting optimized for ATS scans.',
                    'tag' => 'ATS',
                    'badge_classes' => 'border-blue-500/20 bg-blue-500/10 text-blue-300',
                    'glow_classes' => 'from-blue-500/15 via-blue-500/5 to-transparent',
                ],
                [
                    'id' => 'modern-minimal',
                    'name' => 'Modern Minimal',
                    'description' => 'Airy spacing with a polished, contemporary feel.',
                    'tag' => 'Minimal',
                    'badge_classes' => 'border-purple-500/20 bg-purple-500/10 text-purple-300',
                    'glow_classes' => 'from-purple-500/15 via-purple-500/5 to-transparent',
                ],
                [
                    'id' => 'creative',
                    'name' => 'Creative',
                    'description' => 'Sidebar-driven layout for visual storytelling and portfolios.',
                    'tag' => 'Creative',
                    'badge_classes' => 'border-pink-500/20 bg-pink-500/10 text-pink-300',
                    'glow_classes' => 'from-pink-500/15 via-pink-500/5 to-transparent',
                ],
                [
                    'id' => 'executive',
                    'name' => 'Executive',
                    'description' => 'Centered presentation tailored to leadership profiles.',
                    'tag' => 'Executive',
                    'badge_classes' => 'border-amber-500/20 bg-amber-500/10 text-amber-300',
                    'glow_classes' => 'from-amber-500/15 via-amber-500/5 to-transparent',
                ],
            ];
        @endphp

        {{-- Templates showcase: Moving miniature previews --}}
        <section id="templates" class="scroll-mt-16 relative overflow-hidden py-20 lg:py-28">
            <div class="absolute left-0 top-12 h-[360px] w-[360px] rounded-full bg-purple-600/10 blur-[120px]"></div>
            <div class="absolute bottom-0 right-0 h-[320px] w-[320px] rounded-full bg-emerald-600/10 blur-[120px]"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mb-12 flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-purple-500/20 bg-purple-500/10 px-4 py-2 text-sm font-semibold text-purple-300">
                            <x-ui::icon name="document-text" size="sm" />
                            Template Gallery
                        </div>
                        <h2 class="text-3xl font-bold text-white md:text-4xl">Different resume templates for every career path</h2>
                        <p class="mt-4 text-lg leading-relaxed text-zinc-400">
                            Explore <span class="font-semibold text-white">5 distinct resume templates</span> inspired by the real designs inside the builder — from classic and ATS-first layouts to modern, creative, and executive styles.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 backdrop-blur-xl">
                            <div class="text-2xl font-bold text-emerald-400">5</div>
                            <div class="text-xs uppercase tracking-[0.2em] text-zinc-500">Live Templates</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 backdrop-blur-xl">
                            <div class="text-2xl font-bold text-blue-400">ATS</div>
                            <div class="text-xs uppercase tracking-[0.2em] text-zinc-500">To Creative</div>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 backdrop-blur-xl sm:col-span-1 col-span-2">
                            <div class="text-2xl font-bold text-purple-400">Switch</div>
                            <div class="text-xs uppercase tracking-[0.2em] text-zinc-500">Anytime</div>
                        </div>
                    </div>
                </div>

                <div class="template-marquee relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/5 p-4 backdrop-blur-xl sm:p-6">
                    <div class="pointer-events-none absolute inset-y-0 left-0 z-10 w-16 bg-gradient-to-r from-zinc-950 via-zinc-950/75 to-transparent"></div>
                    <div class="pointer-events-none absolute inset-y-0 right-0 z-10 w-16 bg-gradient-to-l from-zinc-950 via-zinc-950/75 to-transparent"></div>

                    <div class="template-marquee-track flex w-max">
                        @for ($copy = 0; $copy < 2; $copy++)
                            <div class="flex shrink-0 gap-5 pe-5" @if($copy === 1) aria-hidden="true" @endif>
                                @foreach($templates as $template)
                                    <article class="group relative w-[240px] shrink-0 rounded-[1.75rem] border border-white/10 bg-zinc-950/70 p-3 shadow-2xl shadow-black/20">
                                        <div class="absolute inset-0 rounded-[1.75rem] bg-gradient-to-br {{ $template['glow_classes'] }} opacity-0 blur-xl transition-opacity duration-500 group-hover:opacity-100"></div>

                                        <div class="relative rounded-[1.35rem] border border-white/10 bg-zinc-900/80 p-2">
                                            <div class="aspect-[3/4] overflow-hidden rounded-[1rem] bg-white p-3 shadow-inner shadow-black/5">
                                                @switch($template['id'])
                                                    @case('professional-classic')
                                                        <div class="flex h-full flex-col font-serif text-zinc-800">
                                                            <div class="border-b border-zinc-700 pb-2">
                                                                <div class="h-1.5 w-20 rounded-full bg-zinc-900"></div>
                                                                <div class="mt-1 h-1 w-full rounded-full bg-zinc-300"></div>
                                                                <div class="mt-1 flex gap-1">
                                                                    <span class="h-1 w-8 rounded-full bg-zinc-200"></span>
                                                                    <span class="h-1 w-6 rounded-full bg-zinc-200"></span>
                                                                    <span class="h-1 w-7 rounded-full bg-zinc-200"></span>
                                                                </div>
                                                            </div>
                                                            <div class="mt-3 space-y-2">
                                                                <div>
                                                                    <div class="h-1 w-12 rounded-full bg-zinc-700"></div>
                                                                    <div class="mt-1 h-1 w-full rounded-full bg-zinc-200"></div>
                                                                    <div class="mt-1 h-1 w-4/5 rounded-full bg-zinc-200"></div>
                                                                </div>
                                                                <div>
                                                                    <div class="h-1 w-10 rounded-full bg-zinc-700"></div>
                                                                    <div class="mt-1 h-1 w-full rounded-full bg-zinc-200"></div>
                                                                    <div class="mt-1 h-1 w-3/4 rounded-full bg-zinc-200"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @break

                                                    @case('technical-ats')
                                                        <div class="flex h-full flex-col text-zinc-900">
                                                            <div class="h-1.5 w-16 rounded-full bg-zinc-900"></div>
                                                            <div class="mt-1 h-1 w-24 rounded-full bg-zinc-300"></div>
                                                            <div class="mt-3 space-y-2">
                                                                <div>
                                                                    <div class="h-1 w-10 rounded-full bg-zinc-600"></div>
                                                                    <div class="mt-1 h-1 w-full rounded-full bg-zinc-200"></div>
                                                                    <div class="mt-1 h-1 w-full rounded-full bg-zinc-200"></div>
                                                                </div>
                                                                <div>
                                                                    <div class="h-1 w-12 rounded-full bg-zinc-600"></div>
                                                                    <div class="mt-1 flex items-center justify-between gap-2">
                                                                        <span class="h-1 w-12 rounded-full bg-zinc-900"></span>
                                                                        <span class="h-1 w-8 rounded-full bg-zinc-300"></span>
                                                                    </div>
                                                                    <div class="mt-1 h-1 w-5/6 rounded-full bg-zinc-200"></div>
                                                                </div>
                                                            </div>
                                                            <div class="mt-auto rounded-lg border border-zinc-200 p-2">
                                                                <div class="flex items-center justify-between gap-2">
                                                                    <span class="h-1 w-10 rounded-full bg-zinc-700"></span>
                                                                    <span class="h-1 w-8 rounded-full bg-emerald-400"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @break

                                                    @case('modern-minimal')
                                                        <div class="flex h-full flex-col text-zinc-800">
                                                            <div>
                                                                <div class="flex items-end gap-1">
                                                                    <div class="h-1.5 w-12 rounded-full bg-zinc-400"></div>
                                                                    <div class="h-1.5 w-10 rounded-full bg-zinc-900"></div>
                                                                </div>
                                                                <div class="mt-1 flex gap-1.5">
                                                                    <span class="h-1 w-6 rounded-full bg-zinc-200"></span>
                                                                    <span class="h-1 w-5 rounded-full bg-zinc-200"></span>
                                                                    <span class="h-1 w-5 rounded-full bg-zinc-200"></span>
                                                                </div>
                                                            </div>
                                                            <div class="mt-4 space-y-1.5">
                                                                <div class="h-1 w-full rounded-full bg-zinc-200"></div>
                                                                <div class="h-1 w-full rounded-full bg-zinc-200"></div>
                                                                <div class="h-1 w-4/5 rounded-full bg-zinc-200"></div>
                                                            </div>
                                                            <div class="mt-auto grid grid-cols-2 gap-2">
                                                                <div class="space-y-1">
                                                                    <div class="h-1 w-8 rounded-full bg-zinc-700"></div>
                                                                    <div class="h-1 w-full rounded-full bg-zinc-200"></div>
                                                                    <div class="h-1 w-4/5 rounded-full bg-zinc-200"></div>
                                                                </div>
                                                                <div class="space-y-1">
                                                                    <div class="h-1 w-8 rounded-full bg-zinc-700"></div>
                                                                    <div class="h-1 w-full rounded-full bg-zinc-200"></div>
                                                                    <div class="h-1 w-3/4 rounded-full bg-zinc-200"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @break

                                                    @case('creative')
                                                        <div class="flex h-full overflow-hidden rounded-xl border border-zinc-100">
                                                            <div class="w-[34%] bg-slate-800 p-2">
                                                                <div class="mx-auto h-6 w-6 rounded-full bg-slate-600"></div>
                                                                <div class="mt-2 h-1 w-full rounded-full bg-white/70"></div>
                                                                <div class="mt-1 h-1 w-3/4 rounded-full bg-white/40"></div>
                                                                <div class="mt-3 space-y-1">
                                                                    <div class="h-1 w-full rounded-full bg-teal-300/70"></div>
                                                                    <div class="h-1 w-4/5 rounded-full bg-white/30"></div>
                                                                    <div class="h-1 w-3/5 rounded-full bg-white/30"></div>
                                                                </div>
                                                            </div>
                                                            <div class="flex flex-1 flex-col bg-white p-2">
                                                                <div class="h-1.5 w-14 rounded-full bg-slate-800"></div>
                                                                <div class="mt-1 h-1 w-10 rounded-full bg-teal-500"></div>
                                                                <div class="mt-3 space-y-1.5">
                                                                    <div class="h-1 w-10 rounded-full bg-slate-700"></div>
                                                                    <div class="h-1 w-full rounded-full bg-zinc-200"></div>
                                                                    <div class="h-1 w-4/5 rounded-full bg-zinc-200"></div>
                                                                </div>
                                                                <div class="mt-auto space-y-1.5">
                                                                    <div class="h-1 w-9 rounded-full bg-slate-700"></div>
                                                                    <div class="h-1 w-full rounded-full bg-zinc-200"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @break

                                                    @case('executive')
                                                        <div class="flex h-full flex-col text-zinc-800">
                                                            <div class="border-b border-zinc-700 pb-2 text-center">
                                                                <div class="mx-auto h-1.5 w-20 rounded-full bg-zinc-900"></div>
                                                                <div class="mx-auto mt-1 h-1 w-12 rounded-full bg-zinc-300"></div>
                                                                <div class="mx-auto mt-2 flex w-fit gap-1">
                                                                    <span class="h-1 w-5 rounded-full bg-zinc-200"></span>
                                                                    <span class="h-1 w-5 rounded-full bg-zinc-200"></span>
                                                                    <span class="h-1 w-5 rounded-full bg-zinc-200"></span>
                                                                </div>
                                                            </div>
                                                            <div class="mt-3">
                                                                <div class="mx-auto h-1 w-14 rounded-full bg-zinc-700"></div>
                                                                <div class="mt-1 h-1 w-full rounded-full bg-zinc-200"></div>
                                                                <div class="mt-1 h-1 w-5/6 rounded-full bg-zinc-200"></div>
                                                            </div>
                                                            <div class="mt-3 flex flex-wrap justify-center gap-1">
                                                                <span class="h-3 w-8 rounded-full bg-zinc-100"></span>
                                                                <span class="h-3 w-10 rounded-full bg-zinc-100"></span>
                                                                <span class="h-3 w-7 rounded-full bg-zinc-100"></span>
                                                                <span class="h-3 w-9 rounded-full bg-zinc-100"></span>
                                                            </div>
                                                            <div class="mt-3 space-y-1.5">
                                                                <div class="h-1 w-12 rounded-full bg-zinc-700"></div>
                                                                <div class="h-1 w-full rounded-full bg-zinc-200"></div>
                                                                <div class="h-1 w-4/5 rounded-full bg-zinc-200"></div>
                                                            </div>
                                                        </div>
                                                        @break
                                                @endswitch
                                            </div>
                                        </div>

                                        <div class="relative mt-4 flex items-start justify-between gap-3">
                                            <div>
                                                <h3 class="text-sm font-semibold text-white">{{ $template['name'] }}</h3>
                                                <p class="mt-1 text-xs leading-relaxed text-zinc-400">{{ $template['description'] }}</p>
                                            </div>
                                            <span class="rounded-full border px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.18em] {{ $template['badge_classes'] }}">{{ $template['tag'] }}</span>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </section>

        {{-- Features: Glass cards --}}
        <section id="features" class="scroll-mt-16 relative py-20 lg:py-28 overflow-hidden">
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-emerald-600/10 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-blue-600/10 rounded-full blur-[100px]"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-emerald-500/10 text-emerald-400 text-sm font-semibold px-4 py-2 rounded-full mb-6 border border-emerald-500/20">
                        <x-ui::icon name="sparkles" size="sm" />
                        Features
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Everything You Need to Land Your Dream Job</h2>
                    <p class="text-lg text-zinc-400">Our comprehensive toolkit helps you create, optimize, and manage professional resumes.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach([
                        ['icon' => 'sparkles', 'title' => 'AI-Powered Writing', 'desc' => 'Generate compelling bullet points, summaries, and cover letters tailored to your industry and experience level.', 'color' => 'emerald', 'glow' => 'emerald'],
                        ['icon' => 'shield-check', 'title' => 'ATS Optimization', 'desc' => 'Ensure your CV passes Applicant Tracking Systems with keyword analysis, formatting checks, and real-time scoring.', 'color' => 'blue', 'glow' => 'blue'],
                        ['icon' => 'document-text', 'title' => 'Professional Templates', 'desc' => 'Choose from five distinct resume templates, from ATS-first layouts to creative and executive styles.', 'color' => 'purple', 'glow' => 'purple'],
                        ['icon' => 'eye', 'title' => 'Real-Time Preview', 'desc' => 'See exactly how your CV will look as you edit. Live preview updates instantly.', 'color' => 'amber', 'glow' => 'amber'],
                        ['icon' => 'lightbulb', 'title' => 'Smart Suggestions', 'desc' => 'Intelligent recommendations for skills, action verbs, and content improvements based on hiring trends.', 'color' => 'pink', 'glow' => 'pink'],
                        ['icon' => 'arrow-down-tray', 'title' => 'Export Ready', 'desc' => 'Download polished CVs in PDF format, perfectly formatted and ready to submit.', 'color' => 'teal', 'glow' => 'teal']
                    ] as $feature)
                    <div class="group relative h-full">
                        <div class="absolute inset-0 bg-gradient-to-br from-{{ $feature['glow'] }}-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl"></div>
                        <div class="relative flex h-full flex-col rounded-2xl border border-white/10 bg-white/5 p-8 backdrop-blur-xl transition-all duration-500 hover:-translate-y-2 hover:border-{{ $feature['color'] }}-500/30 group-hover:shadow-2xl group-hover:shadow-{{ $feature['color'] }}-500/10">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-{{ $feature['color'] }}-500/20 to-{{ $feature['color'] }}-500/5 flex items-center justify-center mb-6 border border-{{ $feature['color'] }}-500/20 group-hover:scale-110 group-hover:border-{{ $feature['color'] }}-500/40 transition-all duration-300">
                                <x-ui::icon name="{{ $feature['icon'] }}" size="lg" class="text-{{ $feature['color'] }}-400" />
                            </div>
                            <h3 class="text-lg font-bold text-white mb-3">{{ $feature['title'] }}</h3>
                            <p class="flex-1 text-sm leading-relaxed text-zinc-400">{{ $feature['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- How It Works: Glowing steps --}}
        <section class="relative py-20 lg:py-28 overflow-hidden">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-emerald-600/5 rounded-full blur-[100px]"></div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-blue-500/10 text-blue-400 text-sm font-semibold px-4 py-2 rounded-full mb-6 border border-blue-500/20">
                        <x-ui::icon name="zap" size="sm" />
                        How It Works
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Three Simple Steps</h2>
                    <p class="text-lg text-zinc-400">From zero to polished in minutes.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach([
                        ['step' => 1, 'title' => 'Fill Your Info', 'desc' => 'Enter your work experience, education, skills, and certifications with our guided form.', 'color' => 'emerald'],
                        ['step' => 2, 'title' => 'AI Enhances', 'desc' => 'Our AI optimizes your content with stronger action verbs and ATS-friendly formatting.', 'color' => 'blue'],
                        ['step' => 3, 'title' => 'Download & Apply', 'desc' => 'Export a polished PDF ready to send to employers.', 'color' => 'purple']
                    ] as $item)
                    <div class="group text-center">
                        <div class="relative mb-8">
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-{{ $item['color'] }}-500/20 to-{{ $item['color'] }}-500/5 backdrop-blur-xl flex items-center justify-center mx-auto border border-{{ $item['color'] }}-500/20 group-hover:border-{{ $item['color'] }}-500/40 group-hover:scale-110 transition-all duration-500">
                                <span class="text-3xl font-extrabold text-{{ $item['color'] }}-400">{{ $item['step'] }}</span>
                            </div>
                            <div class="absolute inset-0 rounded-2xl bg-{{ $item['color'] }}-500/10 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">{{ $item['title'] }}</h3>
                        <p class="text-zinc-400 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Stats: Glowing counters --}}
        <section class="relative py-16 lg:py-20 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-900/20 via-zinc-950 to-blue-900/20"></div>
            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach([
                        ['value' => '10,000+', 'label' => 'CVs Created', 'color' => 'emerald'],
                        ['value' => '95%', 'label' => 'ATS Pass Rate', 'color' => 'blue'],
                        ['value' => '5', 'label' => 'Resume Templates', 'color' => 'purple'],
                        ['value' => '4.9/5', 'label' => 'User Rating', 'color' => 'amber']
                    ] as $stat)
                    <div class="group text-center">
                        <div class="text-4xl md:text-5xl font-extrabold text-{{ $stat['color'] }}-400 mb-2 group-hover:text-{{ $stat['color'] }}-300 transition-colors duration-300" style="text-shadow: 0 0 40px currentColor;">{{ $stat['value'] }}</div>
                        <div class="text-sm text-zinc-500 font-medium">{{ $stat['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- About: Glass section --}}
        <section id="about" class="scroll-mt-16 relative py-20 lg:py-28 overflow-hidden">
            <div class="absolute top-0 left-0 w-[400px] h-[400px] bg-emerald-600/10 rounded-full blur-[120px]"></div>
            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <div>
                        <div class="inline-flex items-center gap-2 bg-emerald-500/10 text-emerald-400 text-sm font-semibold px-4 py-2 rounded-full mb-6 border border-emerald-500/20">
                            <x-ui::icon name="heart" size="sm" />
                            Our Mission
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 leading-tight">Empowering Careers Through Technology</h2>
                        <div class="space-y-4">
                            <p class="text-zinc-400 leading-relaxed">SeratyAI was founded in 2024 by a team of hiring managers, career coaches, and software engineers who saw a critical gap in the job application process.</p>
                            <p class="text-zinc-400 leading-relaxed">We built SeratyAI to level the playing field. Our AI analyzes job descriptions in real time and helps candidates present their experience in the most impactful way.</p>
                            <p class="text-zinc-400 leading-relaxed">Today, over <span class="text-emerald-400 font-bold">10,000 professionals</span> trust SeratyAI with a <span class="text-emerald-400 font-bold">95% ATS pass rate</span>.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach([
                            ['icon' => 'graduation-cap', 'title' => 'Industry Expertise', 'desc' => 'Data from real hiring managers.', 'color' => 'emerald'],
                            ['icon' => 'zap', 'title' => 'Lightning Fast', 'desc' => 'Polished CV in under 10 minutes.', 'color' => 'blue'],
                            ['icon' => 'heart', 'title' => 'User Focused', 'desc' => 'Designed around real feedback.', 'color' => 'purple'],
                            ['icon' => 'shield-check', 'title' => 'Privacy First', 'desc' => 'GDPR & SOC 2 compliant.', 'color' => 'amber']
                        ] as $f)
                        <div class="group bg-white/5 backdrop-blur-xl rounded-xl p-5 border border-white/10 hover:border-{{ $f['color'] }}-500/30 transition-all duration-300 hover:-translate-y-1">
                            <x-ui::icon name="{{ $f['icon'] }}" size="md" class="text-{{ $f['color'] }}-400 mb-3" />
                            <h3 class="text-sm font-bold text-white mb-1">{{ $f['title'] }}</h3>
                            <p class="text-xs text-zinc-500">{{ $f['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- Team --}}
        <section class="relative py-20 lg:py-28 overflow-hidden">
            <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-purple-600/10 rounded-full blur-[120px]"></div>
            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-purple-500/10 text-purple-400 text-sm font-semibold px-4 py-2 rounded-full mb-6 border border-purple-500/20">
                        <x-ui::icon name="users" size="sm" />
                        Team
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Meet the People Behind SeratyAI</h2>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach([
                        ['initials' => 'AJ', 'name' => 'Alex Johnson', 'role' => 'CEO & Co-Founder', 'desc' => '15 years talent acquisition.', 'color' => 'emerald'],
                        ['initials' => 'MW', 'name' => 'Maria Williams', 'role' => 'CTO & Co-Founder', 'desc' => 'NLP & ML specialist.', 'color' => 'blue'],
                        ['initials' => 'DL', 'name' => 'David Lee', 'role' => 'Head of Product', 'desc' => 'Career platform veteran.', 'color' => 'purple'],
                        ['initials' => 'RN', 'name' => 'Rachel Nguyen', 'role' => 'Head of Design', 'desc' => 'Award-winning UX.', 'color' => 'amber']
                    ] as $m)
                    <div class="group text-center">
                        <div class="relative mb-5">
                            <div class="w-20 h-20 mx-auto rounded-2xl bg-gradient-to-br from-{{ $m['color'] }}-500/20 to-{{ $m['color'] }}-500/5 backdrop-blur-xl flex items-center justify-center border border-{{ $m['color'] }}-500/20 group-hover:border-{{ $m['color'] }}-500/40 group-hover:scale-110 transition-all duration-300">
                                <span class="text-xl font-bold text-{{ $m['color'] }}-400">{{ $m['initials'] }}</span>
                            </div>
                            <div class="absolute inset-0 rounded-2xl bg-{{ $m['color'] }}-500/10 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <h3 class="text-sm font-bold text-white mb-0.5">{{ $m['name'] }}</h3>
                        <p class="text-xs font-medium text-{{ $m['color'] }}-400 mb-1">{{ $m['role'] }}</p>
                        <p class="text-xs text-zinc-500">{{ $m['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Testimonials: Glass cards --}}
        <section class="relative py-20 lg:py-28 overflow-hidden">
            <div class="absolute top-0 left-1/4 w-[400px] h-[400px] bg-amber-600/10 rounded-full blur-[120px]"></div>
            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-amber-500/10 text-amber-400 text-sm font-semibold px-4 py-2 rounded-full mb-6 border border-amber-500/20">
                        <x-ui::icon name="chat-bubble-left-right" size="sm" />
                        Testimonials
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Trusted by Professionals Worldwide</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach([
                        ['name' => 'Sarah Rodriguez', 'role' => 'Software Engineer at Google', 'initials' => 'SR', 'color' => 'emerald', 'text' => 'SeratyAI completely transformed my job search. The AI suggestions made my experience descriptions so much stronger.'],
                        ['name' => 'Marcus Kim', 'role' => 'Product Manager at Stripe', 'initials' => 'MK', 'color' => 'blue', 'text' => 'As a career changer, I struggled to present my transferable skills. SeratyAI helped me reframe my experience effectively.'],
                        ['name' => 'Elena Petrova', 'role' => 'UX Designer at Figma', 'initials' => 'EP', 'color' => 'purple', 'text' => 'The templates are stunning and the real-time preview is incredibly useful. Recruiters frequently compliment my CV format.']
                    ] as $t)
                    <div class="group relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-{{ $t['color'] }}-500/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-xl"></div>
                        <div class="relative bg-white/5 backdrop-blur-xl rounded-2xl p-8 border border-white/10 hover:border-{{ $t['color'] }}-500/30 transition-all duration-500 hover:-translate-y-2">
                            <div class="flex items-center gap-1 mb-5">
                                @for($i = 0; $i < 5; $i++)
                                <x-ui::icon name="star" size="sm" class="text-amber-400" />
                                @endfor
                            </div>
                            <p class="text-sm text-zinc-300 leading-relaxed mb-6">"{{ $t['text'] }}"</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-{{ $t['color'] }}-500/20 flex items-center justify-center border border-{{ $t['color'] }}-500/20">
                                    <span class="text-xs font-bold text-{{ $t['color'] }}-400">{{ $t['initials'] }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-white">{{ $t['name'] }}</div>
                                    <div class="text-xs text-zinc-500">{{ $t['role'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Pricing: Glass tiers --}}
        <section id="pricing" class="scroll-mt-16 relative py-20 lg:py-28 overflow-hidden">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-emerald-600/5 rounded-full blur-[150px]"></div>
            <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <div class="inline-flex items-center gap-2 bg-purple-500/10 text-purple-400 text-sm font-semibold px-4 py-2 rounded-full mb-6 border border-purple-500/20">
                        <x-ui::icon name="briefcase" size="sm" />
                        Pricing
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Simple, Transparent Pricing</h2>
                    <p class="text-lg text-zinc-400">Start free and upgrade when you need more.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach([
                        ['name' => 'Free', 'price' => '$0', 'period' => '/month', 'btn' => 'Get Started', 'features' => ['1 CV with basic template', 'PDF export with watermark', 'Basic ATS check', '5 AI suggestions per CV'], 'unavailable' => ['Unlimited CVs', 'AI cover letter generator'], 'popular' => false],
                        ['name' => 'Pro', 'price' => '$9', 'period' => '/month', 'btn' => 'Start Free Trial', 'features' => ['Unlimited CVs', 'Access all 5 resume templates', 'Clean PDF export', 'Advanced ATS optimization', 'Unlimited AI suggestions', 'AI cover letter generator'], 'unavailable' => [], 'popular' => true],
                        ['name' => 'Enterprise', 'price' => '$29', 'period' => '/month', 'btn' => 'Contact Sales', 'features' => ['Everything in Pro', 'Team management', 'Custom branding', 'Priority support', 'API access', 'SSO & security'], 'unavailable' => [], 'popular' => false]
                    ] as $plan)
                    <div class="relative group {{ $plan['popular'] ? 'md:-mt-4 md:mb-[-1rem]' : '' }}">
                        @if($plan['popular'])
                        <div class="absolute inset-0 bg-gradient-to-b from-emerald-500/20 to-emerald-500/5 rounded-2xl blur-xl"></div>
                        @endif
                        <div class="relative bg-white/5 backdrop-blur-xl rounded-2xl p-8 border {{ $plan['popular'] ? 'border-emerald-500/30' : 'border-white/10' }} hover:border-emerald-500/30 transition-all duration-500 hover:-translate-y-2 h-full flex flex-col">
                            @if($plan['popular'])
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                                <span class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-lg shadow-emerald-500/30">Most Popular</span>
                            </div>
                            @endif
                            <h3 class="text-xl font-bold text-white mb-1">{{ $plan['name'] }}</h3>
                            <div class="mt-4 mb-8">
                                <span class="text-5xl font-extrabold text-white">{{ $plan['price'] }}</span>
                                <span class="text-zinc-500">{{ $plan['period'] }}</span>
                            </div>
                            <x-ui::button variant="{{ $plan['popular'] ? 'primary' : 'outline' }}" :href="route('register')" class="w-full mb-8 py-3 {{ !$plan['popular'] ? 'border-white/20 text-white hover:bg-white/10' : '' }}">{{ $plan['btn'] }}</x-ui::button>
                            <ul class="space-y-3 mt-auto">
                                @foreach($plan['features'] as $f)
                                <li class="flex items-start gap-3 text-sm text-zinc-300">
                                    <x-ui::icon name="check-circle" size="md" class="text-emerald-400 shrink-0 mt-0.5" />
                                    {{ $f }}
                                </li>
                                @endforeach
                                @foreach($plan['unavailable'] as $f)
                                <li class="flex items-start gap-3 text-sm text-zinc-600">
                                    <x-ui::icon name="x-circle" size="md" class="text-zinc-700 shrink-0 mt-0.5" />
                                    {{ $f }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- FAQ: Glass accordion --}}
        <section id="faq" class="scroll-mt-16 relative py-20 lg:py-28 overflow-hidden">
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-emerald-600/10 rounded-full blur-[120px]"></div>
            <div class="relative mx-auto max-w-3xl px-6 lg:px-8">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center gap-2 bg-emerald-500/10 text-emerald-400 text-sm font-semibold px-4 py-2 rounded-full mb-6 border border-emerald-500/20">
                        <x-ui::icon name="help" size="sm" />
                        FAQ
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white">Frequently Asked Questions</h2>
                </div>

                <div class="space-y-3" x-data="{ open: null }">
                    @foreach([
                        ['id' => 'd4q1', 'q' => 'What is SeratyAI?', 'a' => 'An AI-powered CV builder that creates professional, ATS-optimized resumes with real-time compatibility scoring.'],
                        ['id' => 'd4q2', 'q' => 'Is my data secure?', 'a' => 'Yes. All data encrypted at rest and in transit. We never sell or share your data. GDPR and SOC 2 compliant.'],
                        ['id' => 'd4q3', 'q' => 'Is there a free trial?', 'a' => 'Every new account gets a 7-day Pro trial. No credit card required.'],
                        ['id' => 'd4q4', 'q' => 'Can I cancel anytime?', 'a' => 'Yes. Cancel anytime from settings. No fees.'],
                        ['id' => 'd4q5', 'q' => 'What export formats?', 'a' => 'High-quality PDF, universally accepted.'],
                        ['id' => 'd4q6', 'q' => 'Multiple CVs?', 'a' => 'Pro and Enterprise users create unlimited CVs.']
                    ] as $faq)
                    <div class="overflow-hidden rounded-xl border bg-white/5 backdrop-blur-xl transition-all duration-300" x-bind:class="open === '{{ $faq['id'] }}' ? 'border-emerald-500/30 bg-emerald-500/5 shadow-lg shadow-emerald-500/10' : 'border-white/10 hover:border-emerald-500/20'">
                        <button type="button" @click="open = open === '{{ $faq['id'] }}' ? null : '{{ $faq['id'] }}'" x-bind:aria-expanded="open === '{{ $faq['id'] }}'" class="flex w-full items-center justify-between px-6 py-5 text-left transition-colors hover:bg-white/5">
                            <span class="text-sm font-semibold text-white pr-4">{{ $faq['q'] }}</span>
                            <x-ui::icon name="chevron-down" size="md" class="text-zinc-500 shrink-0 transition-transform duration-300" x-bind:class="open === '{{ $faq['id'] }}' ? 'rotate-180 text-emerald-400' : ''" />
                        </button>
                        <div x-show="open === '{{ $faq['id'] }}'" x-collapse x-cloak class="overflow-hidden">
                            <div class="border-t border-white/10 px-6 pb-5 pt-4 text-sm leading-relaxed text-zinc-400">{{ $faq['a'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA: Glowing gradient --}}
        <section class="relative py-20 lg:py-28 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-900/40 via-zinc-950 to-blue-900/40"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-emerald-500/15 rounded-full blur-[120px]"></div>
            <div class="relative mx-auto max-w-4xl px-6 lg:px-8 text-center">
                <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6 leading-tight">Ready to Stand Out?</h2>
                <p class="text-lg text-zinc-400 max-w-2xl mx-auto mb-10">Join 10,000+ professionals who transformed their job search. Your next career move starts here.</p>
                <x-ui::button variant="primary" :href="route('register')" icon="arrow-right" size="lg" class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-white shadow-2xl shadow-emerald-500/30 hover:-translate-y-1 transition-all duration-300 px-10 py-4">
                    Create Your CV Now
                </x-ui::button>
            </div>
        </section>
    </main>
</x-layouts::landing>
