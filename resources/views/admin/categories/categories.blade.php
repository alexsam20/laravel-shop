@extends('admin.layout.layout')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Categories</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Categories</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        @if(Session::has('success_message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success:</strong> {{ Session::get('success_message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="card">
                            <div class="card-header">
                                @if((isset($categoriesModule['edit_access']) && $categoriesModule['edit_access'] == 1) ||
                                    (isset($categoriesModule['full_access']) && $categoriesModule['full_access'] == 1))
                                <h3 class="card-title">Categories</h3>
                                <a class="btn btn-block btn-primary"
                                   style="max-width: 150px; float: right; display: inline-block"
                                   href="{{ url('admin/add-edit-category') }}">
                                    Add Category
                                </a>
                                @endif
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="categories" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Parent Category</th>
                                        <th>URL</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>{{ $category['id'] }}</td>
                                            <td>{{ $category['category_name'] }}</td>
                                            <td>
                                                @if($category['parentCategory'] !== null)
                                                    {{ $category['parentCategory']['category_name'] }}
                                                @endif
                                            </td>
                                            <td>{{ $category['url'] }}</td>
                                            <td>{{ date("F j, Y, g:i a", strtotime($category['created_at'])) }}</td>
                                            <td style="text-align: center">
                                                @if((isset($categoriesModule['edit_access']) && $categoriesModule['edit_access'] == 1) ||
                                                    (isset($categoriesModule['full_access']) && $categoriesModule['full_access'] == 1))
                                                    @if($category['status'] === 1)
                                                        <a class="updateCategoryStatus"
                                                           id="category-{{ $category['id'] }}"
                                                           category_id="{{ $category['id'] }}"
                                                           style="color: #3f6ed3"
                                                           href="javascript:void(0)">
                                                            <i class="fas fa-toggle-on" status="Active"></i>
                                                        </a>
                                                    @else
                                                        <a class="updateCategoryStatus"
                                                           id="category-{{ $category['id'] }}"
                                                           category_id="{{ $category['id'] }}"
                                                           style="color: grey"
                                                           href="javascript:void(0)">
                                                            <i class="fas fa-toggle-off" status="Inactive"></i>
                                                        </a>
                                                    @endif
                                                    &nbsp;
                                                    <a href="{{ url('admin/add-edit-category/' . $category['id']) }}"
                                                       style="color: #3f6ed3;"><i class="fas fa-edit"></i></a>
                                                    &nbsp;
                                                @endif
                                                @if(isset($categoriesModule['full_access']) && $categoriesModule['full_access'] == 1)
                                                    <a href="javascript:void(0)" record="category"
                                                       recordid="{{ $category['id'] }}"
                                                       class="confirmDelete"
                                                       name="CMS category" title="Delete CMS category"
                                                       style="color: #3f6ed3;"><i class="fas fa-trash"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
