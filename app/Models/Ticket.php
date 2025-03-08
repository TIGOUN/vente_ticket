<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false; // Utilisation d'UUID
    protected $keyType = 'string';

    protected $fillable = ['id', 'event_id', 'encrypted_data', 'is_used', 'email'];

    // Générer une signature cryptographique pour le ticket
    public function generateSignature(): string
    {
        return hash_hmac('sha256', $this->id, config('app.key'));
    }

    // Sauvegarde automatique de la signature lors de la création du ticket
    protected static function booted()
    {
        static::creating(function ($ticket) {
            $ticket->signature = $ticket->generateSignature();
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