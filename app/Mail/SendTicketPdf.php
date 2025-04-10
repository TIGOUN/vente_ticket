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

    public function __construct($name, $pdfPath)
    {
        $this->name = $name;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->subject('Votre Ticket')
            ->markdown('emails.tickets.send')
            ->attach($this->pdfPath, [
                'as' => 'ticket.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
