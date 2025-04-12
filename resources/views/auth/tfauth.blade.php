<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="author" />

    <!-- App favicon -->

    <!-- App css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css"
        integrity="sha512-6S2HWzVFxruDlZxI3sXOZZ4/eJ8AcxkQH1+JjSe/ONCEqR9L4Ysq5JdT5ipqtzU7WHalNwzwBv+iE51gNHJNqQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="loading authentication-bg"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>

    <div class="pt-2 pt-sm-5 pb-4 pb-sm-5 account-pages">
        <div class="container">
            <div class="justify-content-center row">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <!-- Logo -->
                        <div class="pt-4 pb-4 text-center card-header">
                            <a href="#">
                                <span>

                                </span>
                            </a>
                        </div>

                        <div class="p-4 card-body">
                            <div class="m-auto w-75 text-center">
                                <h4 class="mt-0 text-dark-50 text-center fw-bold">Vérifiez votre e-mail pour un code
                                </h4>
                                <p class="mb-4 text-muted">Nous avons envoyé un code à 6 caractères à
                                    <strong>{{ $user->email }}</strong> . Le code expire sous peu, veuillez donc le
                                    saisir rapidement.
                                </p>
                            </div>

                            <form id="codeForm" method="POST" autocomplete="off">
                                @csrf
                                <div class="mb-3">
                                    <input class="form-control" type="number" id="code" name="code"
                                        required="" placeholder="Entrer le code">
                                </div>

                                <div class="mb-0 text-center">
                                    <button class="btn btn-primary" type="submit">Confirmer</button>
                                </div>
                            </form>
                        </div> <!-- end card-body-->
                    </div>
                    <!-- end card -->

                    <div class="mt-3 row">
                        <div class="text-center col-12">
                            <p class="text-muted">Vous n'aviez pas reçu le code ?<a href="{{ route('login.confirm') }}}}"
                                    class="ms-1 text-muted"><b>Renvoyer</b></a></p>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        <a href="mailto:serviceskawa@gmail.com?subject=Le sujet&body=Le corps du message">Cliquez ici pour contacter le
            Support Technique</a>
    </footer>

    <!-- bundle -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

</body>

</html>

<script src="{{ asset('assets/sweetalertjs/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/sweetalertjs/sweetalert2@11.js') }}"></script>
<script>
    toastr.options = {
        "progressBar": true,
        "timeOut": "7200",
    };
    $('#codeForm').on('submit', function(e) {
        e.preventDefault();
        let code = $('#code').val();
        $.ajax({
            url: "{{ route('login.postAuth') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                code: code,
            },
            success: function(data) {
                // console.log(data);
                if (data == 200) {
                    window.location.href = "{{ url('/dashboard') }}";
                } else {
                    toastr.error("Le code saisi est incorecte", 'Code incorrecte');
                }
            },
            error: function(data) {

            },
        });
    });
</script>
</script>
