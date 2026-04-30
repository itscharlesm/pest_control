@extends('layouts.themes.main')

@section('content')
    {{-- Content Header --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Branches</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ action('App\Http\Controllers\AdminController@home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">Management</li>
                        <li class="breadcrumb-item active">Branches</li>
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
                            <a class="btn btn-danger btn-md mb-3" href="{{ url('management/branches/deleted') }}">
                                <span class="fa fa-archive"></span> Deleted Branches
                            </a>
                            @if (session('SUPERADMIN') == '1' || session('ADMIN') == '1')
                                <button type="button" class="btn btn-success mb-3" data-toggle="modal"
                                    data-target="#addBranchModal">
                                    <span class="fa fa-plus"></span> Add Branch
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <!-- Table Column -->
                        <div class="col-lg-12 col-md-7">
                            <form method="GET" action="{{ url('management/branches/active') }}" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="search" id="searchInput" class="form-control"
                                        placeholder="Search branches..." value="{{ request('search') }}">
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
                                        <th style="vertical-align: middle; text-align: center">No</th>
                                        <th style="vertical-align: middle; text-align: center">Branch</th>
                                        <th style="vertical-align: middle; text-align: center">Created By</th>
                                        <th style="vertical-align: middle; text-align: center">Modified By</th>
                                        @if (session('SUPERADMIN') == '1' || session('ADMIN') == '1')
                                            <th style="vertical-align: middle; text-align: center" width="110px">Action
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($branches as $branch)
                                        <tr>
                                            <td style="vertical-align: middle; text-align: center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                {{ $branch->branch_name }}
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                @if (!empty($branch->created_first_name))
                                                    {{ $branch->created_first_name }} {{ $branch->created_last_name }} -
                                                    {{ \Carbon\Carbon::parse($branch->branch_date_created)->format('m/d/Y | h:i A') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td style="vertical-align: middle; text-align: center">
                                                @if (!empty($branch->modified_first_name))
                                                    {{ $branch->modified_first_name }}
                                                    {{ $branch->modified_last_name }} -
                                                    {{ \Carbon\Carbon::parse($branch->branch_date_modified)->format('m/d/Y | h:i A') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            @if (session('SUPERADMIN') == '1' || session('ADMIN') == '1')
                                                <td style="vertical-align: middle; text-align: center">
                                                    <a class="btn btn-danger btn-sm mb-1" href="javascript:void(0)"
                                                        data-toggle="modal"
                                                        data-target="#deleteModal-{{ $branch->branch_id }}">
                                                        <span class="fa fa-trash"></span>
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>

                                        {{-- Delete Modal --}}
                                        <div class="modal fade" id="deleteModal-{{ $branch->branch_id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form method="POST"
                                                        action="{{ action('App\Http\Controllers\ManagementController@branches_delete', [$branch->branch_id]) }}">
                                                        @csrf
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title text-white" id="exampleModalLabel">
                                                                Please Confirm
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to <strong>DELETE</strong> branch -
                                                                <strong>{{ $branch->branch_name }}</strong>?
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

    {{-- Add Branch Modal --}}
    <div class="modal fade" id="addBranchModal" tabindex="-1" role="dialog" aria-labelledby="addBranchModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xs" role="document">
            <form action="{{ url('management/branches/add') }}" method="POST">
                @csrf

                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title text-white" id="addBranchModalLabel">
                            <span class="fa fa-plus text-white"></span> Add Branch
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            {{-- Branch Name --}}
                            <div class="col-md-12 mb-3">
                                <label for="branch_name">Branch Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="add_branch_name" name="branch_name"
                                    placeholder="Branch Name" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <span class="fa fa-close"></span> Close
                        </button>
                        <button type="submit" class="btn btn-success">
                            <span class="fa fa-save"></span> Save Branch
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