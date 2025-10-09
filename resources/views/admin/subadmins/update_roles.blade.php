@extends('admin.layout.layout')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sub Admin</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if(Session::has('error_message'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error:</strong> {{ Session::get('error_message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif
                            @if(Session::has('success_message'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success:</strong> {{ Session::get('success_message') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <!-- form start -->
                            <form name="subadminForm" id="subadminForm" method="post"
                            action="{{ url('admin/update-role/' . $id) }}">
                            @csrf
                                <input type="hidden" name="subadmin_id" value="{{ $id }}">
                            @if(!empty($subadminRoles))
                                @foreach($subadminRoles as $role)
                                    @if($role['module'] == 'cms_pages')
                                        {{--@if($role['view_access'] == 1)
                                            @php $viewCMSPages = 'checked' @endphp
                                        @else
                                            @php $viewCMSPages = '' @endphp
                                        @endif
                                        @if($role['edit_access'] == 1)
                                            @php $editCMSPages = 'checked' @endphp
                                        @else
                                            @php $editCMSPages = '' @endphp
                                        @endif
                                        @if($role['full_access'] == 1)
                                            @php $fullCMSPages = 'checked' @endphp
                                        @else
                                            @php $fullCMSPages = '' @endphp
                                        @endif--}}
                                        @php $viewCMSPages = ($role['view_access'] == 1) ? 'checked' : ''; @endphp
                                        @php $editCMSPages = ($role['edit_access'] == 1) ? 'checked' : ''; @endphp
                                        @php $fullCMSPages = ($role['full_access'] == 1) ? 'checked' : ''; @endphp
                                    @endif
                                    @if($role['module'] == 'categories')
                                        @php $viewCategories = ($role['view_access'] == 1) ? 'checked' : ''; @endphp
                                        @php $editCategories = ($role['edit_access'] == 1) ? 'checked' : ''; @endphp
                                        @php $fullCategories = ($role['full_access'] == 1) ? 'checked' : ''; @endphp
                                    @endif
                                    @if($role['module'] == 'products')
                                        @php $viewProducts = ($role['view_access'] == 1) ? 'checked' : ''; @endphp
                                        @php $editProducts = ($role['edit_access'] == 1) ? 'checked' : ''; @endphp
                                        @php $fullProducts = ($role['full_access'] == 1) ? 'checked' : ''; @endphp
                                    @endif
                                    @if($role['module'] == 'brands')
                                        @php $viewBrands = ($role['view_access'] == 1) ? 'checked' : ''; @endphp
                                        @php $editBrands = ($role['edit_access'] == 1) ? 'checked' : ''; @endphp
                                        @php $fullBrands = ($role['full_access'] == 1) ? 'checked' : ''; @endphp
                                    @endif
                                @endforeach
                            @endif
                            <div class="card-body">
                                <div class="form-group">
                                    <label style="width: 130px;" for="cms_pages">CMS Pages:</label>
                                    <input type="checkbox" name="cms_pages[view]" value="1" @if(isset($viewCMSPages)) {{ $viewCMSPages }} @endif>&nbsp;View Access &nbsp;&nbsp;
                                    <input type="checkbox" name="cms_pages[edit]" value="1" @if(isset($editCMSPages)) {{ $editCMSPages }} @endif>&nbsp;View/Edit Access &nbsp;&nbsp;
                                    <input type="checkbox" name="cms_pages[full]" value="1" @if(isset($fullCMSPages)) {{ $fullCMSPages }} @endif>&nbsp;Full Access &nbsp;&nbsp;
                                </div>
                                <div class="form-group">
                                    <label style="width: 130px;" for="categories">Categories:</label>
                                    <input type="checkbox" name="categories[view]" value="1" @if(isset($viewCategories)) {{ $viewCategories }} @endif>&nbsp;View Access &nbsp;&nbsp;
                                    <input type="checkbox" name="categories[edit]" value="1" @if(isset($editCategories)) {{ $editCategories }} @endif>&nbsp;View/Edit Access &nbsp;&nbsp;
                                    <input type="checkbox" name="categories[full]" value="1" @if(isset($fullCategories)) {{ $fullCategories }} @endif>&nbsp;Full Access &nbsp;&nbsp;
                                </div>
                                <div class="form-group">
                                    <label style="width: 130px;" for="products">Products:</label>
                                    <input type="checkbox" name="products[view]" value="1" @if(isset($viewProducts)) {{ $viewProducts }} @endif>&nbsp;View Access &nbsp;&nbsp;
                                    <input type="checkbox" name="products[edit]" value="1" @if(isset($editProducts)) {{ $editProducts }} @endif>&nbsp;View/Edit Access &nbsp;&nbsp;
                                    <input type="checkbox" name="products[full]" value="1" @if(isset($fullProducts)) {{ $fullProducts }} @endif>&nbsp;Full Access &nbsp;&nbsp;
                                </div>
                                <div class="form-group">
                                    <label style="width: 130px;" for="brands">Brands:</label>
                                    <input type="checkbox" name="brands[view]" value="1" @if(isset($viewBrands)) {{ $viewBrands }} @endif>&nbsp;View Access &nbsp;&nbsp;
                                    <input type="checkbox" name="brands[edit]" value="1" @if(isset($editBrands)) {{ $editBrands }} @endif>&nbsp;View/Edit Access &nbsp;&nbsp;
                                    <input type="checkbox" name="brands[full]" value="1" @if(isset($fullBrands)) {{ $fullBrands }} @endif>&nbsp;Full Access &nbsp;&nbsp;
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="form-group" style="margin-top: -20px; margin-left: 20px;">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                            </form>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.card-body -->
                {{--<div class="card-footer"></div>--}}
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
