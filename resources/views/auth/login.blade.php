<x-guest-layout>

    <div class="text-center w-75 m-auto">
        <h4 class="text-dark-50 text-center pb-0 fw-bold">Sign In</h4>
        <p class="text-muted mb-4">Enter your email address and password to access admin panel.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input name="email" class="form-control" type="email" id="email" placeholder="Entrez votre email">
        </div>

        <div class="mb-3">
            <a href="{{ route('password.request') }}" class="text-muted float-end"><small>Mot de passe
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

        <div class="mb-3 mb-3">
            <div class="form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="checkbox-signin" checked>
                <label class="form-check-label" for="checkbox-signin">Souviens-toi de moi</label>
            </div>
        </div>

        <div class="mb-3 mb-0 text-center">
            <button class="btn btn-primary" type="submit"> Se connecter </button>
        </div>

    </form>
</x-guest-layout>