<div class="space-y-6">
    <div>
        <h2 class="text-lg font-semibold text-zinc-100">Notification Preferences</h2>
        <p class="text-sm text-zinc-400 mt-1">Manage how you receive notifications</p>
    </div>

    <div class="space-y-4">
        <div class="flex items-center justify-between rounded-lg border border-zinc-800 bg-zinc-900/50 px-4 py-3">
            <div>
                <p class="text-sm font-medium text-zinc-100">Email Notifications</p>
                <p class="text-xs text-zinc-400">Receive notifications via email</p>
            </div>
            <label class="relative inline-flex cursor-pointer items-center">
                <input
                    type="checkbox"
                    wire:model.live="emailEnabled"
                    class="peer sr-only"
                >
                <div class="peer h-6 w-11 rounded-full bg-zinc-700 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-zinc-600 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white rtl:peer-checked:after:-translate-x-full"></div>
            </label>
        </div>

        <div class="flex items-center justify-between rounded-lg border border-zinc-800 bg-zinc-900/50 px-4 py-3">
            <div>
                <p class="text-sm font-medium text-zinc-100">Browser Push Notifications</p>
                <p class="text-xs text-zinc-400">Receive push notifications in your browser</p>
            </div>
            <label class="relative inline-flex cursor-pointer items-center">
                <input
                    type="checkbox"
                    wire:model.live="pushEnabled"
                    class="peer sr-only"
                >
                <div class="peer h-6 w-11 rounded-full bg-zinc-700 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-zinc-600 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white rtl:peer-checked:after:-translate-x-full"></div>
            </label>
        </div>
    </div>

    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-zinc-300">Notification Types</h3>

        <div class="flex items-center justify-between rounded-lg border border-zinc-800 bg-zinc-900/50 px-4 py-3">
            <div class="flex items-center gap-3">
                <x-ui::icon name="check-circle" size="sm" class="text-emerald-400" />
                <div>
                    <p class="text-sm font-medium text-zinc-100">CV Evaluations</p>
                    <p class="text-xs text-zinc-400">When your CV evaluation is complete</p>
                </div>
            </div>
            <label class="relative inline-flex cursor-pointer items-center">
                <input
                    type="checkbox"
                    wire:model.live="evaluationNotifications"
                    class="peer sr-only"
                >
                <div class="peer h-6 w-11 rounded-full bg-zinc-700 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-zinc-600 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white rtl:peer-checked:after:-translate-x-full"></div>
            </label>
        </div>

        <div class="flex items-center justify-between rounded-lg border border-zinc-800 bg-zinc-900/50 px-4 py-3">
            <div class="flex items-center gap-3">
                <x-ui::icon name="alert-triangle" size="sm" class="text-amber-400" />
                <div>
                    <p class="text-sm font-medium text-zinc-100">Low Credits</p>
                    <p class="text-xs text-zinc-400">When your credits are running low</p>
                </div>
            </div>
            <label class="relative inline-flex cursor-pointer items-center">
                <input
                    type="checkbox"
                    wire:model.live="creditNotifications"
                    class="peer sr-only"
                >
                <div class="peer h-6 w-11 rounded-full bg-zinc-700 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-zinc-600 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white rtl:peer-checked:after:-translate-x-full"></div>
            </label>
        </div>

        <div class="flex items-center justify-between rounded-lg border border-zinc-800 bg-zinc-900/50 px-4 py-3">
            <div class="flex items-center gap-3">
                <x-ui::icon name="users" size="sm" class="text-blue-400" />
                <div>
                    <p class="text-sm font-medium text-zinc-100">Referrals</p>
                    <p class="text-xs text-zinc-400">When someone joins using your referral</p>
                </div>
            </div>
            <label class="relative inline-flex cursor-pointer items-center">
                <input
                    type="checkbox"
                    wire:model.live="referralNotifications"
                    class="peer sr-only"
                >
                <div class="peer h-6 w-11 rounded-full bg-zinc-700 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-zinc-600 after:bg-white after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-full peer-checked:after:border-white rtl:peer-checked:after:-translate-x-full"></div>
            </label>
        </div>
    </div>
</div>
