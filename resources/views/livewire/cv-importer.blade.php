<div>
    {{-- Step 1: Choose - Template Picker + Start/Upload --}}
    @if($step === 'choose')
    <div>
        <div class="mb-6">
            <h2 class="mb-2 text-2xl font-bold text-white">Pick a template</h2>
            <p class="text-sm text-zinc-400">Choose a layout, then start from scratch or upload an existing CV</p>
        </div>

        <div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-5">
            @foreach($this->templates as $id => $template)
                <button
                    wire:click="selectTemplate('{{ $id }}')"
                    class="group relative rounded-2xl border p-4 text-left transition-all duration-300
                        {{ $selectedTemplate === $id
                            ? 'border-emerald-400/50 bg-emerald-500/10 shadow-lg shadow-emerald-500/10'
                            : 'border-white/10 bg-white/5 hover:border-emerald-400/30 hover:bg-white/10' }}"
                >
                    <div class="mb-2 flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/5">
                        <x-ui::icon name="{{ $template['icon'] }}" class="h-5 w-5 text-zinc-400" />
                    </div>
                    <div class="text-sm font-semibold {{ $selectedTemplate === $id ? 'text-emerald-100' : 'text-white' }}">
                        {{ $template['name'] }}
                    </div>
                    <div class="mt-1 text-xs text-zinc-500">{{ $template['description'] }}</div>
                    @if($selectedTemplate === $id)
                        <div class="absolute right-3 top-3">
                            <div class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500">
                                <x-ui::icon name="check" class="h-3 w-3 text-white" />
                            </div>
                        </div>
                    @endif
                </button>
            @endforeach
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">
            <button
                wire:click="startFromScratch"
                class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500"
            >
                <x-ui::icon name="pencil" class="h-4 w-4" />
                Start from Scratch
            </button>
            <button
                wire:click="showUpload"
                class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-6 py-3.5 text-sm font-semibold text-zinc-300 transition-all duration-300 hover:bg-white/10 hover:text-white"
            >
                <x-ui::icon name="upload" class="h-4 w-4" />
                Upload Existing CV
            </button>
        </div>
    </div>
    @endif

    {{-- Step 2: Upload --}}
    @if($step === 'upload')
    <div>
        <button wire:click="showChoose" class="mb-6 inline-flex items-center gap-2 text-sm text-zinc-400 transition-colors hover:text-white">
            <x-ui::icon name="arrow-left" class="h-4 w-4" />
            Back to templates
        </button>

        <div class="mb-6">
            <h2 class="mb-2 text-2xl font-bold text-white">Upload your CV</h2>
            <p class="text-sm text-zinc-400">We'll extract your information and fill in the fields automatically</p>
        </div>

        @if($errorMessage)
            <div class="mb-6 rounded-xl border border-red-400/30 bg-red-500/10 p-4 text-sm text-red-300">
                {{ $errorMessage }}
            </div>
        @endif

        <div wire:loading.class="pointer-events-none opacity-50" class="mb-6">
            <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-white/10 bg-white/5 p-10 transition-colors hover:border-emerald-400/30"
                 x-data="{ isDragging: false }"
                 x-on:dragover.prevent="isDragging = true"
                 x-on:dragleave.prevent="isDragging = false"
                 x-on:drop.prevent="isDragging = false; $wire.upload('uploadedFile', $event.dataTransfer.files[0])"
                 :class="{ 'border-emerald-400/50 bg-emerald-500/5': isDragging }">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-white/5">
                    <x-ui::icon name="upload-cloud" class="h-8 w-8 text-zinc-400" />
                </div>
                <p class="mb-1 text-sm font-medium text-white">Drag & drop your CV here</p>
                <p class="mb-4 text-xs text-zinc-500">PDF, DOC, DOCX, or TXT up to 5MB</p>
                <label class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-zinc-300 transition-all duration-200 hover:bg-white/10 hover:text-white">
                    <x-ui::icon name="folder-open" class="h-4 w-4" />
                    Browse Files
                    <input type="file" wire:model="uploadedFile" class="hidden" accept=".pdf,.doc,.docx,.txt" />
                </label>
            </div>
        </div>

        @if($uploadedFile)
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-white/10 bg-zinc-900/50 p-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg border border-white/10 bg-white/5">
                    <x-ui::icon name="file-text" class="h-5 w-5 text-zinc-400" />
                </div>
                <div class="flex-1 min-w-0">
                    <p class="truncate text-sm font-medium text-white">{{ $uploadedFile->getClientOriginalName() }}</p>
                    <p class="text-xs text-zinc-500">{{ number_format($uploadedFile->getSize() / 1024, 0) }} KB</p>
                </div>
                <button wire:click="$set('uploadedFile', null)" class="flex h-8 w-8 items-center justify-center rounded-lg text-zinc-500 transition-colors hover:bg-white/10 hover:text-white">
                    <x-ui::icon name="x" class="h-4 w-4" />
                </button>
            </div>

            <div class="flex gap-3">
                <button
                    wire:click="importCv"
                    wire:loading.attr="disabled"
                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-emerald-400/20 bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition-all duration-300 hover:-translate-y-0.5 hover:from-emerald-400 hover:to-emerald-500 disabled:opacity-50 disabled:pointer-events-none"
                >
                    <span wire:loading.remove wire:target="importCv">
                        <x-ui::icon name="sparkles" class="h-4 w-4" />
                    </span>
                    <span wire:loading wire:target="importCv">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    </span>
                    <span wire:loading.remove wire:target="importCv">Import & Continue</span>
                    <span wire:loading wire:target="importCv">Processing your CV...</span>
                </button>
                <button wire:click="showChoose" class="rounded-xl border border-white/10 bg-white/5 px-6 py-3.5 text-sm font-medium text-zinc-400 transition-all duration-200 hover:bg-white/10 hover:text-white">
                    Cancel
                </button>
            </div>
        @endif
    </div>
    @endif
</div>
