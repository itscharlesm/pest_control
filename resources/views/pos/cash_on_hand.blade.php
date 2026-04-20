@extends('admin.themes.layouts.main')

@section('title', 'Product Details')

@section('content')
{{-- Content Header) --}}
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Cash Management</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a
                            href="{{ action('App\Http\Controllers\AdminController@home') }}">Home</a></li>
                    <li class="breadcrumb-item">Transactions</li>
                    <li class="breadcrumb-item">Cash Management</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- Main Content --}}
<section class="content">
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" id="success-alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fa fa-check"></i> Alert!</h5>
                {{ session('success') }}
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    setTimeout(function () {
                        var alert = document.getElementById('success-alert');
                        if (alert) {
                            alert.style.transition = 'opacity 0.5s ease';
                            alert.style.opacity = '0';
                            setTimeout(function () {
                                alert.remove();
                            }, 500);
                        }
                    }, 1000);
                });
            </script>
        @endif
        <div class="row mt-3">
            <div class="col-md-12">
                @if(session('usr_type') == '1' || session('usr_type') == '2' || session('usr_type') == '3' || session('usr_type') == '4')
                    @if(!$isDayDone)
                        @if($cashOnHandToday)
                            <a class="btn btn-danger float-right mb-3" href="javascript:void(0)" data-toggle="modal"
                                data-target="#endCashModal">
                                <i class="fa fa-money-bill"></i> End the day
                            </a>
                        @else
                            <a class="btn btn-primary float-right mb-3" href="javascript:void(0)" data-toggle="modal"
                                data-target="#setCashModal">
                                <i class="fa fa-money-bill"></i> Set cash
                            </a>
                        @endif
                    @endif
                @endif
                <table class="table table-hover table-striped" id="RegTable">
                    <thead class="bg-gradient-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Starting Cash</th>
                            <th class="text-center">Employee</th>
                            <th class="text-center">Cash on Hand</th>
                            <th class="text-center">End of the Day Cash</th>
                            <th class="text-center">Confirmed by</th>
                            @if(session('usr_type') == 1 || session('usr_type') == 2 || session('usr_type') == 3)
                                <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cash_on_hand as $index => $cash)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td class="text-center align-middle">
                                    {{ \Carbon\Carbon::parse($cash->coh_date_created)->format('F j, Y') }}
                                </td>
                                <td class="text-center align-middle">₱{{ number_format($cash->coh_starting_cash, 2) ?? 0  }}
                                </td>
                                <td class="text-center align-middle">{{ $cash->usr_first_name }} {{ $cash->usr_last_name }}</td>
                                <td class="text-center align-middle">₱{{ number_format($cash->coh_on_hand_cash, 2) ?? 0  }}
                                </td>
                                <td class="text-center align-middle">
                                    @if ($cash->coh_ending_cash === null)
                                        N/A
                                    @else
                                        ₱{{ number_format($cash->coh_ending_cash, 2) }}
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    {{ $cash->usr_first_name ?? 'N/A' }} {{ $cash->usr_last_name ?? '' }}
                                </td>
                                @if(session('usr_type') == 1 || session('usr_type') == 2 || session('usr_type') == 3)
                                    <td class="text-center align-middle">end</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

{{-- Set cash --}}
<div class="modal fade" id="setCashModal" tabindex="-1" role="dialog" aria-labelledby="setCashModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setCashModalLabel">Set starting cash for today</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ action('App\Http\Controllers\POSController@starting_cash') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="coh_starting_cash">Enter money:</label>
                                <input type="number" class="form-control" name="coh_starting_cash" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Set Cash</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Ending cash --}}
<div class="modal fade" id="endCashModal" tabindex="-1" role="dialog" aria-labelledby="endCashModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="endCashModalLabel">End the day</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ action('App\Http\Controllers\POSController@ending_cash') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="coh_starting_cash">Starting Cash:</label>
                                <input type="number" class="form-control"
                                    value="{{ $latestCashOnHand->coh_starting_cash ?? '' }}" step="0.01" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="coh_on_hand_cash">Cash on Hand:</label>
                                <input type="number" class="form-control"
                                    value="{{ $latestCashOnHand->coh_on_hand_cash ?? '' }}" step="0.01" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="coh_ending_cash">Confirm money:</label>
                                <input type="number" class="form-control" name="coh_ending_cash" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">End the day</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection