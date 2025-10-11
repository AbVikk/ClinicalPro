<!doctype html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">

<title>:: Oreo Hospital :: Add Doctors</title>
<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">

<!-- Dropzone CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/dropzone/dropzone.css') }}">

<!-- Bootstrap Select CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/css/bootstrap-select.css') }}">

<!-- Custom Css -->
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
</head>
<body class="theme-cyan">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="../assets/images/logo.svg" width="48" height="48" alt="Oreo"></div>
        <p>Please wait...</p>
    </div>
</div>
<!-- Overlay For Sidebars -->
@include('admin.sidemenu')

<section class="content">
   <div class="block-header">
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12">
            <h2>Add Drug Category
            <small>Add a new drug category</small>
            </h2>
        </div>            
        <div class="col-lg-7 col-md-7 col-sm-12 text-right">
            <ul class="breadcrumb float-md-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="zmdi zmdi-home"></i> Admin</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.dashboard') }}">Pharmacy</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pharmacy.categories.index') }}">Categories</a></li>
                <li class="breadcrumb-item active">Add</li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Add</strong> Drug Category</h2>
                </div>
                <div class="body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.pharmacy.categories.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Category Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-raised btn-primary">Save Category</button>
                        <a href="{{ route('admin.pharmacy.categories.index') }}" class="btn btn-raised btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="block-header">
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12">
            <h2 style="color: #2CA8FF;">Drug Categories
            <small style="color: #2CA8FF;">Manage drug categories</small>
            </h2>
        </div>            
       
    </div>
</div>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Drug</strong> Categories</h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="{{ route('admin.pharmacy.categories.create') }}" class="btn btn-raised btn-primary btn-sm">
                                <i class="zmdi zmdi-plus"></i> Add Category
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->description ?? 'N/A' }}</td>
                                    <td>{{ $category->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.pharmacy.categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                            <i class="zmdi zmdi-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.pharmacy.categories.destroy', $category) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">
                                                <i class="zmdi zmdi-delete"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No categories found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</section>
    <!-- Jquery Core Js -->
    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script> <!-- Bootstrap JS and jQuery v3.2.1 -->
    <!-- slimscroll, waves Scripts Plugin Js -->
    <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
    <!-- Dropzone Plugin Js -->
    <script src="{{ asset('assets/plugins/dropzone/dropzone.js') }}"></script>
    <!-- Custom Js -->
    <script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
</body>
</html>
