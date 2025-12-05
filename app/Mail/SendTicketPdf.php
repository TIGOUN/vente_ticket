<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendTicketPdf extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $name;
    public $pdfPath;
    public $event;

    public function __construct($name, $pdfPath, $event)
    {
        $this->name = $name;
        $this->pdfPath = $pdfPath;
        $this->event = $event;
    }

    public function build()
    {
        return $this->subject('🎟️ Votre ticket pour l\'événement ' . $this->event->name)
            ->view('emails.tickets.send')
            ->with([
                'name' => $this->name,
                'event' => $this->event,
            ])
            ->attach($this->pdfPath, [
                'as' => 'ticket.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
