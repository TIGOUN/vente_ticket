<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $keyType = 'string'; // UUID en tant que clé primaire
    public $incrementing = false; // Désactiver l'auto-incrémentation

    protected $fillable = [
        'id',
        'name',
        'code',
        'description',
        'start_date',
        'end_date',
        'location',
        'total_tickets_expired',
        'total_tickets_scanned',
        'total_tickets',
        'sold_tickets',
        'branding_image',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($event) {
            $event->id = Str::uuid(); // Génération automatique d'UUID
        });
    }

    // public function tickets()
    // {
    //     return $this->hasMany(Ticket::class, 'event_id');
    // }
}
