<div>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="m-0 breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Layouts</a></li>
                        <li class="breadcrumb-item active">Detached Sidenav</li>
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
                            @error('numberTicket') <span class="text-danger">{{ $error->has('numberTicket') }}</span> @enderror
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
                        <div class="col-sm-12">
                            Listes des cités
                        </div>
                    </div>

                    <div class="table-responsive">
                        @if (count($this->checked) > 0)
                        <button type="button" wire:click="makeTicketHasPayed"
                            class="me-1 btn btn-info btn-sm fs-6"><i
                                class="mdi mdi-cash"></i>
                            Marquer vendu
                            @if ($this->checked > 1)
                            <strong>{{ count($this->checked) }} tickets</strong>
                            @else
                            <strong>{{ count($this->checked) }} ticket</strong>
                            @endif
                        </button>

                        <button type="button" wire:click="generateCompileTickets"
                            class="me-1 btn btn-info btn-sm fs-6"><i
                                class="mdi mdi-cash"></i>
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
                                            <input type="checkbox" id="selectEmploye" wire:model.live="checkedPage" class="form-check-input" id="customCheck1">
                                            <label class="form-check-label" for="customCheck1">&nbsp;</label>
                                        </div>
                                    </th>

                                    <th>Code</th>
                                    <th>QRCode</th>
                                    <th>Présence</th>
                                    <th>Statut</th>
                                    <th>Payer électroniquement par</th>
                                    <th>Scanner par</th>
                                    <th>Générer par</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets as $ticket)
                                <tr wire:key="{{ $ticket->id }}" @if($this->isChecked($ticket->id)) class="table-primary" @endif>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox"
                                                wire:model.live="checked"
                                                id="customCheck2"
                                                class="form-check-input"
                                                value="{{ $ticket->id }}"
                                                id="{{ $ticket->id }}"
                                                @if($ticket->is_selled) disabled @endif
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
                                        @if ($ticket->email && $ticket->user_paid_online_name)
                                        {{ $ticket->email }}
                                        {{ $ticket->user_paid_online_name }}
                                        @else
                                        -
                                        @endif

                                    </td>

                                    <td>
                                        {{ $ticket?->user_scanner?->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $ticket->user->name }}
                                    </td>

                                    <td>
                                        -
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
</div>

@script
<script>
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