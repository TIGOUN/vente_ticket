<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
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
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Log;

class Ticket extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false; // Utilisation d'UUID
    protected $keyType = 'string';

    protected $fillable = ['id', 'event_id', 'encrypted_data', 'is_used', 'email', 'signature', 'qr_code', 'user_id', 'scanner_id'];

    // Générer une signature cryptographique pour le ticket
    public function generateSignature(): string
    {
        return hash_hmac('sha256', $this->id, env('APP_QR_CODE_KEY'));
    }

    // Sauvegarde automatique de la signature lors de la création du ticket
    public function generateQRCode()
    {
        $data = json_encode([
            'id' => $this->id,
            'signature' => $this->signature,
        ]);

        $writer = new PngWriter();
        $qrCode = QrCode::create($data)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        $result = $writer->write($qrCode);

        $result = $writer->write($qrCode);

        $fileName = 'qrcodes/ticket_' . $this->id . '.png';

        // Stocker l'image dans le disque public de Laravel
        Storage::disk('public')->put($fileName, $result->getString());

        return $fileName;
    }

    protected static function booted()
    {
        static::creating(function ($ticket) {
            $ticket->signature = $ticket->generateSignature();
        });

        static::created(function ($ticket) {
            $ticket->qr_code = $ticket->generateQRCode();
            $ticket->save();
        });
    }
    /**
     * Relation : Un ticket appartient à un événement.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Enregistre les informations d'un ticket de manière sécurisée.
     *
     * @param array $data Informations du ticket (nom, email, etc.)
     */
    public function encryptData(array $data)
    {
        $this->encrypted_data = Crypt::encryptString(json_encode($data));
    }

    /**
     * Récupère les informations d'un ticket de manière sécurisée.
     *
     * @return array Données décryptées
     */
    public function decryptData()
    {
        return json_decode(Crypt::decryptString($this->encrypted_data), true);
    }
}
