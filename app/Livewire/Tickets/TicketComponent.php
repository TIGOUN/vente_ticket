<?php

namespace App\Livewire\Tickets;

use App\Models\Events;
use App\Models\Ticket;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public $scannedBy = '';
    public $searchCode = '';
    public $isUsed = '';
    public $isSelled = '';
    public $creationDate = null;

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
            DB::beginTransaction();

            // Mettre à jour en une seule requête (optimisé)
            Ticket::whereIn('id', $this->checked)->update(['is_selled' => 1]);

            DB::commit();
            $this->checked = [];
            $this->dispatch('show-message', [
                'message' => 'Tickets marqués comme vendu avec succès !!!',
                'typeMessage' => 'success',
            ]);

            $this->dispatch('refresh-tickets-dataTable');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $this->dispatch('show-message', [
                'message' => 'Opérations échouée !!!',
                'typeMessage' => 'error',
            ]);
        }
    }


    public function generateCompileTickets()
    {
        try {
            // Vérifier si des tickets sont sélectionnés
            if (!is_array($this->checked) || empty($this->checked)) {
                $this->dispatch('show-message', [
                    'errorMessage' => 'Aucun ticket sélectionné.',
                    'typeMessage' => 'error',
                ]);
                return;
            }

            // Récupérer les tickets sélectionnés
            $tickets = Ticket::whereIn('id', $this->checked)->get();
            $event = Events::findOrFail($this->eventId);

            if ($tickets->isEmpty()) {
                $this->dispatch('show-message', [
                    'errorMessage' => 'Les tickets sélectionnés sont introuvables.',
                    'typeMessage' => 'error',
                ]);
                return;
            }

            // Générer le PDF avec les tickets et leurs codes QR
            $pdf = Pdf::loadView('pdfs.tickets_compile', compact('tickets', 'event'))
                ->setPaper('a4', 'portrait');

            // Télécharger le PDF
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, 'tickets_' . time() . '_' . now() . '.pdf');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            dd($e->getMessage());
            $this->dispatch('show-message', [
                'message' => 'Erreur lors de la génération du PDF.',
                'typeMessage' => 'error',
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
            DB::beginTransaction();

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

            DB::commit();

            $this->showCreateTicketForm = false;
            $this->numberTicket = null;

            $this->dispatch('show-message', [
                'message' => 'Tickets créés avec succès!!!',
                'typeMessage' => 'success',
            ]);

            $this->dispatch('refresh-tickets-dataTable');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $this->dispatch('show-message', [
                'message' => 'Opérations échouée !!!',
                'typeMessage' => 'error',
            ]);
        }
    }

    // #[On('refresh-tickets-dataTable')]
    // public function render()
    // {
    //     $tickets = Ticket::where('event_id', $this->eventId)->orderByDesc('code')->paginate(20);
    //     return view('livewire.tickets.ticket-component', ['tickets' => $tickets]);
    // }

    public function updatingSearchCode()
    {
        $this->resetPage();
    }

    public function updatingIsUsed()
    {
        $this->resetPage();
    }

    public function updatingIsSelled()
    {
        $this->resetPage();
    }

    public function updatingCreationDate()
    {
        $this->resetPage();
    }

    public function updatingScannedBy()
    {
        $this->resetPage();
    }

    #[On('refresh-tickets-dataTable')]
    public function render()
    {
        $query = Ticket::where('event_id', $this->eventId);

        if ($this->searchCode) {
            $query->where('code', 'like', '%' . $this->searchCode . '%');
        }

        if ($this->isUsed !== '') {
            $query->where('is_used', $this->isUsed);
        }

        if ($this->isSelled !== '') {
            $query->where('is_selled', $this->isSelled);
        }

        if ($this->creationDate) {
            $query->whereDate('created_at', $this->creationDate);
        }

        $users = User::where('type_user','controller')->get();
        $tickets = $query->orderByDesc('code')->paginate(20);

        return view('livewire.tickets.ticket-component', compact('tickets','users'));
    }
}