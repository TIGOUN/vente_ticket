<div>
    <form wire:submit.prevent="send" class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Ticket : {{ $codeTicket }}</h5>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" wire:model="name" class="form-control">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" wire:model="email" class="form-control">
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success">Envoyer</button>
            <button type="button" class="btn btn-secondary" wire:click="$set('show', false)">Fermer</button>
        </div>
    </form>
</div>
