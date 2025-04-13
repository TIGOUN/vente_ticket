<div>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="m-0 breadcrumb">
                        <li class="breadcrumb-item">
                            <bo href="javascript: void(0);">Tableau de bord</a>
                        </li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Evernements</a></li>
                        <li class="breadcrumb-item active">Liste des évernements</li>
                    </ol>
                </div>
                <h4 class="page-title">Evernements</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="mb-2 row">
        <div class="col-sm-7">
            Ajouter un évernement
        </div>
        <div class="col-sm-5">
            <div class="text-sm-end">
                @if ($showCreateEventForm)
                <a href="#" class="mb-2 btn btn-danger" wire:click="showingCreateEventComponent">
                    <i class="me-2 mdi mdi-close-circle"></i>
                    Fermer
                </a>
                @else
                <a href="#" class="mb-2 btn btn-success" wire:click="showingCreateEventComponent">
                    <i class="me-2 mdi mdi-plus-circle"></i>
                    Ajouter
                </a>
                @endif
            </div>
        </div>
    </div>

    @if ($showCreateEventForm)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="createEvent">
                        <div class="mb-3">
                            <label for="code" class="form-label">Code de l'événement</label>
                            <input type="text" id="code" wire:model="code"
                                class="form-control @error('code') is-invalid @enderror" required readonly>
                            @if ($errors->has('code'))
                            <div class="text-danger error-text">
                                {{ $errors->first('code') }}
                            </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de l'événement</label>
                            <input type="text" id="name" wire:model="name"
                                class="form-control @error('name') is-invalid @enderror" required>
                            @if ($errors->has('name'))
                            <div class="text-danger error-text">
                                {{ $errors->first('name') }}
                            </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" wire:model="description"
                                class="form-control @error('description') is-invalid @enderror"></textarea>
                            @if ($errors->has('description'))
                            <div class="text-danger error-text">
                                {{ $errors->first('description') }}
                            </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">Date de début</label>
                            <input type="datetime-local" id="start_date" wire:model="start_date"
                                class="form-control @error('start_date') is-invalid @enderror" required>
                            @if ($errors->has('start_date'))
                            <div class="text-danger error-text">
                                {{ $errors->first('start_date') }}
                            </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">Date de fin</label>
                            <input type="datetime-local" id="end_date" wire:model="end_date"
                                class="form-control @error('end_date') is-invalid @enderror">
                            @if ($errors->has('end_date'))
                            <div class="text-danger error-text">
                                {{ $errors->first('end_date') }}
                            </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Lieu de l'événement</label>
                            <input type="text" id="location" wire:model="location"
                                class="form-control @error('location') is-invalid @enderror">
                            @if ($errors->has('location'))
                            <div class="text-danger error-text">
                                {{ $errors->first('location') }}
                            </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="branding_image" class="form-label">Image de Branding</label>
                            <input type="file" id="branding_image" wire:model="branding_image"
                                class="form-control @error('branding_image') is-invalid @enderror">
                            @if ($errors->has('branding_image'))
                            <div class="text-danger error-text">
                                {{ $errors->first('branding_image') }}
                            </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            @if ($branding_image)
                            <img src="{{ $branding_image->temporaryUrl() }}" alt="Image Branding" width="100%"
                                class="img-fluid">
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
                        <div class="col-sm-12">
                            Liste des évernements
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <div class="col-md-4">
                            <input wire:model.live="search" type="text" class="form-control"
                                placeholder="Rechercher par nom d’événement">
                        </div>
                        <div class="col-md-3">
                            <input wire:model.live="date" type="date" class="form-control"
                                placeholder="Date de création">
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
                                    <th>Nom</th>
                                    <th>Date début</th>
                                    <th>Date fin</th>
                                    <th>Lieu</th>
                                    <th>Crée par</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($events) > 0)
                                @foreach ($events as $event)
                                <tr wire:key="{{ $event->id }}">
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck1">
                                            <label class="form-check-label" for="customCheck1">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $event->code }}
                                    </td>
                                    <td>
                                        {{ $event->name }}
                                    </td>
                                    <td>
                                        {{ $event->start_date }}
                                    </td>
                                    <td>
                                        {{ $event->end_date }}
                                    </td>
                                    <td>
                                        {{ $event->location }}
                                    </td>
                                    <td>
                                        {{ $event->user->name }}
                                    </td>
                                    <td>
                                        <a href="{{ route('tickets', $event->id) }}" class="btn btn-sm btn-success">
                                            Ticket
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    Pas de données
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.full-page', [
    'wire_target' => 'send,date,showingCreateEventComponent,createEvent'
    ])
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