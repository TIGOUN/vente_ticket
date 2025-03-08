<?php

namespace App\Livewire\Tickets;

use App\Models\Event;
use App\Models\Ticket;
use Livewire\Component;
use Illuminate\Support\Str;

class TicketComponent extends Component
{
    public $showCreateTicketForm, $numberTicket, $eventId, $events;

    public function showingCreateTicketComponent()
    {
        $this->showCreateTicketForm = !$this->showCreateTicketForm;
    }

    // Récupérer les événements pour le formulaire
    public function mount()
    {
        $this->events = Event::latest()->get();
    }

    // Définir les règles de validation
    public function rules()
    {
        return [
            'eventId' => 'required|exists:events,id', // L'événement est requis et doit exister dans la table 'events'
            'numberTicket' => 'required|integer|min:1', // Le nombre de tickets est requis, doit être un entier et supérieur ou égal à 1
        ];
    }

    public function createTicket()
    {
        // TODO: Implement ticket creation logic here
        // 1- Validation du formulaire
        $this->validate();
        // dd(env('APP_QR_CODE_KEY'));
        // 2- Sauvegarde des données du ticket dans la base de données
        $ticket = new Ticket();
        $ticket->id = Str::uuid(); // Génération d'un UUID sécurisé
        $ticket->event_id = $this->eventId;

        // Données sensibles du ticket (ex: nom du participant, email, etc.)
        $data = [
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@example.com',
            'seat' => 'A12'
        ];

        // Chiffrement des données
        $ticket->encryptData($data);
        $ticket->save();

        // 3- Affichage d'un message de confirmation

    }

    public function render()
    {
        return view('livewire.tickets.ticket-component');
    }
}