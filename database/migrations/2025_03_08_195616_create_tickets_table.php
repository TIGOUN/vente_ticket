<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary(); // ID en UUID pour plus de sécurité
            $table->string('code');
            $table->uuid('event_id'); // L'événement auquel appartient le ticket
            $table->uuid('user_id'); // L'utilisateur qui a crée le ticket
            $table->uuid('scanner_id')->nullable(); // L'utilisateur qui a scanné le ticket
            $table->string('signature'); // Stocke la signature du QR Code
            $table->string('qr_code')->nullable(); // Stocke le chemin du fichier QR Code
            $table->longText('encrypted_data'); // Données cryptées (nom, email, etc.)
            $table->string('email')->nullable();
            $table->boolean('is_used')->default(false); // Indique si le ticket a été scanné
            $table->softDeletes();
            $table->timestamps();

            // Clé étrangère vers la table des événements
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('scanner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
