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
                            <div class="col-6">
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
                                <form name="subadminForm"
                                      id="subadminForm"
                                      @if(empty($subAdminData['id']))
                                          action="{{ url('admin/add-edit-subadmin') }}"
                                      @else
                                          action="{{ url('admin/add-edit-subadmin/' . $subAdminData['id']) }}"
                                      @endif
                                      method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Name<span style="color: red">&nbsp;*</span></label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                   placeholder="Enter Name"
                                                   @if(!empty($subAdminData['name'])) value="{{ $subAdminData['name'] }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="mobile">Mobile<span style="color: red">&nbsp;*</span></label>
                                            <input type="text" name="mobile" class="form-control" id="mobile"
                                                   placeholder="Enter Mobile"
                                                   @if(!empty($subAdminData['mobile'])) value="{{ $subAdminData['mobile'] }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control" id="email"
                                                   placeholder="Enter Email"
                                                   @if(!empty($subAdminData['email'])) value="{{ $subAdminData['email'] }}" @endif
                                                   @if($subAdminData['id'] != "") disabled @else required @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="text" name="password" class="form-control"
                                                   id="password" placeholder="Enter Password"
                                                   @if(!empty($subAdminData['password'])) value="{{ $subAdminData['password'] }}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Photo</label>
                                            <input type="file" class="form-control" id="image" name="image">
                                            @if(!empty($subAdminData['image']))
                                                <a target="_blank" href="{{ url('admin/img/photos/' . $subAdminData['image']) }}">View Photo</a>
                                                <input type="hidden" name="current_image" value="{{ $subAdminData['image'] }}">
                                            @endif
                                        </div>
                                    </div>
                                    <!-- /.card-body -->

                                    <div class="form-group">
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
