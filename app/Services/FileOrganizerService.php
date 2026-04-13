<?php

namespace App\Services;

use App\Models\Rule;
use Illuminate\Support\Facades\Storage;
use NativeBlade\Storage\StoragePath;
use Illuminate\Support\Str;

class FileOrganizerService
{
    public function organize()
    {
        $rules = Rule::where('active', true)->get();
        $summary = [];

        foreach ($rules as $rule) {
            $count = $this->applyRule($rule);
            $summary[] = "Regra '{$rule->name}': {$count} arquivos movidos.";
        }

        return $summary;
    }

    protected function applyRule(Rule $rule)
    {
        try {
            $sourcePathEnum = $this->getPathEnum($rule->source_folder);
            $destPathEnum = $this->getPathEnum($rule->destination_folder);

            $sourceBase = native_path('', $sourcePathEnum);
            
            // Listar arquivos na origem
            $files = Storage::disk('native')->files($sourceBase);
            $movedCount = 0;

            if (empty($files)) {
                // Tenta listar com um asterisco ou garantir que o diretório é lido corretamente
                // Dependendo da implementação do driver, pode precisar de ajuste
            }

            foreach ($files as $filePath) {
                $fileName = basename($filePath);
                
                // Filtro por Extensão
                if ($rule->extension && !Str::endsWith(strtolower($fileName), '.' . strtolower($rule->extension))) {
                    continue;
                }

                // Filtro por Palavra-chave
                if ($rule->keyword && !Str::contains(strtolower($fileName), strtolower($rule->keyword))) {
                    continue;
                }

                // Definir destino
                $subfolder = $rule->destination_subfolder ? trim($rule->destination_subfolder, '/\\') : '';
                $destinationPath = native_path($subfolder . ($subfolder ? '/' : '') . $fileName, $destPathEnum);

                // Mover arquivo
                Storage::disk('native')->move($filePath, $destinationPath);
                $movedCount++;
            }

            return $movedCount;
        } catch (\Throwable $e) {
            // Retorna a mensagem de erro para o frontend ver no alert
            return "Erro na regra '{$rule->name}': " . $e->getMessage();
        }
    }

    protected function getPathEnum($folderName)
    {
        return match (strtoupper($folderName)) {
            'DOWNLOADS' => StoragePath::DOWNLOADS,
            'DOCUMENTS', 'EXPORT' => StoragePath::EXPORT,
            'APP' => StoragePath::APP,
            'CACHE' => StoragePath::CACHE,
            default => StoragePath::DOWNLOADS,
        };
    }
}
