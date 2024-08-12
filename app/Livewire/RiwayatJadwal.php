<?php

namespace App\Livewire;

use App\Models\Jadwal;
use Livewire\Component;

class RiwayatJadwal extends Component
{
    public $jadwals;
    public $date_from;
    public $date_to;

    public function mount()
    {
        $this->firstData();
    }

    public function loadData()
    {
        $jadwals = Jadwal::whereBetween('tanggal', [$this->date_from, $this->date_to])->latest()->get()
                    ->groupBy(function($query){
                        return $query->tanggal;
                    })->keys();

        return $this->jadwals = $jadwals;
    }

    public function firstData()
    {   
        $jadwals = Jadwal::latest()->get()
                ->groupBy(function($query){
                    return $query->tanggal;
                })->keys();
        
        return $this->jadwals = $jadwals;
    }

    public function render()
    {
        return view('livewire.riwayat-jadwal');
    }
}
