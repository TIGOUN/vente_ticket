<?php

namespace App\Livewire\Tickets;

use App\Mail\SendTicketPdf;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class SendTicketModal extends Component
{
    public $ticketId;
    public $name;
    public $email;
    public $codeTicket = '';
    public $show = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
    ];

    public function mount($ticketId)
    {
        $this->ticketId = $ticketId;
        $ticket = Ticket::where('id', $this->ticketId)->first();
        $this->codeTicket = $ticket->code;
    }

    public function send()
    {
        try {
            $this->validate();
            DB::beginTransaction();
            $ticket = Ticket::findOrFail($this->ticketId);
            $ticket->email = $this->email;
            $ticket->is_selled = 1;
            $ticket->user_paid_online_name = $this->name;
            $ticket->save();

            $pdf = Pdf::loadView('pdfs.single_ticket', ['ticket' => $ticket])
                ->setPaper('a4', 'portrait');

            $filename = 'ticket_' . $ticket->code . '.pdf';
            $pdfPath = storage_path("app/public/tickets/{$filename}");
            // file_put_contents($pdfPath, $pdf->output());


            $directory = storage_path('app/public/tickets');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // $filename = 'ticket_' . $ticket->code . '.pdf';
            $pdfPath = $directory . '/' . $filename;
            // file_put_contents($pdfPath, $pdf->output());

            Mail::to($this->email)->send(new SendTicketPdf($this->name, $pdfPath, $ticket->event));

            $this->dispatch('show-message', [
                'message' => 'Le ticket a été envoyé avec succès !',
                'typeMessage' => 'success',
            ]);

            DB::commit();
            $this->reset(['name', 'email', 'ticketId']);
            $this->dispatch('updated-tickets-close');
        } catch (Exception $th) {
            DB::rollback();
            // dd($th->getMessage());
            Log::error($th->getMessage());
            $this->dispatch('show-message', [
                'message' => 'L\'Opération a rencontré un problème !!!',
                'typeMessage' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.tickets.send-ticket-modal');
    }
}
