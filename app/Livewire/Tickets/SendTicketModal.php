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

    // public function send()
    // {
    //     try {
    //         $this->validate();
    //         DB::beginTransaction();
    //         $ticket = Ticket::findOrFail($this->ticketId);
    //         $ticket->email = $this->email;
    //         $ticket->is_selled = 1;
    //         $ticket->user_paid_online_name = $this->name;
    //         $ticket->save();

    //         $pdf = Pdf::loadView('pdfs.single_ticket', ['ticket' => $ticket])
    //             ->setPaper('a4', 'portrait');

    //         $filename = 'ticket_' . $ticket->code . '.pdf';
    //         $pdfPath = storage_path("app/public/tickets/{$filename}");
    //         // file_put_contents($pdfPath, $pdf->output());


    //         $directory = storage_path('app/public/tickets');
    //         if (!file_exists($directory)) {
    //             mkdir($directory, 0777, true);
    //         }

    //         // $filename = 'ticket_' . $ticket->code . '.pdf';
    //         $pdfPath = $directory . '/' . $filename;
    //         file_put_contents($pdfPath, $pdf->output());

    //         Mail::to($this->email)->send(new SendTicketPdf($this->name, $pdfPath, $ticket->event));

    //         $this->dispatch('show-message', [
    //             'message' => 'Le ticket a été envoyé avec succès !',
    //             'typeMessage' => 'success',
    //         ]);

    //         DB::commit();
    //         $this->reset(['name', 'email', 'ticketId']);
    //         $this->dispatch('updated-tickets-close');
    //     } catch (Exception $th) {
    //         DB::rollback();
    //         dd($th->getMessage());
    //         Log::error($th->getMessage());
    //         $this->dispatch('show-message', [
    //             'message' => 'L\'Opération a rencontré un problème !!!',
    //             'typeMessage' => 'error',
    //         ]);
    //     }
    // }


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

            // ✅ Générer le PDF
            $pdf = Pdf::loadView('pdfs.single_ticket', ['ticket' => $ticket])
                ->setPaper('a4', 'portrait');

            // ✅ Créer le dossier s'il n'existe pas
            $directory = storage_path('app/public/tickets');
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
                Log::info('Dossier tickets créé', ['path' => $directory]);
            }

            // ✅ Générer le nom de fichier unique
            $filename = 'ticket_' . $ticket->code . '_' . time() . '.pdf';
            $pdfPath = $directory . '/' . $filename;

            // ✅ Sauvegarder le PDF
            file_put_contents($pdfPath, $pdf->output());

            // ✅ VÉRIFICATIONS CRITIQUES
            if (!file_exists($pdfPath)) {
                throw new Exception("Échec de création du PDF : {$pdfPath}");
            }

            if (is_dir($pdfPath)) {
                throw new Exception("Le chemin est un dossier : {$pdfPath}");
            }

            if (!is_file($pdfPath)) {
                throw new Exception("Le chemin n'est pas un fichier : {$pdfPath}");
            }

            if (!is_readable($pdfPath)) {
                throw new Exception("Le fichier n'est pas lisible : {$pdfPath}");
            }

            // ✅ Logger les infos du fichier
            Log::info('PDF généré avec succès', [
                'path' => $pdfPath,
                'size' => filesize($pdfPath),
                'exists' => file_exists($pdfPath),
                'is_file' => is_file($pdfPath),
                'is_readable' => is_readable($pdfPath),
                'ticket_id' => $ticket->id,
            ]);

            // ✅ Envoyer l'email
            Mail::to($this->email)->send(
                new SendTicketPdf($this->name, $pdfPath, $ticket->event)
            );

            // ✅ Optionnel : Supprimer le PDF après envoi
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
                Log::info('PDF supprimé après envoi', ['path' => $pdfPath]);
            }

            $this->dispatch('show-message', [
                'message' => 'Le ticket a été envoyé avec succès !',
                'typeMessage' => 'success',
            ]);

            DB::commit();
            $this->reset(['name', 'email', 'ticketId']);
            $this->dispatch('updated-tickets-close');
        } catch (Exception $th) {
            DB::rollback();

            Log::error('Erreur envoi ticket', [
                'error' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'ticket_id' => $this->ticketId ?? 'unknown',
                'email' => $this->email ?? 'unknown',
                'pdf_path' => $pdfPath ?? 'not set',
            ]);

            $this->dispatch('show-message', [
                'message' => 'Erreur : ' . $th->getMessage(),
                'typeMessage' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.tickets.send-ticket-modal');
    }
}
