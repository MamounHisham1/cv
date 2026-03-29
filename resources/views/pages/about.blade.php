<x-layouts::landing>
    <main>
        <section class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 py-20 lg:py-28">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">About SeratyAI</h1>
                <p class="text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto leading-relaxed">
                    We are on a mission to help every job seeker present their best professional self. Our AI-powered platform makes building standout resumes accessible to everyone.
                </p>
            </div>
        </section>

        <section class="py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <div>
                        <x-ui::badge variant="brand" class="mb-4">Our Mission</x-ui::badge>
                        <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-6">
                            Empowering Careers Through Technology
                        </h2>
                        <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed mb-4">
                            SeratyAI was founded in 2024 by a team of hiring managers, career coaches, and software engineers who saw a critical gap in the job application process. Millions of talented professionals were being filtered out by ATS systems, not because they lacked qualifications, but because their CVs were not optimized for the technology screening them.
                        </p>
                        <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed mb-4">
                            We built SeratyAI to level the playing field. Our AI analyzes job descriptions in real time, identifies the keywords and competencies that ATS systems prioritize, and helps candidates present their experience in the most impactful way possible.
                        </p>
                        <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Today, over 10,000 professionals trust SeratyAI to power their job searches, and we are proud to report a 95% ATS pass rate among our users. We are just getting started.
                        </p>
                    </div>
                    <div class="flex items-center justify-center">
                        <div class="relative w-full max-w-md">
                            <div class="aspect-square rounded-2xl bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/30 dark:to-emerald-800/30 flex items-center justify-center">
                                <div class="text-center p-8">
                                    <x-ui::icon name="trophy" class="text-emerald-600 dark:text-emerald-400 mx-auto mb-6" style="width: 80px; height: 80px;" />
                                    <div class="text-5xl font-bold text-emerald-600 dark:text-emerald-400 mb-2">10,000+</div>
                                    <div class="text-lg text-zinc-700 dark:text-zinc-300 font-medium">Careers Transformed</div>
                                </div>
                            </div>
                            <div class="absolute -bottom-4 -right-4 bg-white dark:bg-zinc-800 rounded-xl shadow-lg p-4 border border-zinc-200 dark:border-zinc-700">
                                <div class="flex items-center gap-2">
                                    <x-ui::icon name="star" size="md" class="text-amber-400" />
                                    <span class="text-sm font-semibold text-zinc-900 dark:text-white">4.9/5 Rating</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 lg:py-28 bg-zinc-50 dark:bg-zinc-900">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <x-ui::badge variant="brand" class="mb-4">Why Choose Us</x-ui::badge>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Built by Experts, for Professionals
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        Our platform combines deep industry expertise with cutting-edge technology to deliver results that matter.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                    <x-ui::card class="hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-200">
                        <div class="p-6 lg:p-8 text-center">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mx-auto mb-5">
                                <x-ui::icon name="graduation-cap" size="lg" class="text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Industry Expertise</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Our templates and AI models are trained on data from real hiring managers across tech, finance, healthcare, and more.
                            </p>
                        </div>
                    </x-ui::card>

                    <x-ui::card class="hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-200">
                        <div class="p-6 lg:p-8 text-center">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mx-auto mb-5">
                                <x-ui::icon name="zap" size="lg" class="text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Lightning Fast</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Build a polished, professional CV in under 10 minutes. Our streamlined workflow eliminates the back-and-forth of traditional resume writing.
                            </p>
                        </div>
                    </x-ui::card>

                    <x-ui::card class="hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-200">
                        <div class="p-6 lg:p-8 text-center">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mx-auto mb-5">
                                <x-ui::icon name="heart" size="lg" class="text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">User Focused</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Every feature is designed around real user feedback. We continuously improve based on what job seekers actually need to succeed.
                            </p>
                        </div>
                    </x-ui::card>

                    <x-ui::card class="hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-200">
                        <div class="p-6 lg:p-8 text-center">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mx-auto mb-5">
                                <x-ui::icon name="shield-check" size="lg" class="text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Privacy First</h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                Your data stays yours. We use end-to-end encryption, never sell your information, and comply with GDPR and SOC 2 standards.
                            </p>
                        </div>
                    </x-ui::card>
                </div>
            </div>
        </section>

        <section class="py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <x-ui::badge variant="brand" class="mb-4">Our Team</x-ui::badge>
                    <h2 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Meet the People Behind SeratyAI
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        A passionate team of engineers, designers, and career experts dedicated to transforming how people apply for jobs.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                    <div class="text-center">
                        <div class="w-24 h-24 rounded-full bg-emerald-600 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-white">AJ</span>
                        </div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Alex Johnson</h3>
                        <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium mb-2">CEO & Co-Founder</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Former VP of Talent Acquisition at a Fortune 500 company with 15 years of recruiting experience.
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="w-24 h-24 rounded-full bg-blue-600 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-white">MW</span>
                        </div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Maria Williams</h3>
                        <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium mb-2">CTO & Co-Founder</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Full-stack engineer with a background in NLP and machine learning, previously at a leading AI research lab.
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="w-24 h-24 rounded-full bg-purple-600 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-white">DL</span>
                        </div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">David Lee</h3>
                        <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium mb-2">Head of Product</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Product leader with experience building career platforms used by millions of professionals worldwide.
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="w-24 h-24 rounded-full bg-amber-600 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-white">RN</span>
                        </div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Rachel Nguyen</h3>
                        <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium mb-2">Head of Design</p>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                            Award-winning UX designer specializing in document design and user experience for productivity tools.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts::landing>
