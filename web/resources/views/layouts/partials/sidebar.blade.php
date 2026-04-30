<aside class="main-sidebar sidebar-dark-red elevation-4">
    {{-- Brand Logo --}}
    <a href="#" class="brand-link d-flex align-items-center">
        <img src="{{ asset('images/logos/logo.jpg') }}" alt="Mendoza Cafe logo" class="brand-image"
            style="width:35px; height:35px; object-fit:cover; margin-right:10px;">

        <span class="brand-text font-weight-light" style="line-height:1.2;">
            GO FORWARD <br>
            <small style="font-size: 10px;">PEST CONTROL - </small>
            <small style="font-size: 10px;">{{ session('branch_name') }} BRANCH</small>
        </span>
    </a>

    {{-- Sidebar --}}
    <div class="sidebar">
        {{-- Sidebar user panel --}}
        <div class="user-panel d-flex align-items-center py-2">
            <div class="image me-2">
                <img src="{{ asset(getAvatar(session('usr_id'))) }}" class="img-circle elevation-2" alt="User Image"
                    style="width:40px; height:40px;">
            </div>
            <div class="info p-2">
                <a href="javascript:void(0)" class="d-block mb-0 fw-semibold">
                    {{ session('usr_first_name') }}
                </a>
                <small class="text-muted d-block" style="line-height:1;">
                    {{ session('role_name') }}
                </small>
            </div>
        </div>

        {{-- * Sidebar Menu --}}
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                {{-- Home --}}
                <li class="nav-item">
                    <a href="{{ action('App\Http\Controllers\AdminController@home') }}"
                        class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>HOME</p>
                    </a>
                </li>

                {{-- Messages --}}
                <li class="nav-item">
                    <a href="{{ action('App\Http\Controllers\MessageController@main') }}"
                        class="nav-link  {{ request()->is('messages*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>MESSAGES</p>
                    </a>
                </li>

                {{-- Account --}}
                <li class="nav-item">
                    <a href="{{ action('App\Http\Controllers\UserController@account') }}"
                        class="nav-link  {{ request()->is('account*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>ACCOUNT</p>
                    </a>
                </li>

                {{-- PROFILING --}}
                @if (session('SUPERADMIN') == '1' || session('ADMIN') == '1' || session('PROFILER') == '1')
                    <li class="nav-header">PROFILING</li>

                    <li class="nav-item {{ request()->is('profiling*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('profiling*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                MANAGE
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ action('App\Http\Controllers\ProfilingController@clients_active') }}"
                                    class="nav-link {{ request()->is('profiling/clients*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>CLIENTS</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ action('App\Http\Controllers\ProfilingController@technicians_active') }}"
                                    class="nav-link {{ request()->is('profiling/technicians*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>TECHNICIANS</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ action('App\Http\Controllers\ProfilingController@users_active') }}"
                                    class="nav-link {{ request()->is('profiling/users*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>USERS</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                {{-- MANAGEMENT --}}
                @if (session('SUPERADMIN') == '1' || session('ADMIN') == '1')
                    <li class="nav-header">MANAGEMENT</li>

                    <li class="nav-item {{ request()->is('management*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('management*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                MANAGE
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ action('App\Http\Controllers\ManagementController@addresses_active') }}"
                                    class="nav-link {{ request()->is('management/addresses*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>ADDRESSESS</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ action('App\Http\Controllers\ManagementController@branches_active') }}"
                                    class="nav-link {{ request()->is('management/branches*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>BRANCHES</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item {{ request()->is('management*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->is('management/histories*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>
                                LOGS
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ action('App\Http\Controllers\ManagementController@login_histories') }}"
                                    class="nav-link {{ request()->is('management/histories/login*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>LOGINS</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ action('App\Http\Controllers\ManagementController@branches_active') }}"
                                    class="nav-link {{ request()->is('management/histories/user*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>USERS</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                {{-- ! Signout --}}
                <li class="nav-item">
                    <a href="{{ action('App\Http\Controllers\LoginController@logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out"></i>
                        <p>
                            SIGN OUT
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>