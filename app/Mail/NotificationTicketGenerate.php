<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationTicketGenerate extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('🎟️ Nouveau ticket générer : ' . $this->data['event_name'])
            ->view('emails.tickets.ticket_generate')
            ->with([
                'event_name' => $this->data['event_name'],
                'first_code' => $this->data['first_code'],
                'last_code' => $this->data['last_code']
            ]);
    }
}
