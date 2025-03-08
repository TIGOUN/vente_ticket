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
                @if ($showCreateEventForm)
                <a href="#" class="btn btn-danger mb-2" wire:click="showingCreateEventComponent">
                    <i class="mdi mdi-close-circle me-2"></i>
                    Fermer
                </a>
                @else
                <a href="#" class="btn btn-success mb-2" wire:click="showingCreateEventComponent">
                    <i class="mdi mdi-plus-circle me-2"></i>
                    Ajouter
                </a>
                @endif
            </div>
        </div>
    </div>

    @if ($showCreateEventComponent)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="createEvent">
                        <div class="mb-3">
                            <label for="code" class="form-label">Code de l'événement</label>
                            <input type="text" id="code" wire:model="code"
                                class="form-control @error('code') is-invalid @enderror" required readonly>
                            @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de l'événement</label>
                            <input type="text" id="name" wire:model="name"
                                class="form-control @error('name') is-invalid @enderror" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" wire:model="description"
                                class="form-control @error('description') is-invalid @enderror"></textarea>
                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">Date de début</label>
                            <input type="datetime-local" id="start_date" wire:model="start_date"
                                class="form-control @error('start_date') is-invalid @enderror" required>
                            @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">Date de fin</label>
                            <input type="datetime-local" id="end_date" wire:model="end_date"
                                class="form-control @error('end_date') is-invalid @enderror">
                            @error('end_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Lieu de l'événement</label>
                            <input type="text" id="location" wire:model="location"
                                class="form-control @error('location') is-invalid @enderror">
                            @error('location') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="branding_image" class="form-label">Image de Branding</label>
                            <input type="file" id="branding_image" wire:model="branding_image"
                                class="form-control @error('branding_image') is-invalid @enderror">
                            @error('branding_image') <span class="text-danger">{{ $message }}</span> @enderror
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
                                    <th>Nom</th>
                                    <th>Date début</th>
                                    <th>Date fin</th>
                                    <th>Lieu</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
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
                                        -
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