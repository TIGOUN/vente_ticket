<?php

namespace App\Livewire\Tickets;

use App\Models\Events;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class TicketComponent extends Component
{
    use WithPagination;
    public $showCreateTicketForm, $numberTicket, $eventId, $events;
    public $checked = [], $checkedPage = false, $checkedAll = false, $perPage = 5, $search;

    public function showingCreateTicketComponent()
    {
        $this->showCreateTicketForm = !$this->showCreateTicketForm;
    }

    public function mount($eventId)
    {
        $this->eventId = $eventId;
    }

    public function checkedAllItem()
    {
        $this->checkedAll = true;
        $this->checked = $this->getTickets()->pluck('id')->map(fn($item) => (string)$item)->toArray();
    }

    private function getTickets()
    {
        return Ticket::where('event_id', $this->eventId)
            ->where('is_selled', 0)
            ->get();
    }

    public function updatedCheckedPage($value)
    {
        if ($value) {
            $this->checked = $this->getTickets()->pluck('id')->map(fn($item) => (string)$item)->toArray();
        } else {
            $this->checked = [];
        }
    }

    public function updatedChecked()
    {
        $this->checkedPage = false;
    }

    public function isChecked($ticketId)
    {
        return in_array($ticketId, $this->checked);
    }

    // Définir les règles de validation
    public function rules()
    {
        return [
            'numberTicket' => 'required|integer|min:1', // Le nombre de tickets est requis, doit être un entier et supérieur ou égal à 1
        ];
    }

    public function createTicket()
    {
        // TODO: Implement ticket creation logic here
        // 1- Validation du formulaire
        $this->validate();

        // 2- Sauvegarde des données du ticket dans la base de données
        for ($i = 0; $i < $this->numberTicket; $i++) // Création du nombre de tickets demandé
        {
            $ticket = new Ticket();
            $ticket->id = Str::uuid(); // Génération d'un UUID sécurisé
            $ticket->event_id = $this->eventId;
            $ticket->user_id = Auth::user()->id;
            $ticket->code = generateCodeTicket();

            // Données sensibles du ticket (ex: nom du participant, email, etc.)
            $data = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'timeAt' => now()
            ];

            // Chiffrement des données
            $ticket->encryptData($data);
            $ticket->save();
        }

        // 3- Affichage d'un message de confirmation
        return redirect()->route('tickets', $this->eventId);
    }

    public function render()
    {
        $tickets = Ticket::where('event_id', $this->eventId)->orderByDesc('code')->paginate(5);
        return view('livewire.tickets.ticket-component', ['tickets' => $tickets]);
    }
}
