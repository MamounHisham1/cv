<x-layouts::landing>
    <main class="relative">
        {{-- Animated Background --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 right-10 w-72 h-72 bg-emerald-200 dark:bg-emerald-900/20 rounded-full blur-3xl animate-pulse-glow opacity-50"></div>
            <div class="absolute bottom-40 left-10 w-96 h-96 bg-blue-200 dark:bg-blue-900/20 rounded-full blur-3xl animate-pulse-glow opacity-50" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-r from-emerald-500/5 to-blue-500/5 rounded-full blur-3xl"></div>
        </div>

        {{-- Hero Section --}}
        <section class="relative bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 py-24 lg:py-32 overflow-hidden">
            {{-- Pattern Overlay --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 48px 48px;"></div>
            </div>

            {{-- Floating Particles --}}
            <div class="absolute top-16 left-1/4 w-3 h-3 bg-white rounded-full animate-float opacity-30"></div>
            <div class="absolute bottom-24 right-1/3 w-2 h-2 bg-emerald-200 rounded-full animate-float-reverse opacity-40" style="animation-delay: 1.5s;"></div>
            <div class="absolute top-1/3 right-16 w-4 h-4 bg-emerald-300 rounded-full animate-float-slow opacity-25" style="animation-delay: 0.8s;"></div>
            <div class="absolute bottom-1/3 left-10 w-2 h-2 bg-white rounded-full animate-float opacity-30" style="animation-delay: 2.5s;"></div>

            <div class="relative mx-auto max-w-4xl px-6 lg:px-8 text-center">
                <div class="animate-slide-up inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm text-white text-sm font-medium px-5 py-2.5 rounded-full mb-6 border border-white/20 shadow-lg">
                    <x-ui::icon name="chat-bubble-left-right" size="sm" />
                    Get in Touch
                </div>
                <h1 class="animate-slide-up delay-100 text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    We're Here to Help
                </h1>
                <p class="animate-slide-up delay-200 text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto leading-relaxed">
                    Have a question, suggestion, or need help? Our team typically responds within 24 hours.
                </p>
            </div>
        </section>

        {{-- Contact Content --}}
        <section class="relative py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-12">
                    {{-- Contact Form --}}
                    <div class="lg:col-span-3">
                        <x-ui::card class="relative overflow-hidden border-2 border-emerald-200 dark:border-emerald-800 hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500">
                            {{-- Decorative Elements --}}
                            <div class="absolute top-0 right-0 w-48 h-48 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-full blur-2xl transform translate-x-1/2 -translate-y-1/2"></div>
                            <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-emerald-500/10 to-transparent rounded-full blur-2xl transform -translate-x-1/2 translate-y-1/2"></div>

                            <div class="relative p-6 lg:p-8">
                                <div class="mb-8">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                            <x-ui::icon name="paper-airplane" size="md" class="text-white" />
                                        </div>
                                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Send Us a Message</h2>
                                    </div>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 ml-13">Fill out the form below and we will get back to you as soon as possible.</p>
                                </div>

                                <livewire:contact-form />
                            </div>
                        </x-ui::card>
                    </div>

                    {{-- Contact Information Cards --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Email Card --}}
                        <x-ui::card class="group hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/10">
                            <div class="p-6 lg:p-8">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                                        <x-ui::icon name="envelope" size="lg" class="text-white" />
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-semibold text-zinc-900 dark:text-white mb-1">Email Us</div>
                                        <a href="mailto:support@seratyai.com" class="text-base text-emerald-600 dark:text-emerald-400 hover:underline font-medium">support@seratyai.com</a>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2">We'll respond within 24 hours</p>
                                    </div>
                                </div>
                            </div>
                        </x-ui::card>

                        {{-- Phone Card --}}
                        <x-ui::card class="group hover:border-blue-300 dark:hover:border-blue-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/10">
                            <div class="p-6 lg:p-8">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shrink-0 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                                        <x-ui::icon name="phone" size="lg" class="text-white" />
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-semibold text-zinc-900 dark:text-white mb-1">Call Us</div>
                                        <a href="tel:+15551234567" class="text-base text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 font-medium">+1 (555) 123-4567</a>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2">Mon-Fri, 9AM-6PM PST</p>
                                    </div>
                                </div>
                            </div>
                        </x-ui::card>

                        {{-- Office Location Card --}}
                        <x-ui::card class="group hover:border-purple-300 dark:hover:border-purple-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-purple-500/10">
                            <div class="p-6 lg:p-8">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shrink-0 shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300">
                                        <x-ui::icon name="map-pin" size="lg" class="text-white" />
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-semibold text-zinc-900 dark:text-white mb-1">Visit Us</div>
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">123 Innovation Drive<br>San Francisco, CA 94107</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2">By appointment only</p>
                                    </div>
                                </div>
                            </div>
                        </x-ui::card>

                        {{-- Office Hours Card --}}
                        <x-ui::card class="group hover:border-amber-300 dark:hover:border-amber-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-amber-500/10">
                            <div class="p-6 lg:p-8">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                                        <x-ui::icon name="clock" size="md" class="text-white" />
                                    </div>
                                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Office Hours</h3>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center p-3 rounded-lg bg-zinc-50 dark:bg-zinc-800/50">
                                        <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Monday - Friday</span>
                                        <span class="text-sm font-bold text-zinc-900 dark:text-white">9:00 AM - 6:00 PM PST</span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 rounded-lg bg-zinc-50 dark:bg-zinc-800/50">
                                        <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Saturday</span>
                                        <span class="text-sm font-bold text-zinc-900 dark:text-white">10:00 AM - 2:00 PM PST</span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                                        <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Sunday</span>
                                        <span class="text-sm font-bold text-red-600 dark:text-red-400">Closed</span>
                                    </div>
                                </div>
                            </div>
                        </x-ui::card>

                        {{-- Social Media Card --}}
                        <x-ui::card class="group hover:border-pink-300 dark:hover:border-pink-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-pink-500/10">
                            <div class="p-6 lg:p-8">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center shadow-lg shadow-pink-500/30">
                                        <x-ui::icon name="heart" size="md" class="text-white" />
                                    </div>
                                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Follow Us</h3>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="#" class="group/social w-11 h-11 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 dark:text-zinc-400 hover:bg-gradient-to-br hover:from-emerald-500 hover:to-emerald-600 hover:text-white transition-all duration-300 hover:scale-110 shadow-md">
                                        <x-ui::icon name="globe" size="lg" />
                                    </a>
                                    <a href="#" class="group/social w-11 h-11 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 dark:text-zinc-400 hover:bg-gradient-to-br hover:from-blue-500 hover:to-blue-600 hover:text-white transition-all duration-300 hover:scale-110 shadow-md">
                                        <x-ui::icon name="code-2" size="lg" />
                                    </a>
                                    <a href="#" class="group/social w-11 h-11 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 dark:text-zinc-400 hover:bg-gradient-to-br hover:from-purple-500 hover:to-purple-600 hover:text-white transition-all duration-300 hover:scale-110 shadow-md">
                                        <x-ui::icon name="users" size="lg" />
                                    </a>
                                </div>
                            </div>
                        </x-ui::card>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts::landing>
