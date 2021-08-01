<?php

namespace App\Http\Livewire;

use App\Jobs\ImportJob;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class Import extends Component
{
    use WithFileUploads;

    public $batchId;
    public $importFile;
    public $importing = false;
    public $importFilePath;
    public $importFinished = false;

    public function import()
    {
        $this->validate([
            'importFile' => 'required',
        ]);

        $this->importing = true;
        $this->importFilePath = $this->importFile->store('imports');

        $batch = Bus::batch([
            new ImportJob($this->importFilePath),
        ])->dispatch();

        $this->batchId = $batch->id;
    }

    public function getImportBatchProperty()
    {
        if (!$this->batchId) {
            return null;
        }

        return Bus::findBatch($this->batchId);
    }

    public function updateImportProgress()
    {
        $this->importFinished = $this->importBatch->finished();

        if ($this->importFinished) {
            Storage::delete($this->importFilePath);
            $this->importing = false;
        }
    }

    public function render()
    {
        return view('livewire.import');
    }
}
