@extends('layouts.themes.main')

@section('content')
    {{-- Content Header --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Deleted Services</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ action('App\Http\Controllers\AdminController@home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">Management</li>
                        <li class="breadcrumb-item active">Deleted Services</li>
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
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <a class="btn btn-success btn-md" href="{{ url('management/services/active') }}">
                                <span class="fa fa-list"></span> Services
                            </a>
                        </div>
                    </div>

                    {{-- Search Bar --}}
                    <form method="GET" action="{{ url('management/services/deleted') }}" class="mb-3">
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

                    <div class="row">
                        {{-- COLUMN 1: General Service Area Pricing --}}
                        <div class="col-lg-12 col-md-12">
                            <div class="card mb-4">
                                <div class="card-header bg-light d-flex align-items-center">
                                    <span class="fa fa-map-marker-alt mr-2"></span>
                                    <div>
                                        <strong>Service Area Pricing</strong>
                                        <small class="d-block" style="font-size:11px; opacity:.85;">
                                            Standard cost per area &amp; branch
                                        </small>
                                    </div>
                                    <span class="badge badge-light ml-auto">{{ $services->total() }} areas</span>
                                </div>
                                <div class="card-body p-0">
                                    <table id="serviceTable"
                                        class="table table-hover table-bordered table-sm responsive mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="vertical-align:middle; text-align:center">Area</th>
                                                <th style="vertical-align:middle; text-align:center">Cost</th>
                                                <th style="vertical-align:middle; text-align:center">Branch</th>
                                                <th style="vertical-align:middle; text-align:center" width="80px">Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($services as $service)
                                                <tr>
                                                    <td style="vertical-align:middle">{{ $service->svcpa_area }}</td>
                                                    <td style="vertical-align:middle; text-align:center">
                                                        ₱ {{ number_format($service->svcpa_cost, 2) }}
                                                    </td>
                                                    <td style="vertical-align:middle; text-align:center">
                                                        {{ $service->branch_name }}
                                                    </td>
                                                    <td style="vertical-align:middle; text-align:center">
                                                        @if (session('SUPERADMIN') == '1' || session('ADMIN') == '1')
                                                            <a class="btn btn-success btn-sm mb-1" href="javascript:void(0)"
                                                                data-toggle="modal"
                                                                data-target="#restoreServiceModal-{{ $service->svcpa_id }}">
                                                                <span class="fa fa-refresh"></span>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>

                                                {{-- Restore Service Modal --}}
                                                <div class="modal fade" id="restoreServiceModal-{{ $service->svcpa_id }}"
                                                    tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <form
                                                                action="{{ url('management/services/area/restore', $service->svcpa_id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-header bg-success text-white">
                                                                    <h5 class="modal-title text-white">Please Confirm</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal">
                                                                        <span>&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to <strong>RESTORE</strong>
                                                                        service area
                                                                        <strong>{{ $service->svcpa_area }}</strong>?
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">
                                                                        <span class="fa fa-close"></span> Close
                                                                    </button>
                                                                    <button type="submit" class="btn btn-success">
                                                                        <span class="fa fa-refresh"></span> Confirm Restore
                                                                    </button>
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