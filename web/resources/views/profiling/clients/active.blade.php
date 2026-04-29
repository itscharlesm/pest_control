@extends('layouts.themes.main')

@section('content')
    {{-- Content Header --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Clients</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ action('App\Http\Controllers\AdminController@home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">Profiling</li>
                        <li class="breadcrumb-item active">Clients</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <section class="content">
        @include('layouts.partials.onclick')
        @include('layouts.partials.alerts')
        @include('layouts.partials.modal_style')
        <div class="container-fluid">
            <div class="card">
                <div class="card-body overflow-auto">
                    <div class="row">
                        <div class="col-md-12">
                            @if (session('SUPERADMIN') == '1' || session('ADMIN') == '1')
                                <a class="btn btn-danger btn-md mb-3" href="{{ url('profiling/clients/deleted') }}">
                                    <span class="fa fa-archive"></span> Deleted Clients
                                </a
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <!-- Table Column -->
                        <div class="col-lg-12 col-md-7">
                            <form method="GET" action="{{ url('profiling/clients/active') }}" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="search" id="searchInput" class="form-control"
                                        placeholder="Search clients..." value="{{ request('search') }}">
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
                                        <th style="vertical-align: middle; text-align: center">Branch</th>
                                        <th style="vertical-align: middle; text-align: center" width="130px">Availabilities
                                        </th>
                                        <th style="vertical-align: middle; text-align: center" width="110px">Action</th>
                                        @if (session('rol_admin') == '1' || session('rol_manager') == '1')
                                            <th style="vertical-align: middle; text-align: center" width="70px">Active
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clients as $client)
                                        <tr>
                                            <td style="vertical-align: middle; text-align: left">
                                                {{ $client->usr_last_name }}, {{ $client->usr_first_name }}
                                                {{ $client->usr_middle_name }}
                                                <br />
                                                <small>{{ $client->usr_email }}</small>
                                                <br />
                                                <em><small>Last login: {{ getLastLogin($client->usr_id) }}</small></em>
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                {{ $client->branch_name }}
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                @if (!empty($client->availabilities))
                                                    <span class="badge badge-success">
                                                        {{ $client->availabilities }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">None</span>
                                                @endif
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                <a class="btn btn-primary btn-sm mb-1" href="javascript:void(0)"
                                                    data-toggle="modal"
                                                    data-target="#viewClientModal-{{ $client->usr_id }}">
                                                    <span class="fa fa-eye"></span>
                                                </a>
                                                <a class="btn btn-info btn-sm mb-1" href="javascript:void(0)"
                                                    data-toggle="modal"
                                                    data-target="#resetModal-{{ $client->usr_id }}">
                                                    <span class="fa fa-key"></span>
                                                </a>
                                                <a class="btn btn-danger btn-sm mb-1" href="javascript:void(0)"
                                                    data-toggle="modal"
                                                    data-target="#deleteModal-{{ $client->usr_id }}">
                                                    <span class="fa fa-trash"></span>
                                                </a>
                                            </td>

                                            {{-- Reset Password Modal --}}
                                            <div class="modal fade" id="resetModal-{{ $client->usr_id }}"
                                                tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form method="POST"
                                                            action="{{ action('App\Http\Controllers\ProfilingController@clients_reset_password', [$client->usr_id]) }}">
                                                            @csrf
                                                            <div class="modal-header bg-info text-white">
                                                                <h5 class="modal-title text-black" id="exampleModalLabel">
                                                                    Please Confirm
                                                                </h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to RESET this client's password
                                                                    to
                                                                    <strong>123456</strong>?
                                                                </p>
                                                                <small>{{ $client->usr_first_name }}
                                                                    {{ $client->usr_last_name }}<br>{{ $client->usr_email }}</small>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">
                                                                    <span class="fa fa-close"></span> Close
                                                                </button>
                                                                <button type="submit" class="btn btn-info">
                                                                    <span class="fa fa-refresh"></span> Confirm Reset
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Delete Modal --}}
                                            <div class="modal fade" id="deleteModal-{{ $client->usr_id }}"
                                                tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form method="POST"
                                                            action="{{ action('App\Http\Controllers\ProfilingController@clients_delete', [$client->usr_id]) }}">
                                                            @csrf
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title text-white" id="exampleModalLabel">
                                                                    Please Confirm
                                                                </h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p><strong>Are you sure you want to DELETE client
                                                                        {{ $client->usr_first_name }}
                                                                        {{ $client->usr_last_name }}</strong>?
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">
                                                                    <span class="fa fa-close"></span> Close
                                                                </button>
                                                                <button type="submit" class="btn btn-danger">
                                                                    <span class="fa fa-trash"></span> Confirm Delete
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
                    </div>
                </div>
            </div>
        </div>
    </section>

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