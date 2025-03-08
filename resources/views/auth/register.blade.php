<x-guest-layout>
    <div class="text-center w-75 m-auto">
        <h4 class="text-dark-50 text-center mt-0 fw-bold">S'inscrire</h4>
        <p class="text-muted mb-4">Avez-vous un compte? Créer ton compte, celà ne prendra que quelques minutes</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="fullname" class="form-label">Nom complet</label>
            <input class="form-control" type="text" name="name" value="{{ old('name') }}" id="fullname"
                placeholder="Enter your name" required>
        </div>

        <div class="mb-3">
            <label for="emailaddress" class="form-label">Email</label>
            <input class="form-control" type="email" name="email" id="emailaddress" value="{{ old('email') }}" required
                placeholder="Enter your email">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <div class="input-group input-group-merge">
                <input type="password" name="password" id="password" class="form-control"
                    placeholder="Enter your password" autocomplete="new-password">
                <div class="input-group-text" data-password="false">
                    <span class="password-eye"></span>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmation du mot de passe</label>
            <div class="input-group input-group-merge">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                    placeholder="Enter your password" autocomplete="new-password">
                <div class="input-group-text" data-password="false">
                    <span class="password-eye"></span>
                </div>
            </div>
        </div>

        <!-- <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="checkbox-signup">
                <label class="form-check-label" for="checkbox-signup">I accept <a href="#" class="text-muted">Terms and
                        Conditions</a></label>
            </div>
        </div> -->

        <div class="mb-3 text-center">
            <button class="btn btn-primary" type="submit"> S'inscrire </button>
        </div>
    </form>
</x-guest-layout>