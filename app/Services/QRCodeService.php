<?php

use App\Models\Ticket;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeService
{
    // public static function generateQRCode(Ticket $ticket): string
    // {
    //     $data = json_encode([
    //         'id' => $ticket->id,
    //         'signature' => $ticket->signature,
    //     ]);

    //     return QrCode::size(300)->generate($data);
    // }
}