@extends('layouts.master')

{{-- page title --}}
@section('title','Customer Management | HVL')

@section('vendor-style')

@endsection
@section('content')
    <div class="breadcrumb-holder">
        <div class="container-fluid">
            <ul class="breadcrumb">
                {{--                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>--}}
                <li class="breadcrumb-item active"><a href="{{route('customer.index')}}">Customer Management </a></li>
                <li class="breadcrumb-item ">Create Customer</li>
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
                                <h2 class="h3 display"> Create Customer</h2>
                                <p style="font-size: 12px;"><strong>Note:</strong> Comma is not allowed in any field.</p>

                            </div>
                        </div>

                    </header>

            <form action="{{route('customer.approveUpdate',['id' => $details->id])}}" id="formValidate" method="post">

                {{csrf_field()}}
                <input type="hidden" name="lead_id" value="{{$lead_id}}">
                <div class="row">
                    <div class="form-group col-sm-6 col-md-4" style="margin-bottom: 1px;">
                        <label >Employee <span class="text-danger">*</span></label>
                        <select name="employee_id[]" id="" class="form-control select" multiple required data-error=".errorTxt01" autocomplete="off" autofocus="off" >
                            <option value="" disabled> Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{$employee->id}}">{{$employee->Name}}</option>
                            @endforeach
                        </select>
                        <div class="errorTxt01 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4">
                        <label >Customer Code <span class="text-danger">*</span></label>
                        <input type="text" name="customer_code" class="form-control " onkeypress="return RestrictCommaSemicolon(event);" ondrop="return false;" onpaste="return false;"  placeholder="Enter Customer Code" data-error=".errorTxt1" autocomplete="off" autofocus="off">
                        <div class="errorTxt1 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4">
                        <label >Customer Name <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control " onkeypress="return RestrictCommaSemicolon(event);" ondrop="return false;" onpaste="return false;" value="{{$details->customer_name}}"  placeholder="Enter Customer Name" data-error=".errorTxt2" autocomplete="off" autofocus="off">
                        <div class="errorTxt2 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4" style="margin-bottom: 1px;">
                        <label >Customer Alias Name <span class="text-danger">*</span></label>
                        <input type="text" name="customer_alias" class="form-control " onkeypress="return RestrictCommaSemicolon(event);" ondrop="return false;" onpaste="return false;" value="{{$details->customer_alias}}" placeholder="Enter Customer Alias" data-error=".errorTxt3" autocomplete="off" autofocus="off">
                        <div class="errorTxt3 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4">
                        <label >Billing Address <span class="text-danger">*</span></label>
                        <input type="text" name="billing_address" class="form-control " onkeypress="return RestrictCommaSemicolon(event);" ondrop="return false;" onpaste="return false;" value="{{$details->billing_address}}" placeholder="Enter Billing Address" data-error=".errorTxt4" autocomplete="off" autofocus="off">
                        <div class="errorTxt4 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4">
                        <label >Billing State <span class="text-danger">*</span></label>
                        <select class="form-control" name="billing_state" id="billing_state" autocomplete="off" autofocus="off" data-error=".errorTxt5" >
                            <option value="">Select Billing State</option>
                            @foreach($states as $state)
                                <option value="{{$state->id}}">{{$state->state_name}}</option>
                            @endforeach
                        </select>
                        <div class="errorTxt5 text-danger"></div>
                    </div>
                    <div class=" form-group col-sm-6 col-md-4" >
                        <label class="shift_name">Billing City <span class="text-danger">*</span></label>
                        <select name="billing_city" id="billing_city" class="form-control" autocomplete="off" autofocus="off" data-error=".errorTxt29" >

                        </select>
                        <div class="errorTxt29 text-danger"></div>
                    </div>
                    <div class=" form-group col-sm-6 col-md-4">
                        <label >Billing Pincode</label>
                        <input type="number" name="billing_pincode" class="form-control" placeholder="Enter Billing Pincode" data-error=".errorTxt30" autocomplete="off" autofocus="off">
                        <div class="errorTxt30 text-danger"></div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4">
                        <label >Contact Person <span class="text-danger">*</span></label>
                        <input type="text" name="contact_person" class="form-control " onkeypress="return RestrictCommaSemicolon(event);" ondrop="return false;" onpaste="return false;" value="{{$details->contact_person}}" placeholder="Enter Contact Person" data-error=".errorTxt6" autocomplete="off" autofocus="off">
                        <div class="errorTxt6 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4">
                        <label for="">Contact Person Phone <span class="text-danger">*</span></label>
                        <input type="text" name="contact_person_phone" class="form-control" value="{{$details->contact_person_phone}}" placeholder="Enter Contact Person Phone" data-error=".errorTxt7" autocomplete="off" autofocus="off">
                        <div class="errorTxt7 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4">
                        <label for="">Billing Email </label>
                        <input type="email"  name="billing_mail" class="form-control " onkeypress="return RestrictCommaSemicolon(event);" ondrop="return false;" onpaste="return false;" value="{{$details->billing_email}}" placeholder="Enter Billing Email" data-error=".errorTxt8" autocomplete="off" autofocus="off" onchange="return TimeCalculation();">
                        <div class="errorTxt8 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4" >
                        <label >Billing Mobile <span class="text-danger">*</span></label>
                        <input type="text" name="billing_mobile" class="form-control" value="{{$details->billing_mobile}}" placeholder="Enter Billing Mobile" autocomplete="off" autofocus="off" data-error=".errorTxt9">
                        <div class="errorTxt9 text-danger"></div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4" style="margin-bottom: 1px;">
                        <label >Sales Person <span class="text-danger">*</span></label>
                        <select name="sales_person" id="" class="form-control" autocomplete="off" autofocus="off" data-error=".errorTxt10">
                            <option value=""> Select Employee</option>
                            @foreach($employees as $employee)
                                <option value="{{$employee->id}}" @if($details->sales_person == $employee->id) selected @endif>{{$employee->Name}}</option>
                            @endforeach
                        </select>
                        <div class="errorTxt10 text-danger"></div>
                    </div>

{{--                    <div class="form-group col-sm-6 col-md-4">--}}
{{--                        <label >Status<span class="text-danger">*</span></label>--}}
{{--                        <input type="text" name="status" class="form-control" placeholder="Enter Status" data-error=".errorTxt11" autocomplete="off" autofocus="off">--}}
{{--                        <div class="errorTxt11 text-danger"></div>--}}
{{--                    </div>--}}
                    <div class="form-group col-sm-6 col-md-4">
                        <label for="">Create Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" name="create_date" value="{{$details->create_date}}" placeholder="Enter Create Date" data-error=".errorTxt12" autocomplete="off" autofocus="off">
                        <div class="errorTxt12 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4">
                        <label >Shipping Address <span class="text-danger">*</span></label>
                        <input type="text" name="shipping_adress" class="form-control " onkeypress="return RestrictCommaSemicolon(event);" ondrop="return false;" onpaste="return false;" value="{{$details->shipping_address}}" placeholder="Enter Shipping Address" autocomplete="off" autofocus="off" data-error=".errorTxt13">
                        <div class="errorTxt13 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4" >
                        <label >Shipping State <span class="text-danger">*</span></label>
                        <select name="shipping_state" id="shipping_state" class="form-control" autocomplete="off" autofocus="off" data-error=".errorTxt14" >
                            <option value="">Select Shipping State</option>
                            @foreach($states as $state)
                                <option value="{{$state->id}}">{{$state->state_name}}</option>
                            @endforeach
                        </select>
                        <div class="errorTxt14 text-danger"></div>
                    </div>
                    <div class=" form-group col-sm-6 col-md-4" >
                        <label class="shift_name">Shipping City <span class="text-danger">*</span></label>
                        <select name="shipping_city" id="shipping_city" class="form-control" autocomplete="off" autofocus="off" data-error=".errorTxt27" >

                        </select>
                        <div class="errorTxt27 text-danger"></div>
                    </div>
                    <div class=" form-group col-sm-6 col-md-4">
                        <label class="shift_name">Shipping Pincode</label>
                        <input type="number" name="shipping_pincode" class="form-control" placeholder="Enter Shipping Pincode" data-error=".errorTxt28" autocomplete="off" autofocus="off">
                        <div class="errorTxt28 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4">
                        <label >Credit Limit </label>
                        <input type="number" name="credit_limit" class="form-control" placeholder="Enter Credit Limit" data-error=".errorTxt16" autocomplete="off" autofocus="off">
                        <div class="errorTxt16 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4" style="margin-bottom: 1px;">
                        <label >Gst Registration Type <span class="text-danger">*</span></label>
                        <select name="gst_reges_type" id="reges_type" class="form-control" data-error=".errorTxt17" autocomplete="off" autofocus="off">
                            <option value="">Select Type</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                        <div class="errorTxt17 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4" id="gst_in" style="display: none;">
                        <label >GSTIN <span class="text-danger">*</span></label>
                        <input type="text" name="gstin" class="form-control comma" placeholder="Enter GSTIN Number" data-error=".errorTxt18" autocomplete="off" autofocus="off">
                        <div class="errorTxt18 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4" >
                        <label >Branch <span class="text-danger">*</span></label>
                        <select name="branch" id="" class="form-control" data-error=".errorTxt19" autocomplete="off" autofocus="off">
                            <option value=""> Select Branch</option>
                            @foreach($branchs as $branch)
                                <option value="{{$branch->id}}">{{$branch->Name}}</option>
                            @endforeach
                        </select>
                        <div class="errorTxt19 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4">
                        <label >Payment Mode </label>
                        <select name="payment_mode" class="form-control" id="" data-error=".errorTxt20" autocomplete="off" autofocus="off">
                            <option value="">Select Type</option>
                            <option value="Cash">Cash</option>
                            <option value="Online">Online</option>
                        </select>
                        <div class="errorTxt20 text-danger"></div>
                    </div>
                    <div class=" form-group col-sm-6 col-md-4">
                        <label class="shift_name">Contract Start Date  <span class="text-danger">*</span> </label>
                        <input type="text" name="con_start_date" class="form-control datepicker" placeholder="Select Contract Start Date" data-error=".errorTxt22" autocomplete="off" autofocus="off">
                        <div class="errorTxt22 text-danger"></div>
                    </div>
                    <div class=" form-group col-sm-6 col-md-4">
                        <label class="shift_name">Contract End Date  <span class="text-danger">*</span> </label>
                        <input type="text" name="con_end_date" class="form-control datepicker" placeholder="Select Contract End Date" data-error=".errorTxt23" autocomplete="off" autofocus="off">
                        <div class="errorTxt23 text-danger"></div>
                    </div>
                    <div class=" form-group col-sm-6 col-md-4">
                        <label class="shift_name">Value <span class="text-danger">*</span> </label>
                        <input type="text" name="cust_value" class="form-control" onkeypress="return RestrictCommaSemicolon(event);" ondrop="return false;" onpaste="return false;"  placeholder="Enter Value" data-error=".errorTxt31" autocomplete="off" autofocus="off">
                        <div class="errorTxt31 text-danger"></div>
                    </div>

                    <div class="form-group col-sm-6 col-md-4">
                        <label >Status <span class="text-danger">*</span></label>
                        <select name="is_active" class="form-control" autocomplete="off" autofocus="off" data-error=".errorTxt21">
                            <option value="">Select Status</option>
                            <option value="Active">Active</option>
                            <option value="InActive">InActive</option>
                        </select>
                        <div class="errorTxt21 text-danger"></div>
                    </div>

                </div>

                <div class="row mt-4 pull-right">
                    <div class="col-sm-12 ">
                        <button class="btn btn-primary mr-2" type="submit" name="action">
                            <i class="fa fa-arrow-circle-up"></i>
                            Update
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
            })
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
