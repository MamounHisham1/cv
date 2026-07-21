<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Status banner --}}
        <x-filament::section>
            <x-slot:name>{{ __("Bot Status") }}</x-slot:name>
            @if ($botConfigured)
                <p class="text-sm text-success-600 dark:text-success-400">
                    <span class="font-semibold">{{ __("Configured.") }}</span>{{ __("The Telegram bot token is set and ready.") }}</p>
            @else
                <p class="text-sm text-danger-600 dark:text-danger-400">
                    <span class="font-semibold">{{ __("Not configured.") }}</span>{{ __("Set the") }}<code>{{ __("TELEGRAM_BOT_TOKEN") }}</code>{{ __("environment variable.") }}</p>
            @endif

            <dl class="mt-3 space-y-1 text-sm">
                <div>
                    <dt class="inline font-medium text-gray-700 dark:text-gray-300">{{ __("Webhook URL:") }}</dt>
                    <dd class="ml-1 inline font-mono text-xs">{{ $webhookUrl }}</dd>
                </div>
                <div>
                    <dt class="inline font-medium text-gray-700 dark:text-gray-300">{{ __("Admin chat:") }}</dt>
                    <dd class="ml-1 inline">
                        @if ($adminChat ?? null)
                            <span class="text-success-600 dark:text-success-400">{{ __("Connected") }}</span>
                            ({{ $adminChat->username ? '@' . $adminChat->username : ($adminChat->first_name ?: $adminChat->chat_id) }})
                        @else
                            <span class="text-warning-600 dark:text-warning-400">{{ __("No authorized chat yet.") }}</span>
                            Start a conversation with the bot and authorize this chat.
                        @endif
                    </dd>
                </div>
            </dl>
        </x-filament::section>

        {{-- API tokens --}}
        <x-filament::section>
            <x-slot:name>{{ __("API Keys") }}</x-slot:name>
            <x-slot:description>{{ __("Plaintext keys are shown only once at generation time.") }}</x-slot:description>

            @if ($tokens->isEmpty())<p class="text-sm text-gray-500 dark:text-gray-400">{{ __("No API keys yet. Use") }}<span class="font-medium">{{ __("Generate API Key") }}</span>{{ __("above.") }}</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b text-xs uppercase text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-2 py-2">{{ __("Label") }}</th>
                                <th class="px-2 py-2">{{ __("Created") }}</th>
                                <th class="px-2 py-2">{{ __("Last used") }}</th>
                                <th class="px-2 py-2">{{ __("Status") }}</th>
                                <th class="px-2 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-800">
                            @foreach ($tokens as $token)
                                @php
                                    $revoked = method_exists($token, 'isRevoked') ? $token->isRevoked() : filled($token->revoked_at ?? null);
                                @endphp
                                <tr>
                                    <td class="px-2 py-2 font-medium">{{ $token->label ?: '—' }}</td>
                                    <td class="px-2 py-2 text-gray-600 dark:text-gray-400">{{ optional($token->created_at)->format('Y-m-d H:i') }}</td>
                                    <td class="px-2 py-2 text-gray-600 dark:text-gray-400">{{ optional($token->last_used_at)->format('Y-m-d H:i') ?: 'Never' }}</td>
                                    <td class="px-2 py-2">
                                        @if ($revoked)
                                            <span class="inline-flex items-center rounded-full bg-danger-100 px-2 py-0.5 text-xs font-medium text-danger-700 dark:bg-danger-900/40 dark:text-danger-400">{{ __("Revoked") }}</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-success-100 px-2 py-0.5 text-xs font-medium text-success-700 dark:bg-success-900/40 dark:text-success-400">{{ __("Active") }}</span>
                                        @endif
                                    </td>
                                    <td class="px-2 py-2 text-right">
                                        @unless ($revoked)
                                            <button
                                                type="button"
                                                wire:click="revokeToken('{{ $token->id }}')"
                                                wire:confirm="Revoke this API key? It can no longer be used."
                                                class="text-xs font-medium text-danger-600 hover:underline dark:text-danger-400"
                                            >{{ __("Revoke") }}</button>
                                        @endunless
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-filament::section>

        {{-- Authorized chats --}}
        <x-filament::section>
            <x-slot:name>{{ __("Authorized Chats") }}</x-slot:name>
            <x-slot:description>{{ __("Chats permitted to interact with the bot.") }}</x-slot:description>

            @if ($chats->isEmpty())<p class="text-sm text-gray-500 dark:text-gray-400">{{ __("No chats yet.") }}</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b text-xs uppercase text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-2 py-2">{{ __("Chat") }}</th>
                                <th class="px-2 py-2">{{ __("Chat ID") }}</th>
                                <th class="px-2 py-2">{{ __("Authorized") }}</th>
                                <th class="px-2 py-2">{{ __("Status") }}</th>
                                <th class="px-2 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-800">
                            @foreach ($chats as $chat)
                                @php
                                    $authorized = method_exists($chat, 'isAuthorized') ? $chat->isAuthorized() : filled($chat->authorized_at ?? null);
                                    $label = $chat->username ? '@' . $chat->username : ($chat->first_name ?: '—');
                                @endphp
                                <tr>
                                    <td class="px-2 py-2 font-medium">{{ $label }}</td>
                                    <td class="px-2 py-2 font-mono text-xs text-gray-600 dark:text-gray-400">{{ $chat->chat_id }}</td>
                                    <td class="px-2 py-2 text-gray-600 dark:text-gray-400">{{ optional($chat->authorized_at)->format('Y-m-d H:i') ?: '—' }}</td>
                                    <td class="px-2 py-2">
                                        @if ($authorized)
                                            <span class="inline-flex items-center rounded-full bg-success-100 px-2 py-0.5 text-xs font-medium text-success-700 dark:bg-success-900/40 dark:text-success-400">{{ __("Authorized") }}</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">{{ __("Deauthorized") }}</span>
                                        @endif
                                    </td>
                                    <td class="px-2 py-2 text-right">
                                        @if ($authorized)
                                            <button
                                                type="button"
                                                wire:click="revokeChat('{{ $chat->id }}')"
                                                wire:confirm="Deauthorize this chat? It will need to re-authenticate."
                                                class="text-xs font-medium text-danger-600 hover:underline dark:text-danger-400"
                                            >{{ __("Deauthorize") }}</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>
