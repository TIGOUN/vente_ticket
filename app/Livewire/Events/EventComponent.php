<?php

namespace App\Livewire\Events;

use App\Models\Events;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class EventComponent extends Component
{
    use WithFileUploads;
    public $showCreateEventForm = false;
    public string $search = '';
    public ?string $date = null;
    public $eventId;
    public $code;
    public $name;
    public $description;
    public $start_date;
    public $end_date;
    public $location;
    public $total_tickets_expired = 0;
    public $total_tickets_scanned = 0;
    public $total_tickets = 0;
    public $sold_tickets = 0;
    public $branding_image;
    public $branding_image_url;

    public $message, $typeMessage;

    protected $rules = [
        // 'code' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_date' => 'required|date|before:end_date',
        'end_date' => 'nullable|date|after:start_date',
        'location' => 'nullable|string|max:255',
        // 'total_tickets_expired' => 'required|integer',
        // 'total_tickets_scanned' => 'required|integer',
        // 'total_tickets' => 'required|integer',
        // 'sold_tickets' => 'required|integer',
        'branding_image' => 'nullable|image', // 1MB max
    ];

    public function mount($eventId = null)
    {
        $this->code = generateUniqueReference();
        // if ($eventId) {
        //     $event = Event::findOrFail($eventId);
        //     $this->eventId = $event->id;
        //     $this->name = $event->name;
        //     $this->description = $event->description;
        //     $this->start_date = $event->start_date->format('Y-m-d\TH:i');
        //     $this->end_date = $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : null;
        //     $this->location = $event->location;
        //     $this->total_tickets_expired = $event->total_tickets_expired;
        //     $this->total_tickets_scanned = $event->total_tickets_scanned;
        //     $this->total_tickets = $event->total_tickets;
        //     $this->sold_tickets = $event->sold_tickets;
        //     $this->branding_image_url = $event->branding_image;
        // }
    }


    public function createEvent()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            $eventData = [
                'code' => $this->code,
                'name' => $this->name,
                'description' => $this->description,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'location' => $this->location,
                'user_id' => Auth::user()->id
            ];

            if ($this->branding_image) {
                $brandingImagePath = $this->branding_image->store('events/branding_images', 'public');
                $eventData['branding_image'] = $brandingImagePath;
            }

            Events::create($eventData);
            DB::commit();

            $this->showingCreateEventComponent();
            $this->dispatch('show-message', [
                'message' => 'Evernement créer avec succès!!!',
                'typeMessage' => 'success',
            ]);
            $this->dispatch('refresh-events-dataTable');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            $this->dispatch('show-message', [
                'message' => 'Opérations échouée !!!',
                'typeMessage' => 'error',
            ]);
        }
    }

    public function showingCreateEventComponent()
    {
        $this->showCreateEventForm = !$this->showCreateEventForm;
        $this->code = generateUniqueReference();
        $this->reset(
            'name',
            'description',
            'start_date',
            'end_date',
            'location',
        );
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDate()
    {
        $this->resetPage();
    }

    #[On('refresh-events-dataTable')]
    public function render()
    {
        // return view('livewire.events.event-component', ['events' => Events::latest()->get()]);
        $query = Events::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->OrWhere('code', 'like', '%' . $this->search . '%')
                ->OrWhere('location', 'like', '%' . $this->search . '%')
                ->OrWhere('description', 'like', '%' . $this->search . '%')
            ;
        }

        if ($this->date) {
            $query->whereDate('start_date', $this->date)
                ->OrWhereDate('end_date', $this->date)
            ;
        }

        return view('livewire.events.event-component', [
            'events' => $query->latest()->paginate(20)
        ]);
    }
}
