<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ScanController extends Controller
{

    public function scanQrCode(Request $request)
    {
        // Récupération des données
        $scannedData = $request->input('scanned_content');
        $data = json_decode($scannedData, true);
        $ticket = Ticket::findOrFail($data['id']);
        return response()->json([
            'message' => 'Scan enregistré avec succès',
            // 'code' => $ticket->code,
            // 'eventName' => $ticket?->event?->code,
            // 'created' => $ticket->created_at,
            // 'is_used' => $ticket->is_used,

            'code' => 'TKI2',
            'eventName' => 'AOP',
            'created' => '2025-03-01',
            'is_used' => 'Oui',
        ]);
    }
}
