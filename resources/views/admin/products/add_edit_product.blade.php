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
                                @if(Session::has('success_message'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success:</strong> {{ Session::get('success_message') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
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
                                            <label for="category_id">Select Category<span
                                                    style="color: red">&nbsp;*</span></label>
                                            <select name="category_id" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($getCategories as $main_category)
                                                    <option
                                                        @if(!empty(@old('category_id')) && $main_category['id'] == @old('category_id'))
                                                            selected
                                                        @elseif(!empty($product['category_id']) && $product['category_id'] == $main_category['id'])
                                                            selected
                                                        @endif
                                                        value="{{ $main_category['id'] }}">{{ $main_category['category_name'] }}</option>
                                                    @if(!empty($main_category['sub_categories']))
                                                        @foreach($main_category['sub_categories'] as $sub_category)
                                                            <option
                                                                @if(!empty(@old('category_id')) && $sub_category['id'] == @old('category_id'))
                                                                    selected
                                                                @elseif(!empty($product['category_id']) && $product['category_id'] == $sub_category['id'])
                                                                    selected
                                                                @endif value="{{ $sub_category['id'] }}">&nbsp;&nbsp;&raquo;&nbsp;{{ $sub_category['category_name'] }}</option>
                                                            @if(!empty($sub_category['sub_categories']))
                                                                @foreach($sub_category['sub_categories'] as $subcategory)
                                                                    <option
                                                                        @if(!empty(@old('category_id')) && $subcategory['id'] == @old('category_id'))
                                                                            selected
                                                                        @elseif(!empty($product['category_id']) && $product['category_id'] == $subcategory['id'])
                                                                            selected
                                                                        @endif value="{{ $subcategory['id'] }}">&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;&nbsp;{{ $subcategory['category_name'] }}</option>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_name">Product Name<span
                                                    style="color: red">&nbsp;*</span></label>
                                            <input type="text" name="product_name" class="form-control"
                                                   id="product_name"
                                                   placeholder="Enter Product Name"
                                                   value="<?php echo $product['product_name'] ?? old('product_name') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="product_code">Product Code<span
                                                    style="color: red">&nbsp;*</span></label>
                                            <input type="text" name="product_code" class="form-control"
                                                   id="product_code"
                                                   placeholder="Enter Product Code"
                                                   value="<?php echo $product['product_code'] ?? old('product_code') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="product_color">Product Color<span
                                                    style="color: red">&nbsp;*</span></label>
                                            <input type="text" name="product_color" class="form-control"
                                                   id="product_color"
                                                   placeholder="Enter Product Color"
                                                   value="<?php echo $product['product_color'] ?? old('product_color') ?>">
                                        </div>
                                        @php $familyColors = \App\Models\Color::colors(); @endphp
                                        <div class="form-group">
                                            <label for="family_color">Family Color<span
                                                    style="color: red">&nbsp;*</span></label>
                                            <select name="family_color" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($familyColors as $color)
                                                    <option value="{{ $color['color_name'] }}"
                                                            @if(!empty(@old('family_color')) && @old('family_color') == $color['color_name'])
                                                                selected
                                                            @elseif(!empty($product['family_color']) && $product['family_color'] == $color['color_name'])
                                                                selected
                                                        @endif>
                                                        {{ $color['color_name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="group_code">Group Code</label>
                                            <input type="text" name="group_code" class="form-control" id="group_code"
                                                   placeholder="Enter Group Code"
                                                   value="<?php echo $product['group_code'] ?? old('group_code') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="product_price">Product Price<span
                                                    style="color: red">&nbsp;*</span></label>
                                            <input type="text" name="product_price" class="form-control"
                                                   id="product_price"
                                                   placeholder="Enter Product Price"
                                                   value="<?php echo $product['product_price'] ?? old('product_price') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_discount">Product Discount (%)</label>
                                            <input type="text" name="product_discount" class="form-control"
                                                   id="product_discount"
                                                   placeholder="Enter Product Discount (%)"
                                                   value="<?php echo $product['product_discount'] ?? old('product_discount') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="product_weight">Product Weight</label>
                                            <input type="text" name="product_weight" class="form-control"
                                                   id="product_weight"
                                                   placeholder="Enter Product Weight"
                                                   value="<?php echo $product['product_weight'] ?? old('product_weight') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="product_images">Product Image's (Recommend Size: 1040 x 1200)</label>
                                            <input type="file" name="product_images[]" class="form-control"
                                                   id="product_images" multiple>
                                            <table style="margin: 10px; padding: 5px">
                                                <tr>
                                            @foreach($product['images'] as $image)
                                                <td style="background-color: #f9f9f9; margin: 20px;">
                                                <a href="{{ url('front/images/products/large/' . $image['image']) }}" target="_blank">
                                                    <img width="60px" src="{{ asset('front/images/products/small/' . $image['image']) }}"></a>&nbsp;
                                                    <input type="hidden" name="image[]" value="{{ $image['image'] }}">
                                                    <input style="width: 30px;" type="text" name="image_sort[]" value="{{ $image['image_sort'] }}">
                                                    <a href="javascript:void(0)" record="product-images"
                                                       recordid="{{ $image['id'] }}"
                                                       class="confirmDelete"
                                                       title="Delete Product Image"
                                                       style="color: #3f6ed3;"><i class="fas fa-trash"></i></a>

                                                </td>
                                            @endforeach
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_video">Product Video (Recommend Size: Less then 2MB)</label>
                                            <input type="file" name="product_video" class="form-control"
                                                   id="product_video">
                                            <div style="float: right; margin-top: 10px;">
                                            @if(!empty($product['product_video']))
                                                <a target="_blank" href="{{ url('front/video/products/' . $product['product_video']) }}">View Video</a>&nbsp;&nbsp;|&nbsp;
                                                <a href="javascript:void(0)" record="product-video"
                                                   recordid="{{ $product['id'] }}"
                                                   class="confirmDelete"
                                                   title="Delete Product Video"
                                                   style="color: #BD362F;"><i class="fas fa-trash"></i></a>

                                            @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Added Product Attributes</label>
                                            <table style="background-color: #52585e; width: 50%" cellpadding="5">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Size</th>
                                                    <th>SKU</th>
                                                    <th>Price</th>
                                                    <th>Stock</th>
                                                    <th>Actions</th>
                                                </tr>
                                                @foreach($product['attributes'] as $attribute)
                                                    <input type="hidden" name="attributeId[]" value="{{ $attribute['id'] }}" />
                                                    <tr>
                                                        <td>{{ $attribute['id'] }}</td>
                                                        <td>{{ $attribute['size'] }}</td>
                                                        <td>{{ $attribute['sku'] }}</td>
                                                        <td><input style="width: 100px;" type="number" name="price[]" value="{{ $attribute['price'] }}"></td>
                                                        <td><input style="width: 100px;" type="number" name="stock[]" value="{{ $attribute['stock'] }}"></td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                        <label>Add Attributes</label>
                                        <div class="form-group border border-info p-2">
                                            <div class="field_wrapper">
                                                <div>
                                                    <input type="text"  name="size[]" id="size" placeholder="Size" style="width: 120px" />
                                                    <input type="text"  name="sku[]" id="sku" placeholder="SKU" style="width: 120px" />
                                                    <input type="text"  name="price[]" id="price" placeholder="Price" style="width: 120px" />
                                                    <input type="text"  name="stock[]" id="stock" placeholder="Stock" style="width: 120px" />
                                                    <a href="javascript:void(0);" class="add_button" title="Add field">Add</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="fabric">Fabric</label>
                                            <select name="fabric" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($productsFilters['fabricArray'] as $fabric)
                                                    <option value="{{ $fabric }}"
                                                    @if(!empty(@old('fabric')) && @old('fabric') == $fabric)
                                                        selected
                                                    @elseif(!empty($product['fabric']) && $product['fabric'] == $fabric)
                                                        selected
                                                    @endif>
                                                    {{ $fabric }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="sleeve">Sleeve</label>
                                            <select name="sleeve" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($productsFilters['sleeveArray'] as $sleeve)
                                                    <option value="{{ $sleeve }}"
                                                    @if(!empty(@old('sleeve')) && @old('sleeve') == $sleeve)
                                                        selected
                                                    @elseif(!empty($product['sleeve']) && $product['sleeve'] == $sleeve)
                                                        selected
                                                    @endif>
                                                    {{ $sleeve }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="pattern">Pattern</label>
                                            <select name="pattern" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($productsFilters['patternArray'] as $pattern)
                                                    <option value="{{ $pattern }}"
                                                    @if(!empty(@old('pattern')) && @old('pattern') == $pattern)
                                                        selected
                                                    @elseif(!empty($product['pattern']) && $product['pattern'] == $pattern)
                                                        selected
                                                    @endif>
                                                    {{ $pattern }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="fit">Fit</label>
                                            <select name="fit" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($productsFilters['fitArray'] as $fit)
                                                    <option value="{{ $fit }}"
                                                    @if(!empty(@old('fit')) && @old('fit') == $fit)
                                                        selected
                                                    @elseif(!empty($product['fit']) && $product['fit'] == $fit)
                                                        selected
                                                    @endif>
                                                    {{ $fit }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="occasion">Occasion</label>
                                            <select name="occasion" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($productsFilters['occasionArray'] as $occasion)
                                                    <option value="{{ $occasion }}"
                                                    @if(!empty(@old('occasion')) && @old('occasion') == $occasion)
                                                        selected
                                                    @elseif(!empty($product['occasion']) && $product['occasion'] == $occasion)
                                                        selected
                                                    @endif>
                                                    {{ $occasion }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description" class="form-control" rows="3" id="description"
                                                      placeholder="Enter Product Description"><?php echo $product['description'] ?? old('description') ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="wash_care">Wash Care</label>
                                            <textarea name="wash_care" class="form-control" rows="3" id="wash_care"
                                                      placeholder="Enter Product Wash Care"><?php echo $product['wash_care'] ?? old('wash_care') ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="search_keywords">Search Keywords</label>
                                            <textarea name="search_keywords" class="form-control" rows="3"
                                                      id="search_keywords"
                                                      placeholder="Enter Product Search Keywords"><?php echo $product['search_keywords'] ?? old('search_keywords') ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input type="text" name="meta_title" class="form-control" id="meta_title"
                                                   placeholder="Enter Meta Title"
                                                   value="<?php echo $product['meta_title'] ?? old('meta_title') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="meta_description">Meta Description</label>
                                            <input type="text" name="meta_description" class="form-control"
                                                   id="meta_description" placeholder="Enter Meta Description"
                                                   value="<?php echo $product['meta_description'] ?? old('meta_description') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="meta_keywords">Meta Keywords</label>
                                            <input type="text" name="meta_keywords" class="form-control"
                                                   id="meta_keywords" placeholder="Enter Meta Keywords"
                                                   value="<?php echo $product['meta_keywords'] ?? old('meta_keywords') ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="is_featured">Featured Item</label>
                                            <input type="checkbox" name="is_featured" value="Yes" @if($product['is_featured'] == 'Yes') checked @endif>
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
