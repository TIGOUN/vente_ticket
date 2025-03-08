<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithFileUploads;

class EventComponent extends Component
{
    use WithFileUploads;
    public $showCreateEventComponent = false;

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
        // dd($this);
        $eventData = [
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            // 'total_tickets_expired' => $this->total_tickets_expired,
            // 'total_tickets_scanned' => $this->total_tickets_scanned,
            // 'total_tickets' => $this->total_tickets,
            // 'sold_tickets' => $this->sold_tickets,
        ];

        if ($this->branding_image) {
            $brandingImagePath = $this->branding_image->store('events/branding_images', 'public');
            $eventData['branding_image'] = $brandingImagePath;
        }

        // if ($this->eventId) {
        //     $event = Event::findOrFail($this->eventId);
        //     $event->update($eventData);
        // } else {
        Event::create($eventData);
        // }

        // session()->flash('message', 'L\'événement a été enregistré avec succès!');
        return redirect()->route('events');
    }

    public function showingCreateEventComponent()
    {
        $this->showCreateEventComponent = !$this->showCreateEventComponent;
    }

    public function render()
    {
        return view('livewire.events.event-component', ['events' => Event::latest()->get()]);
    }
}