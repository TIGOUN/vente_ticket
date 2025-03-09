<?php

namespace App\Livewire\Scanners;

use App\Models\Ticket;
use Livewire\Component;

class ScannerComponent extends Component
{
    public string $scannedData = ''; // Stocke la donnée scannée

    public function processScan($data)
    {
        $this->scannedData = $data; // Met à jour la valeur scannée
    }

    public function render()
    {
        return view('livewire.scanners.scanner-component');
    }
}