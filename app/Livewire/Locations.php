<?php

namespace App\Livewire;

use Livewire\Component;

class Locations extends Component
{
    public $location = [];
    public $provinces = [];
    public $districts = [];
    public $sectors = [];
    public $cells = [];
    public $villages = [];

    public $selectedProvince;
    public $selectedDistrict;
    public $selectedSector;
    public $selectedCell;
    public $selectedVillage;

    public function mount()
    {
        $json = file_get_contents(public_path('locations.json'));
        $this->location = json_decode($json, true);
        $this->provinces = array_keys($this->location);
    }

    public function updatedSelectedProvince($value)
    {
        $this->dispatch('provinceUpdated', $value); 
        $this->loadDistricts();
        $this->reset(['selectedDistrict', 'selectedSector', 'selectedCell', 'selectedVillage']);
    }

    public function loadDistricts()
    {
        $this->districts = array_keys($this->location[$this->selectedProvince] ?? []);
        $this->reset(['selectedSector', 'selectedCell', 'selectedVillage']);
    }

    public function updatedSelectedDistrict($value)
    {
        $this->dispatch('districtUpdated', $value); // Using $this->dispatch() in v3
        $this->loadSectors();
        $this->reset(['selectedSector', 'selectedCell', 'selectedVillage']);
    }

    public function loadSectors()
    {
        if ($this->selectedProvince && $this->selectedDistrict) {
            $this->sectors = array_keys($this->location[$this->selectedProvince][$this->selectedDistrict] ?? []);
        }
        $this->reset(['selectedCell', 'selectedVillage']);
    }

    public function updatedSelectedSector($value)
    {
        $this->dispatch('sectorUpdated', $value); // Using $this->dispatch() in v3
        $this->loadCells();
        $this->reset(['selectedCell', 'selectedVillage']);
    }

    public function loadCells()
    {
        if ($this->selectedProvince && $this->selectedDistrict && $this->selectedSector) {
            $this->cells = array_keys($this->location[$this->selectedProvince][$this->selectedDistrict][$this->selectedSector] ?? []);
        }
        $this->reset(['selectedVillage']);
    }

    public function updatedSelectedCell($value)
    {
        $this->dispatch('cellUpdated', $value); // Using $this->dispatch() in v3
        $this->loadVillages();
    }

    public function loadVillages()
    {
        if ($this->selectedProvince && $this->selectedDistrict && $this->selectedSector && $this->selectedCell) {
            $this->villages = $this->location[$this->selectedProvince][$this->selectedDistrict][$this->selectedSector][$this->selectedCell] ?? [];
        }
    }

    public function updatedSelectedVillage($value)
    {
        $this->dispatch('villageUpdated', $value); // Using $this->dispatch() in v3
    }

    public function render()
    {
        return view('livewire.locations', [
            'provinces' => $this->provinces,
            'districts' => $this->districts,
            'sectors' => $this->sectors,
            'cells' => $this->cells,
            'villages' => $this->villages,
        ]);
    }
}