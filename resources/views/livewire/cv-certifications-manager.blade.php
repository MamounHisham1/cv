@php
    $glassCardClasses = 'card-hover overflow-hidden rounded-3xl border border-white/10 bg-zinc-950/80 p-5 md:p-6 text-zinc-100 shadow-2xl shadow-black/20 backdrop-blur-xl';
    $fieldClasses = 'border-white/10 bg-zinc-900/50 text-zinc-100 placeholder:text-zinc-500 shadow-inner shadow-black/10 backdrop-blur-sm focus-visible:border-emerald-500/50 focus-visible:ring-emerald-500/20 focus-visible:ring-offset-0 focus-visible:ring-offset-zinc-950';
    $errorFieldClasses = 'border-red-400/70 focus-visible:border-red-400/70 focus-visible:ring-red-500/25';
    $primaryButtonClasses = 'border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 hover:shadow-xl hover:shadow-emerald-500/30';
    $ghostButtonClasses = 'border border-white/10 bg-white/5 text-zinc-200 backdrop-blur-sm transition-all duration-300 hover:bg-white/10 hover:text-white';
@endphp

<div>
    <x-ui::card class="{{ $glassCardClasses }}">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <x-ui::heading size="lg" class="text-white">Certifications</x-ui::heading>
                <p class="mt-1 text-sm text-zinc-400">Highlight the credentials that strengthen your profile.</p>
            </div>
            <x-ui::button
                wire:click="addCertification"
                variant="primary"
                size="sm"
                icon="plus"
                class="{{ $primaryButtonClasses }} w-full sm:w-auto"
            >
                Add Certification
            </x-ui::button>
        </div>

        @if($showForm)
            <form wire:submit="saveCertification" class="mb-8 space-y-4 form-section">
                <x-ui::heading size="md" class="text-emerald-300">
                    {{ $editingId ? 'Edit Certification' : 'Add New Certification' }}
                </x-ui::heading>

                <x-ui::input
                    wire:model="form.name"
                    label="Certification Name"
                    placeholder="e.g., Project Management Professional (PMP)"
                    required
                    :error="$errors->first('form.name')"
                    class="{{ $fieldClasses }} {{ $errors->has('form.name') ? $errorFieldClasses : '' }}"
                />

                <x-ui::input
                    wire:model="form.issuing_organization"
                    label="Issuing Organization"
                    placeholder="e.g., Project Management Institute"
                    required
                    :error="$errors->first('form.issuing_organization')"
                    class="{{ $fieldClasses }} {{ $errors->has('form.issuing_organization') ? $errorFieldClasses : '' }}"
                />

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-field">
                        <x-ui::input
                            wire:model="form.issue_date"
                            type="date"
                            label="Issue Date"
                            :error="$errors->first('form.issue_date')"
                            class="{{ $fieldClasses }} {{ $errors->has('form.issue_date') ? $errorFieldClasses : '' }}"
                        />
                    </div>
                    <div class="form-field">
                        <x-ui::input
                            wire:model="form.expiration_date"
                            type="date"
                            label="Expiration Date (if applicable)"
                            :error="$errors->first('form.expiration_date')"
                            class="{{ $fieldClasses }} {{ $errors->has('form.expiration_date') ? $errorFieldClasses : '' }}"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="form-field">
                        <x-ui::input
                            wire:model="form.credential_id"
                            label="Credential ID"
                            placeholder="Optional"
                            :error="$errors->first('form.credential_id')"
                            class="{{ $fieldClasses }} {{ $errors->has('form.credential_id') ? $errorFieldClasses : '' }}"
                        />
                    </div>
                    <div class="form-field">
                        <x-ui::input
                            wire:model="form.credential_url"
                            label="Credential URL"
                            placeholder="https://..."
                            :error="$errors->first('form.credential_url')"
                            class="{{ $fieldClasses }} {{ $errors->has('form.credential_url') ? $errorFieldClasses : '' }}"
                        />
                    </div>
                </div>

                <div class="flex flex-col justify-end gap-2 border-t border-white/10 pt-4 sm:flex-row">
                    <x-ui::button
                        type="button"
                        variant="ghost"
                        wire:click="cancelEdit"
                        class="{{ $ghostButtonClasses }}"
                    >
                        Cancel
                    </x-ui::button>
                    <x-ui::button type="submit" variant="primary" class="{{ $primaryButtonClasses }}">
                        {{ $editingId ? 'Update' : 'Add' }} Certification
                    </x-ui::button>
                </div>
            </form>
        @endif

        <div class="space-y-3">
            @forelse($certifications as $cert)
                <div class="flex flex-col gap-4 rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-2xl border border-emerald-400/20 bg-emerald-500/10">
                            <x-ui::icon name="trophy" class="w-5 h-5 text-emerald-300" />
                        </div>
                        <div>
                            <h4 class="font-medium text-white">{{ $cert['name'] }}</h4>
                            <p class="text-sm text-zinc-300">{{ $cert['issuing_organization'] }}</p>
                            <p class="text-xs text-zinc-400">
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
                            class="{{ $ghostButtonClasses }}"
                        />
                        <x-ui::button
                            wire:click="deleteCertification({{ $cert['id'] }})"
                            wire:confirm="Are you sure you want to delete this certification?"
                            variant="ghost"
                            size="sm"
                            icon="trash-2"
                            class="border border-red-400/20 bg-red-500/10 text-red-300 transition-all duration-300 hover:bg-red-500/15 hover:text-red-200"
                        />
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-white/10 bg-white/5 py-10 text-center text-zinc-400 backdrop-blur-xl">
                    <x-ui::icon name="trophy" class="mx-auto mb-2 w-12 h-12 text-emerald-300/70" />
                    <p class="text-white">No certifications added yet.</p>
                    <p class="text-sm text-zinc-400">Add your professional certifications.</p>
                </div>
            @endforelse
        </div>
    </x-ui::card>
</div>
