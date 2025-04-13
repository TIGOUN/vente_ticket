<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationTicketDownloaded extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('🎟️ Nouveau ticket télécharger : ' . $this->data['event_name'])
            ->view('emails.tickets.ticket_download')
            ->with([
                'event_name' => $this->data['event_name'],
                'first_code' => $this->data['first_code'],
                'last_code' => $this->data['last_code']
            ]);
    }
}
