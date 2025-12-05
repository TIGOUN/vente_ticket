<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTicketPdf extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $name;
    public $pdfPath;
    public $event;

    // public function __construct($name, $pdfPath, $event)
    // {
    //     $this->name = $name;
    //     $this->pdfPath = $pdfPath;
    //     $this->event = $event;
    // }

    // public function build()
    // {
    //     return $this->subject('🎟️ Votre ticket pour l\'événement ' . $this->event->name)
    //         ->view('emails.tickets.send')
    //         ->with([
    //             'name' => $this->name,
    //             'event' => $this->event,
    //         ])
    //         ->attach($this->pdfPath, [
    //             'as' => 'ticket.pdf',
    //             'mime' => 'application/pdf',
    //         ]);
    // }


    public function __construct($name, $pdfPath, $event)
    {
        $this->name = $name;
        $this->event = $event;

        // ✅ VALIDATION du chemin
        $this->pdfPath = $this->validatePdfPath($pdfPath);
    }

    /**
     * Valide que le chemin PDF est correct
     */
    private function validatePdfPath($path)
    {
        // ✅ Vérifier que le chemin n'est pas vide
        if (empty($path)) {
            Log::error('SendTicketPdf: Chemin vide', [
                'event_id' => $this->event->id ?? 'unknown',
                'name' => $this->name,
            ]);
            throw new \Exception("Le chemin du PDF est vide");
        }

        // ✅ Vérifier que le fichier existe
        if (!file_exists($path)) {
            Log::error('SendTicketPdf: Fichier introuvable', [
                'path' => $path,
                'event_id' => $this->event->id ?? 'unknown',
            ]);
            throw new \Exception("Fichier PDF introuvable : {$path}");
        }

        // ✅ Vérifier que ce n'est PAS un dossier
        if (is_dir($path)) {
            Log::error('SendTicketPdf: Chemin est un dossier', [
                'path' => $path,
                'event_id' => $this->event->id ?? 'unknown',
            ]);
            throw new \Exception("Le chemin est un dossier : {$path}");
        }

        // ✅ Vérifier que c'est un fichier
        if (!is_file($path)) {
            Log::error('SendTicketPdf: Pas un fichier', [
                'path' => $path,
                'is_dir' => is_dir($path),
                'exists' => file_exists($path),
            ]);
            throw new \Exception("Le chemin n'est pas un fichier : {$path}");
        }

        // ✅ Vérifier que le fichier est lisible
        if (!is_readable($path)) {
            Log::error('SendTicketPdf: Fichier non lisible', [
                'path' => $path,
                'permissions' => substr(sprintf('%o', fileperms($path)), -4),
            ]);
            throw new \Exception("Fichier non lisible : {$path}");
        }

        Log::info('SendTicketPdf: PDF validé', [
            'path' => $path,
            'size' => filesize($path),
            'mime' => mime_content_type($path),
        ]);

        return $path;
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
                'as' => 'ticket-' . $this->event->id . '.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
