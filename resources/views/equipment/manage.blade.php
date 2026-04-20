@extends('admin.themes.layouts.main')
@section('content')
    {{-- Content Header) --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manage Equipment</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ action('App\Http\Controllers\AdminController@home') }}">Home</a></li>
                        <li class="breadcrumb-item">Equipment</li>
                        <li class="breadcrumb-item active">Manage Equipment</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content  --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#newEquipmentModal"><span class="fa fa-plus"></span> New Equipment</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Equipment Name</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Qty.</th>
                                <th>QR Code</th>
                                <th width="70px">Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equipments as $equipment)
                                <tr>
                                    <td>
                                        <a href="javascrip:void(0)" data-toggle="modal" data-target="#editModal{{$equipment->eqp_uuid}}"><span class="fa fa-edit"></span> {{ $equipment->eqp_name }}</a>
                                        @if($equipment->eqp_active == '1')
                                            <span class="badge badge-success"> Active </span>
                                        @else
                                            <span class="badge badge-danger"> Inactive </span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $equipment->eqp_description }}
                                    </td>
                                    <td>
                                        {{ $equipment->cat_name }}
                                    </td>
                                    <td>
                                        {{ $equipment->eqp_quantity }}
                                    </td>
                                    <td>
                                        <a href="{{ action('App\Http\Controllers\QRController@generate',[$equipment->eqp_uuid]) }}" target="_blank"><span class="fa fa-qrcode"></span></a>
                                    </td>
                                    <td>
                                        @if($equipment->eqp_active == '1')
                                            <a href="{{ action('App\Http\Controllers\EquipmentController@deactivate',[$equipment->eqp_uuid]) }}"><span class="fa fa-toggle-on"></span></a>
                                        @else
                                            <a href="{{ action('App\Http\Controllers\EquipmentController@activate',[$equipment->eqp_uuid]) }}"><span class="fa fa-toggle-off"></span></a>
                                        @endif
                                    </td>
                                </tr>
                                <div class="modal fade" id="editModal{{$equipment->eqp_uuid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Equipment</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST" action="{{ action('App\Http\Controllers\EquipmentController@update') }}">
                                            {{ csrf_field() }}
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="eqp_name">Equipment Name <span style="color:red;">*</span></label>
                                                        <input class="form-control" type="text" name="eqp_name" id="eqp_name" placeholder="Name of equipment" value="{{ $equipment->eqp_name }}" required/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="eqp_description">Equipment Description <span style="color:red;">*</span></label>
                                                        <input class="form-control" type="text" name="eqp_description" id="eqp_description" placeholder="Description of equipment" value="{{ $equipment->eqp_description }}" required/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cat_id">Category <span style="color:red;">*</span></label>
                                                        <select class="form-control" name="cat_id" id="cat_id" required>
                                                            @foreach($categories as $category)
                                                                @if($category->cat_id == $equipment->cat_id)
                                                                    <option value="{{ $category->cat_id }}" selected>{{ $category->cat_name }}</option>
                                                                @else 
                                                                    <option value="{{ $category->cat_id }}">{{ $category->cat_name }}</option>
                                                                @endif 
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="eqp_manufacturer">Manufacturer <span style="color:red;">*</span></label>
                                                        <input class="form-control" type="text" name="eqp_manufacturer" id="eqp_manufacturer" placeholder="Manufacturer" value="{{ $equipment->eqp_manufacturer }}" required/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="eqp_date_acquired">Date Acquired <span style="color:red;">*</span></label>
                                                        <input class="form-control" type="date" name="eqp_date_acquired" id="eqp_date_acquired" value="{{ $equipment->eqp_date_acquired }}" required/>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="eqp_quantity">Quantity <span style="color:red;">*</span></label>
                                                        <input class="form-control" type="number" name="eqp_quantity" id="eqp_quantity" value="{{ $equipment->eqp_quantity }}" required/>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="eqp_uuid" value="{{ $equipment->eqp_uuid }}" required />
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Save changes</button>
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
    </section>
    {{-- Modals  --}}
    <div class="modal fade" id="newEquipmentModal" tabindex="-1" role="dialog" aria-labelledby="newEquipmentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newUserModalLabel">Create New Equipment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ action('App\Http\Controllers\EquipmentController@save') }}">
                {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="eqp_name">Equipment Name <span style="color:red;">*</span></label>
                            <input class="form-control" type="text" name="eqp_name" id="eqp_name" placeholder="Name of equipment" required/>
                        </div>
                        <div class="form-group">
                            <label for="eqp_description">Equipment Description <span style="color:red;">*</span></label>
                            <input class="form-control" type="text" name="eqp_description" id="eqp_description" placeholder="Description of equipment" required/>
                        </div>
                        <div class="form-group">
                            <label for="cat_id">Category <span style="color:red;">*</span></label>
                            <select class="form-control" name="cat_id" id="cat_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->cat_id }}">{{ $category->cat_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="eqp_manufacturer">Manufacturer <span style="color:red;">*</span></label>
                            <input class="form-control" type="text" name="eqp_manufacturer" id="eqp_manufacturer" placeholder="Manufacturer" required/>
                        </div>
                        <div class="form-group">
                            <label for="eqp_date_acquired">Date Acquired <span style="color:red;">*</span></label>
                            <input class="form-control" type="date" name="eqp_date_acquired" id="eqp_date_acquired" required/>
                        </div>
                        <div class="form-group">
                            <label for="eqp_quantity">Quantity <span style="color:red;">*</span></label>
                            <input class="form-control" type="number" name="eqp_quantity" id="eqp_quantity" required/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Save equipment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
