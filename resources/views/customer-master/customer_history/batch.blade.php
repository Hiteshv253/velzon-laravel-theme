@extends('layouts.master')

{{-- page title --}}
@section('title','Customer batch Management | HVL')

@section('vendor-style')

@endsection
@section('content')
<div class="breadcrumb-holder">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <!--<li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>-->
            <li class="breadcrumb-item active"><a href="{{route('customer.index')}}">Customer Management </a></li>
            <li class="breadcrumb-item "> Customer's Batch Update</li>
        </ul>
    </div>
</div>
<section>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-4">
                <header>
                    <div class="row">
                        <div class="col-md-7">
                            <h2 class="h3 display">Customer Batch Update</h2>
                        </div>
                    </div>

                </header>
                <form action="{{route('customer.services.batchsupdate',['batch' => $batchnumber])}}" id="formValidate" method="post">

                    {{csrf_field()}}
                    <input type="hidden" name="batch_number" value="{{$batchnumber}}">
                    <input type="hidden" name="customer_id" value="{{$customer_id}}">
                    <div class="row">
                        <div class=" form-group col-sm-4 col-md-4">
                            <label class="shift_name">Customer Code<span class="text-danger">*</span> </label>
                            <input type="text" id="update_amount" name="update_amount" class="form-control" readonly="" value="{{$customer_name->customer_code}}">
                        </div>
                        <div class=" form-group col-sm-4 col-md-4">
                            <label class="shift_name">Subject Name<span class="text-danger">*</span> </label>
                            <input type="text" id="update_amount" name="update_amount" class="form-control" readonly="" value="<?php echo str_replace('-', '/', $batchname) ?>">
                        </div>
                        <div class=" form-group col-sm-4 col-md-4">
                            <label class="shift_name">Frequency<span class="text-danger">*</span> </label>
                            <input type="text" id="update_amount" name="update_amount" class="form-control" readonly="" value="{{$frequency}}">
                        </div>
                        <div class=" form-group col-sm-4 col-md-4">
                            <label class="shift_name">Total Activities<span class="text-danger">*</span> </label>
                            <input type="text" id="update_amount" name="update_amount" class="form-control" readonly="" value="{{$total_activities}}">
                        </div>
                        <div class=" form-group col-sm-4 col-md-4">
                            <label class="shift_name">Per Service Amount<span class="text-danger">*</span> </label>
                            <input type="number" id="update_amount" name="update_amount" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mt-4 pull-right">
                        <div class="col-sm-12 ">
                            <button class="btn btn-primary mr-2" type="submit" name="action">
                                <i class="fa fa-arrow-circle-up"></i>
                                Update Amount
                            </button>
                            <button type="reset" class="btn btn-secondary  mb-1">
                                <i class="fa fa-arrow-circle-left"></i>
                                <a href="{{url()->previous()}}" class="text-white">Cancel</a>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
@endsection

{{-- page scripts --}}
@section('page-script')
<script src="{{asset('js/hvl/customermaster/create.js')}}"></script>

<script>
$(document).ready(function () {
    $('.select').multiselect({
        includeSelectAllOption: true,
        enableFiltering: true,
        maxHeight: 450
    });

    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });

    $('#reges_type').change(function () {
        if ($('#reges_type').val() == 'Yes') {
            $('#gst_in').show();
        } else {
            $('#gst_in').hide();
        }
    });
});

function RestrictCommaSemicolon(e) {
    var theEvent = e || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
    var regex = /[^,;]+$/;
    if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault) {
            theEvent.preventDefault();
        }
    }
}
</script>

@endsection
