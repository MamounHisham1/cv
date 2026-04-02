<div wire:poll.keep-alive="loadData" class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button
        type="button"
        @click="open = !open"
        class="relative p-2 text-zinc-400 hover:text-zinc-100 transition-colors"
    >
        <x-ui::icon name="bell" size="lg" />
        @if ($unreadCount > 0)
            <span class="absolute -top-0.5 -right-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 z-50 mt-2 w-80 origin-top-right rounded-lg border border-zinc-800 bg-zinc-900 shadow-xl"
        style="display: none;"
    >
        <div class="flex items-center justify-between border-b border-zinc-800 px-4 py-3">
            <h3 class="text-sm font-semibold text-zinc-100">Notifications</h3>
            @if ($unreadCount > 0)
                <button
                    type="button"
                    wire:click="markAllAsRead"
                    class="text-xs text-emerald-400 hover:text-emerald-300"
                >
                    Mark all as read
                </button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse ($notifications as $notification)
                <button
                    type="button"
                    wire:click="markAsRead('{{ $notification['id'] }}')"
                    class="w-full border-b border-zinc-800/50 px-4 py-3 text-left transition-colors hover:bg-zinc-800/50 {{ is_null($notification['read_at']) ? 'bg-zinc-800/30' : '' }}"
                >
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <x-ui::icon :name="$this->getNotificationIcon($notification['type'])" size="sm" class="{{ is_null($notification['read_at']) ? 'text-emerald-400' : 'text-zinc-500' }}" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-zinc-100 truncate">
                                {{ $this->getNotificationTitle($notification['data'], $notification['type']) }}
                            </p>
                            <p class="text-xs text-zinc-500 mt-1">
                                {{ $this->getRelativeTime($notification['created_at']) }}
                            </p>
                        </div>
                        @if (is_null($notification['read_at']))
                            <span class="h-2 w-2 flex-shrink-0 rounded-full bg-emerald-400 mt-1"></span>
                        @endif
                    </div>
                </button>
            @empty
                <div class="px-4 py-8 text-center">
                    <x-ui::icon name="bell" size="lg" class="mx-auto text-zinc-600" />
                    <p class="mt-2 text-sm text-zinc-500">No notifications yet</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
