<?php

namespace App\Livewire\Tickets;

use App\Models\Events;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class TicketComponent extends Component
{
    use WithPagination;
    public $showCreateTicketForm, $numberTicket, $eventId, $events, $message, $typeMessage;
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

    public function makeTicketHasPayed()
    {
        try {
            // Mettre à jour en une seule requête (optimisé)
            Ticket::whereIn('id', $this->checked)->update(['is_selled' => 1]);

            $this->checked = [];
            $this->message = 'Tickets marqués comme vendu avec succès !!!';
            $this->typeMessage = 'success';
            $this->dispatch('show-message', [
                'message' => $this->message,
                'typeMessage' => $this->typeMessage,
            ]);

            $this->dispatch('refresh-tickets-dataTable');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $this->message = 'Opérations échouée !!!';
            $this->typeMessage = 'error';
            $this->dispatch('show-message', [
                'message' => $this->message,
                'typeMessage' => $this->typeMessage,
            ]);
        }
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

        try {
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

            $this->showCreateTicketForm = false;
            $this->numberTicket = null;

            $this->message = 'Tickets créés avec succès!!!';
            $this->typeMessage = 'success';
            $this->dispatch('show-message', [
                'message' => $this->message,
                'typeMessage' => $this->typeMessage,
            ]);

            $this->dispatch('refresh-tickets-dataTable');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $this->message = 'Opérations échouée !!!';
            $this->typeMessage = 'error';
            $this->dispatch('show-message', [
                'message' => $this->message,
                'typeMessage' => $this->typeMessage,
            ]);
        }
    }

    #[On('refresh-tickets-dataTable')]
    public function render()
    {
        $tickets = Ticket::where('event_id', $this->eventId)->orderByDesc('code')->paginate(5);
        return view('livewire.tickets.ticket-component', ['tickets' => $tickets]);
    }
}
