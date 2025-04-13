<x-guest-layout>

    <div class="m-auto w-75 text-center">
        <h4 class="pb-0 text-dark-50 text-center fw-bold">Se connecter</h4>
        <p class="mb-4 text-muted">Entrez votre email et mot de passe pour accéder au panneau d'administrateur.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input name="email" class="form-control" type="email" id="email" placeholder="Entrez votre email">
        </div>

        <div class="mb-3">
            <a href="{{ route('password.request') }}" class="float-end text-muted"><small>Mot de passe
                    oublié?</small></a>
            <label for="password" class="form-label">Mot de passe</label>
            <div class="input-group input-group-merge">
                <input type="password" name="password" id="password" class="form-control"
                    placeholder="Entrer votre mot de passe">
                <div class="input-group-text" data-password="false">
                    <span class="password-eye"></span>
                </div>
            </div>
        </div>

        <div class="mb-0 mb-3 text-center">
            <button class="btn btn-primary" type="submit"> Se connecter </button>
        </div>

    </form>
</x-guest-layout>