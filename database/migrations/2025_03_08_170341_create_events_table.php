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
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('UUID au lieu de l’ID auto-incrémenté');
            $table->string('code')->unique()->comment('Code de l\'événement');
            $table->string('name')->comment('Nom de l\'événement');
            $table->text('description')->nullable()->comment('Description');
            $table->dateTime('start_date')->comment('Date de début');
            $table->dateTime('end_date')->nullable()->comment('Date de fin');
            $table->string('location')->nullable()->comment('Lieu de l’événement');
            $table->integer('total_tickets_expired')->default(0)->comment('Nombre total de tickets expirés');
            $table->integer('total_tickets_scanned')->default(0)->comment('Nombre total de tickets scannés');
            $table->integer('total_tickets')->default(0)->comment('Nombre total de tickets générés');
            $table->integer('sold_tickets')->default(0)->comment('Nombre de tickets vendus');
            $table->string('branding_image')->nullable()->comment('Branding de l\'évernement');
            $table->uuid('user_id'); // L'utilisateur qui a crée l'evernement
            $table->softDeletes()->comment('Colonne de suppression deleted_at');
            $table->timestamps(); // Dates de création et mise à jour

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
