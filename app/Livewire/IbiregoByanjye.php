<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Dispute;

class IbiregoByanjye extends Component
{
    public $disputes;
    public $activeTab = 'kirabitse';
    public $showIkiregoModal = false;
    public $selectedDisputeId;

    public function mount()
    {
        $this->disputes = Dispute::where('citizen_id', auth()->user()->id)->get();
    }

    public function editDispute($id)
    {
        $this->selectedDisputeId = $id;
        $this->showIkiregoModal = true;
    }

    public function closeModal()
    {
        $this->showIkiregoModal = false;
        $this->selectedDisputeId = null;
        $this->disputes = Dispute::where('citizen_id', auth()->user()->id)->get();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function downloadReport($id)
    {
        return response()->download(storage_path("app/reports/dispute-{$id}.pdf"));
    }

    public function render()
    {
        return view('livewire.ibirego-byanjye', [
            'disputes' => $this->disputes,
            'activeTab' => $this->activeTab
        ]);
    }
}
