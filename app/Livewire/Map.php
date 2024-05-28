<?php

namespace App\Livewire;

use App\Models\Region;
use Livewire\Component;

class Map extends Component
{
    public $loc;

    public $zoom;

    public $regions;

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
