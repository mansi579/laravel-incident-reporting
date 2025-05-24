<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index3.html" class="brand-link">
        <img src="" alt="" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">SharkStriker</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-2 pb-2">
            <div class="info">
                <a href="#" class="d-block">
                    <h4 style="font-size:25px; margin-left:40px;">{{ Auth::user()->name }}</h4>
                </a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @role(Config::get('constants.roles.user'))
                    <li class="nav-item">
                        <a href="{{ route(name: 'incidents.index') }}" class="nav-link {{ Route::is('incidents.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Create Incident Request</p>
                        </a>
                    </li>
                @endrole

                @role(Config::get('constants.roles.admin'))
                    <li class="nav-item">
                        <a href="{{ route(name: 'adminIncidents.index') }}" class="nav-link {{ Route::is('adminIncidents.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>View Incidents</p>
                        </a>
                    </li>
                @endrole

                @role(Config::get('constants.roles.super-admin'))
                    <li class="nav-item">
                        <a href="{{ route(name: 'categories.index') }}" class="nav-link {{ Route::is('categories.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Category</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route(name: 'user.index') }}" class="nav-link {{ Route::is('user.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>User Management</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route(name: 'requests.index') }}" class="nav-link {{ Route::is(patterns: 'requests.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Incident Requests</p>
                        </a>
                    </li>
                @endrole

                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link"
                        onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

</aside>