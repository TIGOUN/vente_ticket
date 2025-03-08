<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Dashbords\StarterPage;
use App\Livewire\Events\EventComponent;
use App\Livewire\Tickets\TicketComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', StarterPage::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/events', EventComponent::class)->name('events');
    Route::get('/tickets', TicketComponent::class)->name('tickets');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';