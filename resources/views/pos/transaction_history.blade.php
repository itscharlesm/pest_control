@extends('admin.themes.layouts.main')

@section('title', 'Product Details')

@section('content')
{{-- Content Header) --}}
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Transaction History</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a
                            href="{{ action('App\Http\Controllers\AdminController@home') }}">Home</a></li>
                    <li class="breadcrumb-item">Transactions</li>
                    <li class="breadcrumb-item">Transaction History</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- Main Content --}}
<section class="content">
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-md-12">
                <table class="table table-hover table-striped" id="RegTable">
                    <thead class="bg-gradient-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Total Orders</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Payment</th>
                            <th class="text-center">Change</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $index => $transaction)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ $transaction->formatted_date }}</td>
                                <td class="text-center">{{ $transaction->mtrx_total_orders }}</td>
                                <td class="text-right">₱{{ number_format($transaction->mtrx_total, 2) ?? 0  }}</td>
                                <td class="text-right">₱{{ number_format($transaction->mtrx_cash, 2) ?? 0  }}</td>
                                <td class="text-right">₱{{ number_format($transaction->mtrx_change, 2) ?? 0 }}</td>
                                <td class="text-center">
                                    <a class="btn btn-primary" href="javascript:void(0)" data-toggle="modal"
                                        data-target="#viewTransactionModal-{{ $transaction->mtrx_id }}">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

{{-- View Transaction Modals --}}
@foreach($transactions as $transaction)
    <div class="modal fade" id="viewTransactionModal-{{ $transaction->mtrx_id }}" tabindex="-1" role="dialog"
        aria-labelledby="viewTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewTransactionModalLabel">Transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="mtrx_date_created">Date and Time:</label>
                                <input type="text" class="form-control" id="mtrx_date_created"
                                    value="{{ $transaction->formatted_date }}" readonly>
                            </div>
                        </div>

                        @foreach($transaction->orders as $orderIndex => $order)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="menu_name">Order {{ $orderIndex + 1 }}:</label>
                                    <input type="text" class="form-control" id="menu_name" value="{{ $order->menu_name }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mcat_name">Category:</label>
                                    <input type="text" class="form-control" id="mcat_name" value="{{ $order->mcat_name }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mtrxo_order_price">Price:</label>
                                    <input type="text" class="form-control" id="mtrxo_order_price"
                                        value="₱{{ number_format($order->mtrxo_order_price, 2) }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mtrxo_order_quantity">Order Quantity:</label>
                                    <input type="text" class="form-control" id="mtrxo_order_quantity"
                                        value="{{ $order->mtrxo_order_quantity }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="mtrxo_total_amount">Total Amount:</label>
                                    <input type="text" class="form-control" id="mtrxo_total_amount"
                                        value="₱{{ number_format($order->mtrxo_total_amount, 2) }}" readonly>
                                </div>
                                <hr>
                            </div>
                        @endforeach

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mtrx_total_orders">Total Orders:</label>
                                <input type="text" class="form-control" id="mtrx_total_orders"
                                    value="{{ $transaction->mtrx_total_orders }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mtrx_total">Total:</label>
                                <input type="text" class="form-control" id="mtrx_total"
                                    value="₱{{ number_format($transaction->mtrx_total, 2) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mtrx_discount_whole">Whole Discount:</label>
                                <input type="text" class="form-control" id="mtrx_discount_whole"
                                    value="{{ $transaction->mtrx_discount_whole }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mtrx_discount_percent">Percentage Discount:</label>
                                <input type="text" class="form-control" id="mtrx_discount_percent"
                                    value="{{ $transaction->mtrx_discount_percent }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mtrx_cash">Payment:</label>
                                <input type="text" class="form-control" id="mtrx_cash"
                                    value="₱{{ number_format($transaction->mtrx_cash, 2) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mtrx_change">Change:</label>
                                <input type="text" class="form-control" id="mtrx_change"
                                    value="₱{{ number_format($transaction->mtrx_change, 2) }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection