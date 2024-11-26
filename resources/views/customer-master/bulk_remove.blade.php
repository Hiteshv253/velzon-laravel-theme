@extends('layouts.master')

{{-- page title --}}
@section('title','Remove Customer | HVL')

@section('vendor-style')

@endsection
@section('content')

    <div class="col-sm-12 col-md-6">
        <h4>Upload Customer File To Remove Data</h4>
        <form action="{{ route('bulk-remove-customer') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="upload_file" class="form-control">
            <br>
            <button class="btn btn-success">Upload File</button>
        </form>
    </div>
@endsection
