<div>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Layouts</a></li>
                        <li class="breadcrumb-item active">Detached Sidenav</li>
                    </ol>
                </div>
                <h4 class="page-title">Cités</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row mb-2">
        <div class="col-sm-7">
            Ajouter un évernement
        </div>
        <div class="col-sm-5">
            <div class="text-sm-end">
                @if ($showCreateTicketForm)
                <a href="#" class="btn btn-danger mb-2" wire:click="showingCreateTicketComponent">
                    <i class="mdi mdi-close-circle me-2"></i>
                    Fermer
                </a>
                @else
                <a href="#" class="btn btn-success mb-2" wire:click="showingCreateTicketComponent">
                    <i class="mdi mdi-plus-circle me-2"></i>
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
                            <label for="code" class="form-label">Evernement</label>
                            <select wire:model="eventId" class="form-select">
                                <option value="">Sélectionnez un evernement</option>
                                @foreach ($events as $event)
                                <option value="{{ $event->id }}">{{ $event->name }}</option>
                                @endforeach
                            </select>
                            @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="numberTicket" class="form-label">Nombre de tickets</label>
                            <input type="number" id="numberTicket" wire:model="numberTicket" min="0"
                                class="form-control @error('numberTicket') is-invalid @enderror" required>
                            @error('numberTicket') <span class="text-danger">{{ $message }}</span> @enderror
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
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            Listes des cités
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck1">
                                            <label class="form-check-label" for="customCheck1">&nbsp;</label>
                                        </div>
                                    </th>

                                    <th>Code</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $ticket)
                                <tr wire:key="{{ $ticket->id }}">
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>

                                    <td>
                                        @if ($ticket->qr_code)
                                        <img src="{{ Storage::url($ticket->qr_code) }}" alt="QR Code du ticket">
                                        <br>
                                        <a href="{{ Storage::url($ticket->qr_code) }}"
                                            download="ticket_{{ $ticket->id }}.png">
                                            Télécharger le QR Code
                                        </a>
                                        @endif
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>