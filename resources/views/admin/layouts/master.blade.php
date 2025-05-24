<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <title>{{ config('app.name', '') }}</title> --}}
    <title>@yield('title')</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css?v=3.2.0') }}">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    @yield('css')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>

                @auth
                    @role(Config::get('constants.roles.user'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button"
                        aria-expanded="false">
                            Notifications
                            <span class="badge bg-danger" id="notifCount">{{ auth()->user()->unreadNotifications->count() }}</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="notifDropdown" id="notifMenu">
                            @forelse(auth()->user()->unreadNotifications as $notification)
                                <li>
                                    <a class="dropdown-item" href="{{ route('incidents.index') }}">
                                        {{ $notification->data['message'] }}
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item">No new notifications</span></li>
                            @endforelse
                        </ul>
                    </li>
                    @endrole
                @endauth
            </ul>
        </nav>

        @include('admin.layouts.sidebar')

        <div class="content-wrapper">
            @yield('content')
        </div>

        <footer class="main-footer">
            <!-- <strong>Copyright &copy; 2014-{{ date('Y') }}. </strong> All rights
            reserved. -->
        </footer>
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/adminlte.min.js?v=3.2.0') }}"></script>
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
   <script>
     document.addEventListener('DOMContentLoaded', function () {
        const dropdown = document.querySelector('.nav-item.dropdown');
        const toggle = dropdown.querySelector('.nav-link.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');

        dropdown.addEventListener('mouseenter', function () {
            toggle.classList.add('show');
            menu.classList.add('show');
            toggle.setAttribute('aria-expanded', 'true');
        });

        dropdown.addEventListener('mouseleave', function () {
            toggle.classList.remove('show');
            menu.classList.remove('show');
            toggle.setAttribute('aria-expanded', 'false');
        });
    });
    </script>


    @yield('js')
</body>
</html>