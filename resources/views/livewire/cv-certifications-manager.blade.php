<div>
    <x-ui::card>
        <div class="flex items-center justify-between mb-4">
            <x-ui::heading size="lg">Certifications</x-ui::heading>
            <x-ui::button
                wire:click="addCertification"
                variant="primary"
                size="sm"
                icon="plus"
            >
                Add Certification
            </x-ui::button>
        </div>

        @if($showForm)
            <form wire:submit="saveCertification" class="space-y-4 mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <x-ui::input
                    wire:model="form.name"
                    label="Certification Name"
                    placeholder="e.g., Project Management Professional (PMP)"
                    required
                />

                <x-ui::input
                    wire:model="form.issuing_organization"
                    label="Issuing Organization"
                    placeholder="e.g., Project Management Institute"
                    required
                />

                <div class="grid grid-cols-2 gap-4">
                    <x-ui::input
                        wire:model="form.issue_date"
                        type="date"
                        label="Issue Date"
                    />
                    <x-ui::input
                        wire:model="form.expiration_date"
                        type="date"
                        label="Expiration Date (if applicable)"
                    />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <x-ui::input
                        wire:model="form.credential_id"
                        label="Credential ID"
                        placeholder="Optional"
                    />
                    <x-ui::input
                        wire:model="form.credential_url"
                        label="Credential URL"
                        placeholder="https://..."
                    />
                </div>

                <div class="flex justify-end gap-2">
                    <x-ui::button
                        type="button"
                        variant="ghost"
                        wire:click="cancelEdit"
                    >
                        Cancel
                    </x-ui::button>
                    <x-ui::button type="submit" variant="primary">
                        {{ $editingId ? 'Update' : 'Add' }} Certification
                    </x-ui::button>
                </div>
            </form>
        @endif

        <div class="space-y-3">
            @forelse($certifications as $cert)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <x-ui::icon name="trophy" class="w-5 h-5 text-indigo-600" />
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $cert['name'] }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $cert['issuing_organization'] }}</p>
                            <p class="text-xs text-gray-500">
                                Issued: {{ $cert['issue_date'] ? \Carbon\Carbon::parse($cert['issue_date'])->format('M Y') : 'N/A' }}
                                @if($cert['expiration_date'])
                                    &bull; Expires: {{ \Carbon\Carbon::parse($cert['expiration_date'])->format('M Y') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <x-ui::button
                            wire:click="editCertification({{ $cert['id'] }})"
                            variant="ghost"
                            size="sm"
                            icon="pencil"
                        />
                        <x-ui::button
                            wire:click="deleteCertification({{ $cert['id'] }})"
                            wire:confirm="Are you sure you want to delete this certification?"
                            variant="ghost"
                            size="sm"
                            icon="trash-2"
                        />
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <x-ui::icon name="trophy" class="w-12 h-12 mx-auto mb-2 opacity-50" />
                    <p>No certifications added yet.</p>
                    <p class="text-sm">Add your professional certifications.</p>
                </div>
            @endforelse
        </div>
    </x-ui::card>
</div>
