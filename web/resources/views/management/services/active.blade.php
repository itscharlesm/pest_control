@extends('layouts.themes.main')

@section('content')
    {{-- Content Header --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Services</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ action('App\Http\Controllers\AdminController@home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">Management</li>
                        <li class="breadcrumb-item active">Services</li>
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

                    {{-- Top Buttons --}}
                    <div class="row">
                        <div class="col-md-12">
                            <a class="btn btn-danger btn-md mb-3" href="{{ url('management/services/deleted') }}">
                                <span class="fa fa-archive"></span> Deleted Services
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        {{-- LEFT: Service Areas Table --}}
                        <div class="col-lg-9 col-md-7">

                            {{-- Search Bar --}}
                            <form method="GET" action="{{ url('management/services') }}" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="search" id="searchInput" class="form-control"
                                        placeholder="Search service areas..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="fa fa-search"></span> Search
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <table id="defaultTable" class="table table-hover table-bordered table-sm responsive">
                                <thead>
                                    <tr>
                                        <th style="vertical-align: middle; text-align: center">Area</th>
                                        <th style="vertical-align: middle; text-align: center">Cost</th>
                                        <th style="vertical-align: middle; text-align: center">Branch</th>
                                        @if (session('SUPERADMIN') == '1' || session('ADMIN') == '1')
                                            <th style="vertical-align: middle; text-align: center" width="100px">Action
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($services as $service)
                                        <tr>
                                            <td style="vertical-align: middle;">
                                                {{ $service->svcpa_area }}
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                ₱ {{ number_format($service->svcpa_cost, 2) }}
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                {{ $service->branch_name }}
                                            </td>
                                            @if (session('SUPERADMIN') == '1' || session('ADMIN') == '1')
                                                <td style="vertical-align: middle; text-align: center">
                                                    <a class="btn btn-warning btn-sm mb-1" href="javascript:void(0)"
                                                        data-toggle="modal"
                                                        data-target="#editServiceModal-{{ $service->svcpa_id }}">
                                                        <span class="fa fa-edit"></span>
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>

                                        {{-- Edit Service Modal --}}
                                        <div class="modal fade" id="editServiceModal-{{ $service->svcpa_id }}"
                                            tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form action="{{ url('management/services/update', $service->svcpa_id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-warning text-white">
                                                            <h5 class="modal-title text-black">Edit Service Area</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Area Name <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="svcpa_area"
                                                                    value="{{ $service->svcpa_area }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Cost (₱) <span class="text-danger">*</span></label>
                                                                <input type="number" step="0.01" class="form-control"
                                                                    name="svcpa_cost" value="{{ $service->svcpa_cost }}"
                                                                    required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Branch</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $service->branch_name }}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">
                                                                <span class="fa fa-close"></span> Close
                                                            </button>
                                                            <button type="submit" class="btn btn-warning">
                                                                <span class="fa fa-save"></span> Update
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- RIGHT: Service Packages Info Panel --}}
                        <div class="col-lg-3 col-md-5">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <strong><i class="fa fa-info-circle"></i> Service Packages</strong>
                                </div>
                                <div class="card-body" style="overflow-y: auto;">
                                    @foreach ($packages as $package)
                                        <div class="mb-3">
                                            <h6 class="text-dark mb-1">
                                                <i class="fa fa-bug"></i> {{ $package->svcp_pest_type }}
                                            </h6>
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

    {{-- Dynamic Search While Typing --}}
    <script>
        document.getElementById("searchInput").addEventListener("keyup", function() {
            let value = this.value.toLowerCase();
            let rows = document.querySelectorAll("#servicesTable tbody tr");
            rows.forEach(function(row) {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(value) ? "" : "none";
            });
        });
    </script>
@endsection