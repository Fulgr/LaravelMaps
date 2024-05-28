<?php

namespace App\Livewire;

use App\Models\Region;
use Livewire\Component;

class Map extends Component
{
    public $loc;

    public $zoom;

    public $regions;

    public $editRegionModal;

    public $regionName;

    public $region;

    public function updateRegion($id)
    {
        $region = $this->region;
        ray($region);
        $region->name = $this->regionName;
        $region->save();
        $this->showRegion($id);
        $this->reload();
    }

    public function deleteRegion($id)
    {
        $region = Region::find($id);
        $region->delete();
        $this->reload();
    }

    public function showRegion($id)
    {
        if ($this->editRegionModal) {
            $this->editRegionModal = false;
            $this->region = null;
            $this->regionName = null;

            return;
        }
        $this->region = Region::with('locations')->find($id);
        $this->regionName = $this->region->name;
        $this->editRegionModal = true;
    }

    public function updateSession($latlng, $zoom)
    {
        session()->put('loc', $latlng);
        session()->put('zoom', $zoom);
    }

    public function createRegion($data, $latlng, $zoom)
    {
        $this->updateSession($latlng, $zoom);
        $region = Region::create();
        foreach ($data['locations'] as $location) {
            $region->locations()->create([
                'lat' => $location[0],
                'lng' => $location[1],
            ]);
        }
        $this->reload();
    }

    public function onMapClick($latlng, $zoom)
    {
        $this->updateSession($latlng, $zoom);
        $this->reload();
    }

    public function reload()
    {
        return redirect('/');
    }

    public function mount()
    {
        $this->loc = session()->get('loc');
        if (! $this->loc) {
            $this->loc = ['lat' => 0, 'lng' => 0];
        }
        $this->zoom = session()->get('zoom');
        if (! $this->zoom) {
            $this->zoom = 13;
        }
        $this->regions = Region::with('locations')->get();
    }

    public function render()
    {
        return view('livewire.map');
    }
}
