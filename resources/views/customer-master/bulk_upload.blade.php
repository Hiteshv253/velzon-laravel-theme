@extends('layouts.master')
@section('title','Dashboard | Asset Management')
@section('vendor-style')
@endsection
@section('content')
<section>
    <div class="container-fluid">
        <header>
            <div class="row">
                <div class="col-md-4">
                    <h2 class="h3 display">Customer Bulk Upload</h2>
                </div>
            </div>
        </header>
        <!-- Page Length Options -->
        <div class="card">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show center-block" role="alert">
                <strong>{!! Session::get('success') !!} </strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            @if($errors->any())
                @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show center-block" role="alert">
                    <strong>{{$error}}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endforeach
            @endif
           
            
            <div class="card-body">
                <form class="form-horizontal well" action="{{route('admin.customermaster_bulkupload.save')}}"
                    method="post" name="upload_excel" enctype="multipart/form-data">
                    @csrf
                        <div class="form-group">
                        <label for="exampleFormControlFile1">Excel File</label>
                        (<a href="{{asset('example/bulkupload/Customer - Bulk Upload.xlsx')}}">Sample File</a>)
                        <input type="file" required name="excel_file" class="form-control-file">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Start</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
