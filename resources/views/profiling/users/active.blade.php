@extends('layouts.themes.main')

@section('content')
    {{-- Content Header --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ action('App\Http\Controllers\AdminController@home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">Profiling</li>
                        <li class="breadcrumb-item">Users</li>
                        <li class="breadcrumb-item active">Active</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <section class="content">
        @include('layouts.partials.onclick')
        @include('layouts.partials.modal_style')
        <div class="container-fluid">
            <div class="card">
                <div class="card-body overflow-auto">
                    <div class="row">
                        <div class="col-md-12">
                            @if (session('SUPERADMIN') == '1' || session('ADMIN') == '1')
                                <a class="btn btn-danger btn-md mb-3" href="{{ url('user/employees/inactive') }}">
                                    <span class="fa fa-archive"></span> Deleted Users
                                </a>
                                <button type="button" class="btn btn-secondary mb-3" data-toggle="modal"
                                    data-target="#newUserModal">
                                    <span class="fa fa-plus"></span> New Employee
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <!-- Table Column -->
                        <div class="col-lg-8 col-md-7">
                            <form method="GET" action="{{ url('profiling/users/active') }}" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="search" id="searchInput" class="form-control"
                                        placeholder="Search users..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="fa fa-search"></span> Search
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <table id="profilingTable" class="table table-hover table-bordered table-sm responsive">
                                <thead>
                                    <tr>
                                        <th style="vertical-align: middle; text-align: center">Name</th>
                                        <th style="vertical-align: middle; text-align: center" width="130px">Role(s)</th>
                                        <th style="vertical-align: middle; text-align: center" width="110px">Action</th>
                                        @if (session('rol_admin') == '1' || session('rol_manager') == '1')
                                            <th style="vertical-align: middle; text-align: center" width="70px">Active
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td style="vertical-align: middle; text-align: left">
                                                {{ $user->usr_last_name }}, {{ $user->usr_first_name }}
                                                {{ $user->usr_middle_name }}
                                                <br />
                                                <small>{{ $user->usr_email }}</small>
                                                <br />
                                                <em><small>Last login: {{ getLastLogin($user->usr_id) }}</small></em>
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                @if (!empty($user->roles))
                                                    @foreach (explode(', ', $user->roles) as $role)
                                                        <span class="badge bg-success">{{ $role }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge bg-danger">No Role Assigned</span>
                                                @endif
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                <a class="btn btn-warning btn-sm mb-2" href="javascript:void(0)"
                                                    data-toggle="modal" data-target="#updateRoleModal-{{ $user->usr_id }}">
                                                    <span class="fa fa-edit"></span> Update
                                                </a>
                                                <a class="btn btn-info btn-sm" href="javascript:void(0)" data-toggle="modal"
                                                    data-target="#resetModal-{{ $user->usr_uuid }}">
                                                    <span class="fa fa-key"></span> Reset
                                                </a>
                                            </td>
                                            @if (session('rol_admin') == '1' || session('rol_manager') == '1')
                                                <td style="vertical-align: middle; text-align: center">
                                                    @if ($user->usr_active == '1')
                                                        <a
                                                            href="{{ action('App\Http\Controllers\UserController@employees_deactivate', [$user->usr_uuid]) }}">
                                                            <span class="fa fa-toggle-on"></span>
                                                        </a>
                                                    @else
                                                        <a
                                                            href="{{ action('App\Http\Controllers\UserController@employees_activate', [$user->usr_uuid]) }}">
                                                            <span class="fa fa-toggle-off"></span>
                                                        </a>
                                                    @endif
                                                </td>
                                            @endif

                                            {{-- Update Role Modal --}}
                                            <div class="modal fade" id="updateRoleModal-{{ $user->usr_id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form action="{{ url('user/update/role', $user->usr_id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">
                                                                    Update Roles of {{ $user->usr_first_name }} {{ $user->usr_last_name }}
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="rol_id">Roles: <span
                                                                            style="color:red;">*</span></label>
                                                                    <select class="select2" multiple="multiple"
                                                                        data-placeholder="Select Role(s)"
                                                                        style="width:100%;" name="roles[]">
                                                                        @foreach ($roles as $role)
                                                                            <option value="{{ $role->rol_id }}"
                                                                                @if (isset($user->roles) && in_array($role->rol_name, explode(', ', $user->roles))) selected @endif>
                                                                                {{ $role->rol_name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">
                                                                    <span class="fa fa-close"></span> Close
                                                                </button>
                                                                <button type="submit" class="btn btn-success">
                                                                    <span class="fa fa-save"></span> Update
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            {{-- Reset Password Modal --}}
                                            <div class="modal fade" id="resetModal-{{ $user->usr_uuid }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form method="POST"
                                                            action="{{ action('App\Http\Controllers\UserController@users_reset_password', [$user->usr_uuid]) }}">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Please
                                                                    Confirm
                                                                </h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to reset this user's password to
                                                                    <strong>123456</strong>?
                                                                </p>
                                                                <small>{{ $user->usr_email }}</small>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">
                                                                    <span class="fa fa-close"></span> Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">
                                                                    <span class="fa fa-refresh"></span> Confirm Reset
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Role Info Column -->
                        <div class="col-lg-4 col-md-5">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <strong><i class="fa fa-info-circle"></i> Role Information</strong>
                                </div>
                                <div class="card-body" style="overflow-y: auto;">
                                    @foreach ($roles as $role)
                                        <div class="mb-3">
                                            <h6 class="text-dark mb-1">
                                                <i class="fa fa-user-tag"></i> {{ $role->rol_name }}
                                            </h6>
                                            <p class="text-muted small mb-0">
                                                {{ $role->rol_description ?? 'No description available' }}
                                            </p>
                                            <hr>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(function() {
            // Initialize Select2 Elements with classic theme
            $('.select2').select2({
                theme: "classic"
            });
        });
    </script>

    {{-- Dynamic Search While Typing --}}
    <script>
        document.getElementById("searchInput").addEventListener("keyup", function() {
            let value = this.value.toLowerCase();

            // Select all tables with ID containing "Table"
            let tables = document.querySelectorAll('table[id*="Table"]');

            tables.forEach(function(table) {
                let rows = table.querySelectorAll("tbody tr");

                rows.forEach(function(row) {
                    let text = row.innerText.toLowerCase();
                    row.style.display = text.includes(value) ? "" : "none";
                });
            });
        });
    </script>
@endsection