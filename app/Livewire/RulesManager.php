<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use App\Models\Rule;
use App\Services\FileOrganizerService;
use NativeBlade\Facades\NativeBlade;

class RulesManager extends Component
{
    public $name = '';
    public $extension = '';
    public $source_folder = 'DOWNLOADS';
    public $destination_folder = 'EXPORT';
    public $destination_subfolder = '';
    public $keyword = '';

    #[Layout('components.layouts.app')]
    public function render()
    {
        $debug = [
            'downloads_path' => native_path('', \NativeBlade\Storage\StoragePath::DOWNLOADS),
            'files_count' => count(Storage::disk('native')->files(native_path('', \NativeBlade\Storage\StoragePath::DOWNLOADS))),
        ];

        return view('livewire.rules-manager', [
            'rules' => Rule::latest()->get(),
            'debug' => $debug
        ]);
    }

    public function saveRule()
    {
        $this->validate([
            'name' => 'required|min:3',
            'extension' => 'nullable',
        ]);

        Rule::create([
            'name' => $this->name,
            'source_folder' => $this->source_folder,
            'destination_folder' => $this->destination_folder,
            'destination_subfolder' => $this->destination_subfolder,
            'extension' => $this->extension,
            'keyword' => $this->keyword,
        ]);

        $this->reset(['name', 'extension', 'destination_subfolder', 'keyword']);
        
        NativeBlade::notification(fn($n) => $n->title('Sucesso')->body('Regra criada com sucesso!'));
    }

    public function deleteRule($id)
    {
        Rule::find($id)->delete();
    }

    public function runOrganizer()
    {
        $service = new FileOrganizerService();
        $results = $service->organize();

        NativeBlade::alert(fn($d) => $d->title('Organização Concluída')->message(implode("\n", $results)));
    }
}
