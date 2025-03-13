<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScanController;
use App\Livewire\Dashbords\StarterPage;
use App\Livewire\Events\EventComponent;
use App\Livewire\Scanners\ScannerComponent;
use App\Livewire\Tickets\TicketComponent;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', StarterPage::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/events', EventComponent::class)->name('events');
    Route::get('/tickets', TicketComponent::class)->name('tickets');
    Route::get('/scanners', ScannerComponent::class)->name('scanners');
    // Route::post('/qr-code/scanners', [ScanController::class, 'scanQrCode'])->name('scan.qr.code');

    Route::post('/qr-code/scanners', function (Request $request) {
        $data = json_decode($request->input('content'), true); // Décoder le JSON reçu
        $ticket = Ticket::find($data['id']); // Rechercher un ticket par ID

        if (!$ticket) {
            return response()->json(['error' => 'Ticket introuvable'], 404);
        }

        return response()->json([
            'message' => 'Scan enregistré avec succès',
            'ticketId' => $ticket->id,
            'code' => $ticket->code,
            'eventName' => $ticket->event->name ?? 'Inconnu',
            'created' => $ticket->created_at->format('d/m/Y'),
            'is_used' => $ticket->is_used ? 'Oui' : 'Non',
            'scanned_by' => $ticket->user_scanner->name ?? '-',
        ]);
    });


    Route::post('/update/qr-code', function (Request $request) {
        $ticket = Ticket::find($request->ticket_id);

        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket non trouvé !'], 404);
        }

        $ticket->is_used = 1;
        $ticket->scanner_id = Auth::user()->id;
        $ticket->save();

        return response()->json(
            [
            'success' => true,
            'message' => 'Présence marquée avec succès !',
            'scanned_by' => $ticket->user_scanner->name
        ]);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
