@extends('admin.themes.layouts.main')

@section('title', 'Product Details')

@section('content')
{{-- Content Header) --}}
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Categories</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a
                            href="{{ action('App\Http\Controllers\AdminController@home') }}">Home</a></li>
                    <li class="breadcrumb-item">Utility</li>
                    <li class="breadcrumb-item">Manage Categories</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- Main Content --}}
<section class="content">
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
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-md-12">
                @if(session('usr_type') == '1')
                    <a class="btn btn-primary float-right mb-3" href="javascript:void(0)" data-toggle="modal"
                        data-target="#newCategoryModal"><i class="fa fa-plus"></i> Add New Category</a>
                @endif
                <table class="table table-hover table-striped" id="RegTable">
                    <thead class="bg-gradient-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Category Name</th>
                            <th class="text-center">Date Created</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $index => $category)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ $category->mcat_name }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($category->mcat_date_created)->format('F d Y') }}
                                </td>
                                <th class="text-center">
                                    <a class="btn btn-primary" href="javascript:void(0)" data-toggle="modal"
                                        data-target="#editCategoryModal-{{ $category->mcat_id }}">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

{{-- Add New Category --}}
<div class="modal fade" id="newCategoryModal" tabindex="-1" role="dialog" aria-labelledby="newCategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newCategoryModalLabel">Create New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ action('App\Http\Controllers\UtilityController@category_create') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="mcat_name">Category Name:</label>
                                <input type="text" class="form-control" name="mcat_name" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit Category --}}
@foreach($categories as $category)
    <div class="modal fade" id="editCategoryModal-{{ $category->mcat_id }}" tabindex="-1" role="dialog"
        aria-labelledby="editCategoryModalLabel-{{ $category->mcat_id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel-{{ $category->mcat_id }}">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form
                        action="{{ action('App\Http\Controllers\UtilityController@category_update', $category->mcat_id) }}"
                        method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="mcat_name">Category Name:</label>
                                    <input type="text" class="form-control" name="mcat_name"
                                        value="{{ $category->mcat_name }}" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection