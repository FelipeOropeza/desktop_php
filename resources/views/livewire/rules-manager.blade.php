<div>
    {{-- Header Nativo do NativeBlade --}}
    <x-nativeblade-header bg="#09090b" border-color="#27272a">
        <div style="display:flex;align-items:center;padding:12px 16px;gap:12px">
            <x-nativeblade-icon name="folder-notch-open-fill" size="24" class="text-blue-500" />
            <span style="font-weight:900;font-size:18px;letter-spacing:-0.5px">File Organizer</span>
        </div>
    </x-nativeblade-header>

    <div class="p-6">
        <!-- Debug Section -->
        <div class="mb-4 p-3 bg-blue-900/20 border border-blue-800 rounded text-[10px] font-mono text-blue-400">
            Path: {{ $debug['downloads_path'] }} <br>
            Files found in Downloads: {{ $debug['files_count'] }}
        </div>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">Minhas Regras</h2>
            <button wire:click="runOrganizer" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow-lg shadow-blue-900/20">
                Executar Agora 🚀
            </button>
        </div>

        <!-- Formulário de Nova Regra -->
        <div class="bg-zinc-950 p-6 rounded-xl border border-zinc-800 mb-8 shadow-sm">
            <h3 class="text-lg font-semibold text-zinc-300 mb-4">Nova Regra de Automação</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-zinc-500 text-xs font-bold uppercase mb-1">Nome da Regra</label>
                    <input type="text" wire:model="name" class="w-full bg-zinc-900 border-zinc-800 text-white rounded-md p-2 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Ex: Limpar PDFs">
                </div>
                <div>
                    <label class="block text-zinc-500 text-xs font-bold uppercase mb-1">Extensão (sem ponto)</label>
                    <input type="text" wire:model="extension" class="w-full bg-zinc-900 border-zinc-800 text-white rounded-md p-2 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="pdf, zip, png">
                </div>
                <div>
                    <label class="block text-zinc-500 text-xs font-bold uppercase mb-1">Origem</label>
                    <select wire:model="source_folder" class="w-full bg-zinc-900 border-zinc-800 text-white rounded-md p-2 outline-none">
                        <option value="DOWNLOADS">Downloads</option>
                        <option value="EXPORT">Documentos</option>
                    </select>
                </div>
                <div>
                    <label class="block text-zinc-500 text-xs font-bold uppercase mb-1">Destino Principal</label>
                    <select wire:model="destination_folder" class="w-full bg-zinc-900 border-zinc-800 text-white rounded-md p-2 outline-none">
                        <option value="EXPORT">Documentos</option>
                        <option value="DOWNLOADS">Downloads</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-zinc-500 text-xs font-bold uppercase mb-1">Subpasta no destino (opcional)</label>
                    <input type="text" wire:model="destination_subfolder" class="w-full bg-zinc-900 border-zinc-800 text-white rounded-md p-2 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Ex: Trabalho/2024">
                </div>
            </div>
            <button wire:click="saveRule" class="mt-6 w-full bg-white hover:bg-zinc-200 text-black font-black py-3 rounded-lg transition uppercase tracking-widest text-sm">
                Salvar Regra
            </button>
        </div>

        <!-- Lista de Regras -->
        <h3 class="text-zinc-500 text-xs font-bold uppercase mb-4 tracking-widest">Regras Ativas</h3>
        <div class="space-y-3">
            @forelse($rules as $rule)
                <div class="bg-zinc-950 border border-zinc-900 p-4 rounded-lg flex justify-between items-center hover:border-zinc-800 transition">
                    <div>
                        <h4 class="font-bold text-white">{{ $rule->name }}</h4>
                        <p class="text-xs text-zinc-500">
                            Se for <span class="text-blue-400 font-mono">*.{{ $rule->extension ?: 'qualquer' }}</span> em 
                            <span class="text-zinc-300">{{ $rule->source_folder }}</span> → 
                            <span class="text-zinc-300">{{ $rule->destination_folder }}/{{ $rule->destination_subfolder }}</span>
                        </p>
                    </div>
                    <button wire:click="deleteRule({{ $rule->id }})" class="text-zinc-600 hover:text-red-500 transition">
                        <x-nativeblade-icon name="trash" size="20" />
                    </button>
                </div>
            @empty
                <div class="text-center py-12 border-2 border-dashed border-zinc-900 rounded-xl">
                    <p class="text-zinc-600">Nenhuma regra criada ainda.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
