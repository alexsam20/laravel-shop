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
                                <form name="productForm"
                                      id="productForm"
                                      @if(empty($product['id']))
                                          action="{{ url('admin/add-edit-product') }}"
                                      @else
                                          action="{{ url('admin/add-edit-product/' . $product['id']) }}"
                                      @endif
                                      method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="category_id">Select Category<span style="color: red">&nbsp;*</span></label>
                                            <select name="category_id" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($getCategories as $main_category)
                                                    <option @if(!empty(@old('category_id')) && $main_category['id'] == @old('category_id')) selected @endif value="{{ $main_category['id'] }}">{{ $main_category['category_name'] }}</option>
                                                    @if(!empty($main_category['sub_categories']))
                                                        @foreach($main_category['sub_categories'] as $sub_category)
                                                            <option @if(!empty(@old('category_id')) && $sub_category['id'] == @old('category_id')) selected @endif value="{{ $sub_category['id'] }}">&nbsp;&nbsp;&raquo;&nbsp;{{ $sub_category['category_name'] }}</option>
                                                            @if(!empty($sub_category['sub_categories']))
                                                                @foreach($sub_category['sub_categories'] as $subcategory)
                                                                    <option @if(!empty(@old('category_id')) && $subcategory['id'] == @old('category_id')) selected @endif value="{{ $subcategory['id'] }}">&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&nbsp;{{ $subcategory['category_name'] }}</option>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_name">Product Name<span style="color: red">&nbsp;*</span></label>
                                            <input type="text" name="product_name" class="form-control" id="product_name"
                                                   placeholder="Enter Product Name"
                                                   @if(!empty($product['product_name'])) value="{{ $product['product_name'] }}" @else value="{{ old('product_name') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_code">Product Code<span style="color: red">&nbsp;*</span></label>
                                            <input type="text" name="product_code" class="form-control" id="product_code"
                                                   placeholder="Enter Product Code"
                                                   @if(!empty($product['product_code'])) value="{{ $product['product_code'] }}" @else value="{{ old('product_code') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_color">Product Color<span style="color: red">&nbsp;*</span></label>
                                            <input type="text" name="product_color" class="form-control" id="product_color"
                                                   placeholder="Enter Product Color"
                                                   @if(!empty($product['product_color'])) value="{{ $product['product_color'] }}" @else value="{{ old('product_color') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="family_color">Family Color<span style="color: red">&nbsp;*</span></label>
                                            <select name="family_color" class="form-control">
                                                <option value="">Select</option>
                                                <option value="Red" @if(!empty(@old('family_color')) && @old('family_color') == 'Red') selected @endif>Red</option>
                                                <option value="Green" @if(!empty(@old('family_color')) && @old('family_color') == 'Green') selected @endif>Green</option>
                                                <option value="Yellow" @if(!empty(@old('family_color')) && @old('family_color') == 'Yellow') selected @endif>Yellow</option>
                                                <option value="Black" @if(!empty(@old('family_color')) && @old('family_color') == 'Black') selected @endif>Black</option>
                                                <option value="White" @if(!empty(@old('family_color')) && @old('family_color') == 'White') selected @endif>White</option>
                                                <option value="Blue" @if(!empty(@old('family_color')) && @old('family_color') == 'Blue') selected @endif>Blue</option>
                                                <option value="Orange" @if(!empty(@old('family_color')) && @old('family_color') == 'Orange') selected @endif>Orange</option>
                                                <option value="Gray" @if(!empty(@old('family_color')) && @old('family_color') == 'Gray') selected @endif>Gray</option>
                                                <option value="Silver" @if(!empty(@old('family_color')) && @old('family_color') == 'Silver') selected @endif>Silver</option>
                                                <option value="Golden" @if(!empty(@old('family_color')) && @old('family_color') == 'Golden') selected @endif>Golden</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="group_code">Group Code</label>
                                            <input type="text" name="group_code" class="form-control" id="group_code"
                                                   placeholder="Enter Group Code"
                                                   @if(!empty($product['group_code'])) value="{{ $product['group_code'] }}" @else value="{{ old('group_code') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_price">Product Price<span style="color: red">&nbsp;*</span></label>
                                            <input type="text" name="product_price" class="form-control" id="product_price"
                                                   placeholder="Enter Product Price"
                                                   @if(!empty($product['product_price'])) value="{{ $product['product_price'] }}" @else value="{{ old('product_price') }}" @endif required>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_discount">Product Discount (%)</label>
                                            <input type="text" name="product_discount" class="form-control" id="product_discount"
                                                   placeholder="Enter Product Discount (%)"
                                                   @if(!empty($product['product_discount'])) value="{{ $product['product_discount'] }}" @else value="{{ old('product_discount') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_weight">Product Weight</label>
                                            <input type="text" name="product_weight" class="form-control" id="product_weight"
                                                   placeholder="Enter Product Weight"
                                                   @if(!empty($product['product_weight'])) value="{{ $product['product_weight'] }}" @else value="{{ old('product_weight') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_video">Product Video</label>
                                            <input type="file" name="product_video" class="form-control" id="product_video">
                                        </div>
                                        <div class="form-group">
                                            <label for="fabric">Fabric</label>
                                            <select name="fabric" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($productsFilters['fabricArray'] as $fabric)
                                                    <option value="{{ $fabric }}">{{ $fabric }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="sleeve">Sleeve</label>
                                            <select name="sleeve" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($productsFilters['sleeveArray'] as $sleeve)
                                                    <option value="{{ $sleeve }}">{{ $sleeve }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="pattern">Pattern</label>
                                            <select name="pattern" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($productsFilters['patternArray'] as $pattern)
                                                    <option value="{{ $pattern }}">{{ $pattern }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="fit">Fit</label>
                                            <select name="fit" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($productsFilters['fitArray'] as $fit)
                                                    <option value="{{ $fit }}">{{ $fit }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="occasion">Occasion</label>
                                            <select name="occasion" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($productsFilters['occasionArray'] as $occasion)
                                                    <option value="{{ $occasion }}">{{ $occasion }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description" class="form-control" rows="3" id="description"
                                                      placeholder="Enter Product Description">@if(!empty($roduct['description'])) {{ $roduct['description'] }} @else {{ old('description') }} @endif</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="wash_care">Wash Care</label>
                                            <textarea name="wash_care" class="form-control" rows="3" id="wash_care"
                                                      placeholder="Enter Product Wash Care">@if(!empty($roduct['wash_care'])) {{ $roduct['wash_care'] }} @else {{ old('wash_care') }} @endif</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="search_keywords">Search Keywords</label>
                                            <textarea name="search_keywords" class="form-control" rows="3" id="search_keywords"
                                                      placeholder="Enter Product Search Keywords">@if(!empty($roduct['search_keywords'])) {{ $roduct['search_keywords'] }} @else {{ old('search_keywords') }} @endif</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" name="meta_title" class="form-control" id="meta_title"
                                                   placeholder="Enter Meta Title"
                                                   @if(!empty($roduct['meta_title'])) value="{{ $roduct['meta_title'] }}" @else value="{{ old('meta_title') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <input type="text" name="meta_description" class="form-control"
                                                   id="meta_description" placeholder="Enter Meta Description"
                                                   @if(!empty($product['meta_description'])) value="{{ $product['meta_description'] }}" @else value="{{ old('meta_description') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="meta_keywords">Meta Keywords</label>
                                            <input type="text" name="meta_keywords" class="form-control"
                                                   id="meta_keywords" placeholder="Enter Meta Keywords"
                                                   @if(!empty($roduct['meta_keywords'])) value="{{ $roduct['meta_keywords'] }}" @else value="{{ old('meta_keywords') }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="is_featured">Featured Item</label>
                                            <input type="checkbox" name="is_featured" value="Yes">
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
