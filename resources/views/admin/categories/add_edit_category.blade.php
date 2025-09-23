@extends('admin.layout.layout')
@section('content')
    {{--@php dd($category); @endphp--}}

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
                                <form name="categoryForm"
                                      id="categoryForm"
                                      @if(empty($category['id']))
                                          action="{{ url('admin/add-edit-category') }}"
                                      @else
                                          action="{{ url('admin/add-edit-category/' . $category['id']) }}"
                                      @endif
                                      method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="category_name">Category Name<span style="color: red">&nbsp;*</span></label>
                                            <input type="text" name="category_name" class="form-control" id="category_name"
                                                   placeholder="Enter Category Name"
                                                   @if(!empty($category['category_name'])) value="{{ $category['category_name'] }}" @else value="{{ old('category_name') }}" @endif >
                                        </div>
                                        <div class="form-group">
                                            <label for="category_level">Category Level (Parent Category)<span style="color: red">&nbsp;*</span></label>
                                            <select name="parent_id" class="form-control">
                                                <option value="" disabled>Select</option>
                                                <option value="0" @if($category['parent_id'] == 0) selected @endif>Main Category</option>

                                                @foreach($getCategories as $main_category)
                                                    <option value="{{ $main_category['id'] }}" @if(isset($category['parent_id']) && $category['parent_id'] == $main_category['id']) selected @endif>{{ $main_category['category_name'] }}</option>
                                                    @if(!empty($main_category['sub_categories']))
                                                        @foreach($main_category['sub_categories'] as $sub_category)
                                                            <option value="{{ $sub_category['id'] }}" @if(isset($category['parent_id']) && $category['parent_id'] == $sub_category['id']) selected @endif>&nbsp;&nbsp;&raquo;&nbsp;{{ $sub_category['category_name'] }}</option>
                                                            @if(!empty($sub_category['sub_categories']))
                                                                @foreach($sub_category['sub_categories'] as $subcategory)
                                                                    <option value="{{ $subcategory['id'] }}" {{--@if(isset($category['parent_id']) && $category['parent_id'] == $subcategory['id']) selected @endif--}}>&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&nbsp;{{ $subcategory['category_name'] }}</option>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach

                                            </select>
                                      </div>
                                        <div class="form-group">
                                            <label for="">Category URL<span style="color: red">&nbsp;*</span></label>
                                            <input type="text" name="url" class="form-control" id="url"
                                                   placeholder="Enter Category  URL"
                                                   @if(!empty($category['url'])) value="{{ $category['url'] }}" @else value="{{ old('url') }}" @endif>
                                            @if(!empty($category['category_image']))
                                                <div style="float: right; margin: 10px 0; padding: 8px; border: 1px solid gray">
                                                    <a target="_blank" href="{{ url('front/img/categories/' . $category['category_image']) }}"><img style="width: 50px" src="{{ asset('front/img/categories/' . $category['category_image']) }}"></a>&nbsp;
                                                    <a href="javascript:void(0)" record="category-image"
                                                       recordid="{{ $category['id'] }}"
                                                       class="confirmDelete"
                                                       title="Delete Category Image"
                                                       style="color: #3f6ed3;"><!--Delete-->&nbsp;<i class="fas fa-trash" style="color: #BD362F"></i></a>
                                                </div>
                                                <input type="hidden" name="current_image" value="{{ $category['category_image'] }}">
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="category_image">Category Image</label>
                                            <input type="file" name="category_image" class="form-control" id="category_image">
                                        </div>
                                        <div class="form-group">
                                            <label for="category_discount">Category Discount</label>
                                            <input type="text" name="category_discount" class="form-control" id="category_discount"
                                                   placeholder="Enter Category Discount"
                                                   @if(!empty($category['category_discount'])) value="{{ $category['category_discount'] }}" @else value="{{ old('category_discount') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Category Description</label>
                                            <textarea name="description" class="form-control" rows="3" id="description"
                                                      placeholder="Enter Category Description">@if(!empty($category['description'])) {{ $category['description'] }} @else {{ old('description') }} @endif</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" name="meta_title" class="form-control" id="meta_title"
                                                   placeholder="Enter Meta Title"
                                                   @if(!empty($category['meta_title'])) value="{{ $category['meta_title'] }}" @else value="{{ old('meta_title') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <input type="text" name="meta_description" class="form-control"
                                                   id="meta_description" placeholder="Enter Meta Description"
                                                   @if(!empty($category['meta_description'])) value="{{ $category['meta_description'] }}" @else value="{{ old('meta_description') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="meta_keywords">Meta Keywords</label>
                                            <input type="text" name="meta_keywords" class="form-control"
                                                   id="meta_keywords" placeholder="Enter Meta Keywords"
                                                   @if(!empty($category['meta_keywords'])) value="{{ $category['meta_keywords'] }}" @else value="{{ old('meta_keywords') }}" @endif>
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
