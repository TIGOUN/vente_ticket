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
                <h4 class="page-title">Users</h4>
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
                @if ($showCreateUserForm)
                <a href="#" class="mb-2 btn btn-danger" wire:click="showingCreateUserComponent">
                    <i class="me-2 mdi mdi-close-circle"></i>
                    Fermer
                </a>
                @else
                <a href="#" class="mb-2 btn btn-success" wire:click="showingCreateUserComponent">
                    <i class="me-2 mdi mdi-plus-circle"></i>
                    Ajouter
                </a>
                @endif
            </div>
        </div>
    </div>

    @if ($showCreateUserForm)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="createUser">
                        <div class="mb-3">
                            <label for="username" class="form-label">Nom complet</label>
                            <input type="text" id="username" wire:model="username"
                                class="form-control @error('username') is-invalid @enderror" required>
                            @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" wire:model="email"
                                class="form-control @error('email') is-invalid @enderror" required>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" id="password" wire:model="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Ajouter</button>
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
                            Listes des utilisateurs
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

                                    <th>Nom complet</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Crée par</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr wire:key="{{ $user->id }}">
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>

                                    <td>
                                        {{ $user->name }}
                                    </td>

                                    <td>
                                    {{ $user->email }}
                                    </td>

                                    <td>
                                    {{ $user->type_user }}
                                    </td>

                                    <td>
                                    {{ $user?->creatorUser?->name }}
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                        {{ $users->links('vendor.livewire.bootstrap') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
