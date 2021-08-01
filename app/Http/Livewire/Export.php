<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Jobs\ExportJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class Export extends Component
{
    public $batchId;
    public $exporting = false;
    public $exportFinished = false;

    public function export()
    {
        $this->exporting = true;
        $this->exportFinished = false;

        $batch = Bus::batch([
            new ExportJob(),
        ])->dispatch();

        $this->batchId = $batch->id;
    }

    public function getExportBatchProperty()
    {
        if (!$this->batchId) {
            return null;
        }

        return Bus::findBatch($this->batchId);
    }

    public function downloadExport()
    {
        return Storage::download('public/transactions.csv');
    }

    public function updateExportProgress()
    {
        $this->exportFinished = $this->exportBatch->finished();

        if ($this->exportFinished) {
            $this->exporting = false;
        }
    }

    public function render()
    {
        return view('livewire.export');
    }
}
