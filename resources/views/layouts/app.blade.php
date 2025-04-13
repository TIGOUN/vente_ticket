<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from coderthemes.com/hyper/saas/layouts-detached.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 29 Jul 2022 10:21:23 GMT -->

<head>
    <meta charset="utf-8" />
    <title>Detached Layout | Hyper - Responsive Bootstrap 5 Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- third party css -->
    <link href="{{ asset('assets/css/vendor/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->

    <!-- App css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('assets/sweetalertjs/sweetalert2.min.css') }}" rel="stylesheet">

    @livewireStyles
</head>

<body class="loading" data-layout-color="light" data-layout="detached" data-rightbar-onstart="true">

    <!-- Topbar Start -->
    <div class="topnav-navbar topnav-navbar-dark navbar-custom">
        <div class="container-fluid">

            <!-- LOGO -->
            <a href="index.html" class="topnav-logo">
                <span class="topnav-logo-lg">
                    <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="16">
                </span>
                <span class="topnav-logo-sm">
                    <img src="{{ asset('assets/images/logo_sm.png') }}" alt="" height="16">
                </span>
            </a>

            <ul class="topbar-menu float-end mb-0 list-unstyled">

                <li class="dropdown notification-list d-xl-none">
                    <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false">
                        <i class="dripicons-search noti-icon"></i>
                    </a>
                    <div class="p-0 dropdown-menu dropdown-menu-animated dropdown-lg">
                        <form class="p-3">
                            <input type="text" class="form-control" placeholder="Search ..."
                                aria-label="Recipient's username">
                        </form>
                    </div>
                </li>


                <li class="notification-list">
                    <a class="nav-link end-bar-toggle" href="javascript: void(0);">
                        <i class="dripicons-gear noti-icon"></i>
                    </a>
                </li>

                <li class="dropdown notification-list">
                    <a class="me-0 nav-link dropdown-toggle nav-user arrow-none" data-bs-toggle="dropdown"
                        id="topbar-userdrop" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="account-user-avatar">
                            <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image"
                                class="rounded-circle">
                        </span>
                        <span>
                            <span class="account-user-name">{{ Auth::user()->name }}</span>
                            <span class="account-position">{{ Auth::user()->name }}</span>
                        </span>
                    </a>
                    <div class="topbar-dropdown-menu dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown"
                        aria-labelledby="topbar-userdrop">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="m-0 text-overflow">Welcome !</h6>
                        </div>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <i class="me-1 mdi mdi-account-circle"></i>
                            <span>My Account</span>
                        </a>

                        <!-- item-->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a wire:navigate href="{{ route('logout') }}" onclick="event.preventDefault();
                                                this.closest('form').submit();" class="dropdown-item notify-item">
                                <i class="me-1 mdi mdi-logout"></i>
                                <span>Deconnexion</span>
                            </a>
                        </form>

                    </div>
                </li>

            </ul>
            <a class="button-menu-mobile disable-btn">
                <div class="lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </a>
        </div>
    </div>
    <!-- end Topbar -->

    <!-- Start Content-->
    <div class="container-fluid">

        <!-- Begin page -->
        <div class="wrapper">

            @include('layouts.navigation')

            <div class="content-page">
                <div class="content">

                    {{ $slot }}

                </div> <!-- End Content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                © Dev - TIGOUN Z. K. CYRILLE
                            </div>
                            <div class="col-md-6">
                                <div class="d-md-block text-md-end footer-links d-none">
                                    <a href="javascript: void(0);">A propos</a>
                                    <a href="javascript: void(0);">Support</a>
                                    <a href="javascript: void(0);">Contactez-nous</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div>
            <!-- content-page -->

        </div> <!-- end wrapper-->
    </div>
    <!-- END Container -->

    <!-- bundle -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    <!-- third party js -->
    <script src="{{ asset('assets/js/vendor/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- third party js ends -->

    <!-- demo app -->
    <script src="{{ asset('assets/js/pages/demo.dashboard.js') }}"></script>
    <script src="{{ asset('assets/sweetalertjs/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/sweetalertjs/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('service-worker.js') }}"></script>

    <!-- <script>
    // Si le service worker est disponible dans le navigateur
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker
                .register('/service-worker.js')
                .then((registration) => {
                    console.log('Service Worker enregistré avec succès:', registration);
                })
                .catch((error) => {
                    console.log('Échec de l\'enregistrement du Service Worker:', error);
                });
        });
    }
    </script> -->
    <!-- end demo js-->
    @livewireScripts
</body>

<!-- Mirrored from coderthemes.com/hyper/saas/layouts-detached.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 29 Jul 2022 10:21:23 GMT -->


</html>