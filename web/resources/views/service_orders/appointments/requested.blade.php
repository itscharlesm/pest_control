@extends('layouts.themes.main')

@section('content')
    {{-- Content Header --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Requested Appointments</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ action('App\Http\Controllers\AdminController@home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">Appointments</li>
                        <li class="breadcrumb-item active">Requested</li>
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
                            <a class="btn btn-danger btn-md mb-3" href="{{ url('management/addresses/deleted') }}">
                                <span class="fa fa-archive"></span> Deleted Addresses
                            </a>
                            @if (session('SUPERADMIN') == '1' || session('ADMIN') == '1')
                                <button type="button" class="btn btn-success mb-3" data-toggle="modal"
                                    data-target="#addAddressModal">
                                    <span class="fa fa-plus"></span> Add Address
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <!-- Table Column -->
                        <div class="col-lg-12 col-md-7">
                            <form method="GET" action="{{ url('management/addresses/active') }}" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="search" id="searchInput" class="form-control"
                                        placeholder="Search addresses..." value="{{ request('search') }}">
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
                                        <th class="text-center">No</th>
                                        <th class="text-center">Client</th>
                                        <th class="text-center">Branch</th>
                                        <th class="text-center">Is Termite</th>
                                        <th class="text-center">Payment Status</th>
                                        <th class="text-center">Date Requested</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($appointments as $appointment)
                                        <tr>
                                            <td style="vertical-align: middle; text-align: center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                {{ $appointment->usr_last_name }}, {{ $appointment->usr_first_name }}
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                {{ $appointment->branch_name }}
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                @if ($appointment->svc_is_termite == 1)
                                                    YES
                                                @else
                                                    NO
                                                @endif
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                {{ $appointment->svc_payment_status }}
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">{{ \Carbon\Carbon::parse($appointment->svc_date_created)->format('m/d/Y | h:i A') }}</td>
                                            <td style="vertical-align: middle; text-align: center">
                                                {{ $appointment->svc_status }}
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                <a class="btn btn-warning btn-sm mb-1"
                                                    href="{{ url('service/orders/appointments/requested/' . $appointment->svc_id) }}">
                                                    <span class="fa fa-eye"></span>
                                                </a>

                                                @if (session('SUPERADMIN') == '1' || session('ADMIN') == '1')
                                                    <a class="btn btn-danger btn-sm mb-1" href="javascript:void(0)"
                                                        data-toggle="modal"
                                                        data-target="#deleteModal-{{ $appointment->svc_id }}">
                                                        <span class="fa fa-trash"></span>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- Delete Modal --}}
                                        <div class="modal fade" id="deleteModal-{{ $appointment->svc_id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST"
                                                        action="{{ action('App\Http\Controllers\AppointmentController@delete_appointment', [$appointment->svc_id]) }}">
                                                        @csrf
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5>Confirm Delete</h5>
                                                        </div>
                                                        <div class="modal-body">
                                                            Delete appointment of
                                                            <strong>
                                                                {{ $appointment->usr_first_name }}
                                                                {{ $appointment->usr_last_name }}
                                                            </strong>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Add Address Modal --}}
    <div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog" aria-labelledby="addAddressModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xs" role="document">
            <form action="{{ url('management/addresses/add') }}" method="POST">
                @csrf

                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title text-white" id="addAddressModalLabel">
                            <span class="fa fa-plus text-white"></span> Add Address
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            {{-- Address Name --}}
                            <div class="col-md-12 mb-3">
                                <label for="add_name">Address Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="add_address_name" name="add_name"
                                    placeholder="Address Identifier" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <span class="fa fa-close"></span> Close
                        </button>
                        <button type="submit" class="btn btn-success">
                            <span class="fa fa-save"></span> Save Address Identifier
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

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