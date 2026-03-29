<x-layouts::landing>
    <main>
        {{-- Hero --}}
        <section class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 py-20 lg:py-28">
            <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">Get in Touch</h1>
                <p class="text-lg md:text-xl text-emerald-100 max-w-2xl mx-auto leading-relaxed">
                    Have a question, suggestion, or need help? We would love to hear from you. Our team typically responds within 24 hours.
                </p>
            </div>
        </section>

        <section class="py-20 lg:py-28 bg-white dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 lg:gap-16">
                    {{-- Contact Form --}}
                    <div class="lg:col-span-3">
                        <flux:card>
                            <div class="p-6 lg:p-8">
                                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-1">Send Us a Message</h2>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-8">Fill out the form below and we will get back to you as soon as possible.</p>

                                <livewire:contact-form />
                            </div>
                        </flux:card>
                    </div>

                    {{-- Contact Info --}}
                    <div class="lg:col-span-2 space-y-6">
                        <flux:card>
                            <div class="p-6 lg:p-8">
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-6">Contact Information</h3>
                                <div class="space-y-5">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
                                            <flux:icon name="mail" class="size-5 text-emerald-600 dark:text-emerald-400" variant="solid" />
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-zinc-900 dark:text-white">Email</div>
                                            <a href="mailto:support@cvforge.com" class="text-sm text-emerald-600 dark:text-emerald-400 hover:underline">support@cvforge.com</a>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
                                            <flux:icon name="phone" class="size-5 text-emerald-600 dark:text-emerald-400" variant="solid" />
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-zinc-900 dark:text-white">Phone</div>
                                            <a href="tel:+15551234567" class="text-sm text-zinc-600 dark:text-zinc-400">+1 (555) 123-4567</a>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
                                            <flux:icon name="map-pin" class="size-5 text-emerald-600 dark:text-emerald-400" variant="solid" />
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-zinc-900 dark:text-white">Office</div>
                                            <p class="text-sm text-zinc-600 dark:text-zinc-400">123 Innovation Drive<br>San Francisco, CA 94107</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </flux:card>

                        <flux:card>
                            <div class="p-6 lg:p-8">
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-6">Office Hours</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-zinc-600 dark:text-zinc-400">Monday - Friday</span>
                                        <span class="font-medium text-zinc-900 dark:text-white">9:00 AM - 6:00 PM PST</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-zinc-600 dark:text-zinc-400">Saturday</span>
                                        <span class="font-medium text-zinc-900 dark:text-white">10:00 AM - 2:00 PM PST</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-zinc-600 dark:text-zinc-400">Sunday</span>
                                        <span class="font-medium text-zinc-900 dark:text-white">Closed</span>
                                    </div>
                                </div>
                            </div>
                        </flux:card>

                        <flux:card>
                            <div class="p-6 lg:p-8">
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-6">Follow Us</h3>
                                <div class="flex items-center gap-3">
                                    <a href="#" class="w-10 h-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 dark:text-zinc-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                        <flux:icon name="globe" class="size-5" />
                                    </a>
                                    <a href="#" class="w-10 h-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 dark:text-zinc-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                        <flux:icon name="code-bracket" class="size-5" />
                                    </a>
                                    <a href="#" class="w-10 h-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-500 dark:text-zinc-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                                        <flux:icon name="users" class="size-5" />
                                    </a>
                                </div>
                            </div>
                        </flux:card>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts::landing>
