<?php

namespace App\Livewire\Scanners;

use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class ScannerComponent extends Component
{
    public $scannedData = '';
    public $code = '-', $ticketDate = '-', $eventName = '-'; // Stocke la donnée scannée

    // #[On('send-cam-data')]
    public function processScan($content)
    {
        $data = json_decode($content, true);
        $ticket = Ticket::findOrFail($data['id']);
        $this->code = $ticket->code ?? "-";
        $this->ticketDate = $ticket->created_at ?? "-";
        $this->eventName = $ticket->event->name ?? "-";
        dd($content);
        // $this->js("alert('\ljkjkjkjknk\')");
        $this->dispatch('show-details-info');
    }

    public function render()
    {
        return view('livewire.scanners.scanner-component');
    }
}
