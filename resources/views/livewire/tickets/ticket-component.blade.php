<div>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="m-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Evernements</a></li>
                        <li class="active breadcrumb-item">Liste des tickets</li>
                    </ol>
                </div>
                <h4 class="page-title">Tickets</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="mb-2 row">
        <div class="col-sm-7">
            Ajouter un ticket
        </div>
        <div class="col-sm-5">
            <div class="text-sm-end">
                @if ($showCreateTicketForm)
                <a href="#" class="mb-2 btn btn-danger" wire:click="showingCreateTicketComponent">
                    <i class="me-2 mdi mdi-close-circle"></i>
                    Fermer
                </a>
                @else
                <a href="#" class="mb-2 btn btn-success" wire:click="showingCreateTicketComponent">
                    <i class="me-2 mdi mdi-plus-circle"></i>
                    Ajouter
                </a>
                @endif
            </div>
        </div>
    </div>

    @if ($showCreateTicketForm)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="createTicket">
                        <div class="mb-3">
                            <label for="numberTicket" class="form-label">Nombre de tickets</label>
                            <input type="number" id="numberTicket" wire:model="numberTicket" min="0"
                                class="form-control @error('numberTicket') is-invalid @enderror" required>
                            @if ($errors->has('numberTicket'))
                            <div class="text-danger error-text">
                                {{ $errors->first('numberTicket') }}
                            </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-2 row">
                        <div class="text-start col-sm-6">
                            Liste des tickets <span class="bg-success badge">{{ count($tickets) }}</span>
                        </div>
                        <div class="text-end col-sm-6">
                            <button class="btn-outline-primary btn" wire:click="getRecapTickets">
                                Recapitulatif
                            </button>
                        </div>
                    </div>


                    <div class="mb-3 row">
                        <div class="col-md-2">
                            <input wire:model.live="searchCode" type="text" class="form-control"
                                placeholder="Recherche code ticket...">
                        </div>

                        <div class="col-md-2">
                            <select wire:model.live="isDownload" class="form-select">
                                <option value="">Télécharger ?</option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select wire:model.live="isUsed" class="form-select">
                                <option value="">Scanné ?</option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select wire:model.live="isSelled" class="form-select">
                                <option value="">Vendu ?</option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <input type="date" wire:model.live="creationDate" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <select wire:model.live="scannedBy" class="form-select">
                                <option value="">Scanné par</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="table-responsive">
                        @if (count($this->checked) > 0)
                        <button type="button" wire:click="makeTicketHasPayed"
                            class="me-1 mt-2 mb-2 btn btn-info btn-sm fs-6"><i class="mdi mdi-cash"></i>
                            Marquer vendu
                            @if ($this->checked > 1)
                            <strong>{{ count($this->checked) }} tickets</strong>
                            @else
                            <strong>{{ count($this->checked) }} ticket</strong>
                            @endif
                        </button>

                        <button type="button" wire:click="generateCompileTickets"
                            class="me-1 mt-2 mb-2 btn btn-info btn-sm fs-6"><i class="mdi mdi-cash"></i>
                            Exporter en Pdf
                            @if ($this->checked > 1)
                            <strong>{{ count($this->checked) }} tickets</strong>
                            @else
                            <strong>{{ count($this->checked) }} ticket</strong>
                            @endif
                        </button>
                        @else
                        <div>
                            <strong>Sélectionner les tickets</strong> <br>
                            <span style="font-size: 13px;">Vous pouvez sélectionner plusieurs
                                tickets.
                            </span>
                        </div>
                        @endif

                        <table class="table table-centered w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input type="checkbox" id="selectEmploye" wire:model.live="checkedPage"
                                                class="form-check-input" id="customCheck1">
                                            <label class="form-check-label" for="customCheck1">&nbsp;</label>
                                        </div>
                                    </th>

                                    <th>Code</th>
                                    <th>Ticket</th>
                                    <th>Présence</th>
                                    <th>Statut</th>
                                    <th>Télécharger</th>
                                    <th>Payer</th>
                                    <th>Scanner par</th>
                                    <th>Générer par</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $ticket)
                                <tr wire:key="{{ $ticket->id }}" @if($this->isChecked($ticket->id))
                                    class="table-primary" @endif>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" wire:model.live="checked" id="customCheck2"
                                                class="form-check-input" value="{{ $ticket->id }}"
                                                id="{{ $ticket->id }}" @if($ticket->is_selled) disabled @endif
                                            >
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>

                                    <td>
                                        {{ $ticket->code }}
                                    </td>

                                    <td>
                                        @if ($ticket->qr_code)
                                        <img src="{{ Storage::url($ticket->qr_code) }}" alt="QR Code du ticket"
                                            style="width: 150px;">
                                        <br>
                                        <a href="{{ Storage::url($ticket->qr_code) }}"
                                            download="ticket_{{ $ticket->id }}.png">
                                            Télécharger le QR Code
                                        </a>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($ticket->is_used)
                                        <span class="badge-outline-success badge">
                                            Présent
                                        </span>
                                        @else
                                        <span class="badge-outline-danger badge">
                                            Absent
                                        </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($ticket->is_selled)
                                        <span class="badge-outline-success badge">
                                            Vendu
                                        </span>
                                        @else
                                        <span class="badge-outline-danger badge">
                                            Non vendu
                                        </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($ticket->is_download)
                                        <span class="badge-outline-success badge">
                                            Oui
                                        </span>
                                        @else
                                        <span class="badge-outline-danger badge">
                                            Non
                                        </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($ticket->email && $ticket->user_paid_online_name)
                                        {{ $ticket->email }}
                                        {{ $ticket->user_paid_online_name }}
                                        @else
                                        <button class="btn btn-sm btn-success"
                                            wire:click="loadUpdateTicket('{{ $ticket->id }}')">
                                            Envoyer
                                        </button>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $ticket?->user_scanner?->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $ticket->user->name }}
                                    </td>

                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                        {{ $tickets->links('vendor.livewire.bootstrap') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="fade modal" id="scrollable-modal-update-tickets" tabindex="-1" role="dialog"
        aria-labelledby="scrollableModalTitle1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollableModalTitle1">
                        Envoyer le ticket electroniquement à :
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    @if ($selectedTicketId)
                    @livewire(App\Livewire\Tickets\SendTicketModal::class, ['ticketId' => $selectedTicketId],
                    key('show-' . $selectedTicketId))
                    @else
                    <p>Pas de données chargées</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('updated-tickets', () => {
        $('#scrollable-modal-update-tickets').modal('show');
    });

    $wire.on('updated-tickets-close', () => {
        $('#scrollable-modal-update-tickets').modal('hide');
    });

    $wire.on('show-message', (data) => {
        const message = data[0].message; // Récupère le message passé depuis le dispatch
        const type = data[0].typeMessage; // Récupère le message passé depuis le dispatch

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: type,
            title: message
        });
    });
</script>
@endscript