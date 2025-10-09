@extends('admin.layout.layout')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $title }}</h1>
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
                                <!-- form start -->
                                <form name="brandForm"
                                      id="brandForm"
                                      @if(empty($brand['id']))
                                          action="{{ url('admin/add-edit-brand') }}"
                                      @else
                                          action="{{ url('admin/add-edit-brand/' . $brand['id']) }}"
                                      @endif
                                      method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="brand_name">Brand Name<span
                                                    style="color: red">&nbsp;*</span></label>
                                            <input type="text" name="brand_name" class="form-control" id="brand_name"
                                                   placeholder="Enter Brand Name"
                                                   @if(!empty($brand['brand_name'])) value="{{ $brand['brand_name'] }}"
                                                   @else value="{{ old('brand_name') }}" @endif >
                                        </div>
                                        <div class="form-group">
                                            <label for="">Brand URL<span style="color: red">&nbsp;*</span></label>
                                            <input type="text" name="url" class="form-control" id="url"
                                                   placeholder="Enter Brand  URL"
                                                   @if(!empty($brand['url'])) value="{{ $brand['url'] }}"
                                                   @else value="{{ old('url') }}" @endif>
                                            @if(!empty($brand['brand_image']))
                                                <div
                                                    style="float: right; margin: 10px 0; padding: 8px; border: 1px solid gray">
                                                    <a target="_blank"
                                                       href="{{ url('front/images/brands/' . $brand['brand_image']) }}"><img
                                                            style="width: 50px"
                                                            src="{{ asset('front/images/brands/' . $brand['brand_image']) }}"></a>&nbsp;
                                                    <a href="javascript:void(0)" record="brand-image"
                                                       recordid="{{ $brand['id'] }}"
                                                       class="confirmDelete"
                                                       title="Delete Brand Image"
                                                       style="color: #3f6ed3;"><!--Delete-->&nbsp;<i
                                                            class="fas fa-trash" style="color: #BD362F"></i></a>
                                                </div>
                                                <input type="hidden" name="current_image"
                                                       value="{{ $brand['brand_image'] }}">
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="brand_image">Brand Image</label>
                                            <input type="file" name="brand_image" class="form-control" id="brand_image">
                                            @if(!empty($brand['brand_logo']))
                                                <div
                                                    style="float: right; margin: 10px 0; padding: 8px; border: 1px solid gray">
                                                    <a target="_blank"
                                                       href="{{ url('front/images/logos/' . $brand['brand_logo']) }}"><img
                                                            style="width: 50px"
                                                            src="{{ asset('front/images/logos/' . $brand['brand_logo']) }}"></a>&nbsp;
                                                    <a href="javascript:void(0)" record="brand-logo"
                                                       recordid="{{ $brand['id'] }}"
                                                       class="confirmDelete"
                                                       title="Delete Brand Logo"
                                                       style="color: #3f6ed3;"><!--Delete-->&nbsp;<i
                                                            class="fas fa-trash" style="color: #BD362F"></i></a>
                                                </div>
                                                <input type="hidden" name="current_logo"
                                                       value="{{ $brand['brand_logo'] }}">
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="brand_logo">Brand Logo</label>
                                            <input type="file" name="brand_logo" class="form-control" id="brand_logo">
                                        </div>
                                        <div class="form-group">
                                            <label for="brand_discount">Brand Discount</label>
                                            <input type="text" name="brand_discount" class="form-control"
                                                   id="brand_discount"
                                                   placeholder="Enter Brand Discount"
                                                   @if(!empty($brand['brand_discount'])) value="{{ $brand['brand_discount'] }}"
                                                   @else value="{{ old('brand_discount') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Brand Description</label>
                                            <textarea name="description" class="form-control" rows="3" id="description"
                                                      placeholder="Enter Brand Description">@if(!empty($brand['description'])){{ $brand['description'] }}@else{{ old('description') }}@endif</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" name="meta_title" class="form-control" id="meta_title"
                                                   placeholder="Enter Meta Title"
                                                   @if(!empty($brand['meta_title'])) value="{{ $brand['meta_title'] }}"
                                                   @else value="{{ old('meta_title') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <input type="text" name="meta_description" class="form-control"
                                                   id="meta_description" placeholder="Enter Meta Description"
                                                   @if(!empty($brand['meta_description'])) value="{{ $brand['meta_description'] }}"
                                                   @else value="{{ old('meta_description') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="meta_keywords">Meta Keywords</label>
                                            <input type="text" name="meta_keywords" class="form-control"
                                                   id="meta_keywords" placeholder="Enter Meta Keywords"
                                                   @if(!empty($brand['meta_keywords'])) value="{{ $brand['meta_keywords'] }}"
                                                   @else value="{{ old('meta_keywords') }}" @endif>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->

                                    <div>
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
                    <div class="card-footer"></div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection
