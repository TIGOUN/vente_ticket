<?php

use App\Models\Ticket;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\ValidationException;
use Illuminate\Support\Facades\Log;

class QRCodeService
{
    // public static function generateQRCode($signature, $ticketId): string
    // {
    //     $data = json_encode([
    //         'id' => $ticketId,
    //         'signature' => $signature,
    //     ]);

    //     // ====================================================================
    //     $writer = new PngWriter();
    //     $qrCode = QrCode::create($data)
    //         ->setEncoding(new Encoding('UTF-8'))
    //         ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
    //         ->setSize(300)
    //         ->setMargin(10)
    //         ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
    //         ->setForegroundColor(new Color(0, 0, 0))
    //         ->setBackgroundColor(new Color(255, 255, 255));
    //     $result = $writer->write($qrCode);

    //     $qrcodes_name = 'qrcodes';

    //     if (!Storage::disk('public')->exists($qrcodes_name)) {
    //         Storage::disk('public')->makeDirectory($qrcodes_name);
    //     }

    //     $qr_codes_directory = storage_path('app/public/' . $qrcodes_name);

    //     $file_path = $qr_codes_directory . '/ticket_' . $ticketId . '.png';

    //     $result->saveToFile($file_path);

    //     return $file_path;
    // }


    // public static function generateSignature($ticketId)
    // {
    //     return hash_hmac('sha256', $ticketId, env('APP_QR_CODE_KEY'));
    // }
}