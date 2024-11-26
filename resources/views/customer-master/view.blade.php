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
                <li class="breadcrumb-item ">View Customer</li>
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
                                <h2 class="h3 display"> View Customer</h2>
                            </div>
                        </div>
                    </header>
                    <div class="row">
                        <div class=" form-group col-sm-6 col-md-4">
                            <label>Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" id="" multiple  readonly="" class="form-control" data-error=".errorTxt22" autocomplete="off" autofocus="off">
                                @foreach($employees as $employee)
                                    @if(in_array($employee->id , $customer_employees) == true)
                                    <option disabled>{{$employee->Name}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="errorTxt22"></div>
                        </div>

                        <div class=" form-group col-sm-6 col-md-4">
                            <label>Customer Code <span class="text-danger">*</span></label>
                            <input type="text" name="customer_code" disabled class="form-control" placeholder="Enter Customer Name" value="{{$details->customer_code}}" data-error=".errorTxt1" autocomplete="off" autofocus="off">
                            <div class="errorTxt1"></div>
                        </div>

                        <div class=" form-group col-sm-6 col-md-4">
                            <label>Customer Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" disabled class="form-control" placeholder="Enter Customer Name" value="{{$details->customer_name}}" data-error=".errorTxt2" autocomplete="off" autofocus="off">
                            <div class="errorTxt2"></div>
                        </div>

                        <div class=" form-group col-sm-6 col-md-4">
                            <label>Customer Alias Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_alias" disabled class="form-control" placeholder="Enter Customer Alias" value="{{$details->customer_alias}}" data-error=".errorTxt3" autocomplete="off" autofocus="off">
                            <div class="errorTxt3"></div>
                        </div>

                        <div class=" form-group col-sm-6 col-md-4">
                            <label>Billing Address <span class="text-danger">*</span></label>
                            <input type="text" name="billing_address" disabled class="form-control" placeholder="Enter Billing Address" value="{{$details->billing_address}}" data-error=".errorTxt4" autocomplete="off" autofocus="off">
                            <div class="errorTxt4"></div>
                        </div>

                        <div class=" form-group col-sm-6 col-md-4">
                            <label>Billing State <span class="text-danger">*</span></label>
                            <select name="billing_state" class="form-control" disabled id="billing_state" autocomplete="off" autofocus="off" data-error=".errorTxt5">
                                @foreach($states as $state)
                                    <option value="{{$state->id}}" @if($details->billing_state == $state->id) selected @endif>{{$state->state_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class=" form-group col-sm-6 col-md-4">
                            <label >Billing City <span class="text-danger">*</span></label>
                            <select name="billing_city" disabled id="billing_city" class="form-control" autocomplete="off" autofocus="off" data-error=".errorTxt29">
                                @foreach($billing_cities as $city)
                                <option value="{{$city->id}}" @if($details->billing_city == $city->id) selected @endif>{{$city->city_name}}</option>
                                @endforeach
                            </select>
                            <div class="errorTxt29 text-danger"></div>
                        </div>
                        <div class=" form-group col-sm-6 col-md-4">
                            <label class="shift_name">Billing Pincode</label>
                            <input type="number" disabled name="billing_pincode" class="form-control" data-error=".errorTxt30" value="{{$details->billing_pincode}}">
                            <div class="errorTxt30 text-danger"></div>
                        </div>

                        <div class=" form-group col-sm-6 col-md-4">
                            <label for="">Tracking Location <span class="text-danger">*</span> </label>
                            <input type="text" disabled value="{{old('billing_location',$details->billing_location)}}"  name="billing_location" class="form-control" id="billing_location" placeholder="Enter Billing Location" data-error=".errorTxtBillingLocation">
                            <div class="errorTxtBillingLocation text-danger"></div>
                        </div>
                        <div class=" form-group col-sm-6 col-md-4">
                            <label for="">Billing Latitude<span class="text-danger">*</span> </label>
                            <input type="text" disabled value="{{old('billing_latitude',$details->billing_latitude)}}"  readonly name="billing_latitude" class="form-control" id="billing_latitude" placeholder="Latitude" data-error=".errorTxtBillingLatitude">
                            <div class="errorTxtBillingLatitude text-danger"></div>
                        
                        </div>
                        <div class=" form-group col-sm-6 col-md-4">
                            <label for="">Billing Longitude<span class="text-danger">*</span> </label>
                            <input type="text" disabled value="{{old('billing_longitude',$details->billing_longitude)}}" readonly name="billing_longitude" class="form-control" id="billing_longitude" placeholder="Longitude" data-error=".errorTxtBillingLongitude">
                            <div class="errorTxtBillingLongitude text-danger"></div>
                        
                        </div>
                       

                        <div class=" form-group col-sm-6 col-md-4">
                            <label>Contact Person <span class="text-danger">*</span></label>
                            <input type="text" name="contact_person" disabled class="form-control" placeholder="Enter Contact Person" value="{{$details->contact_person}}" data-error=".errorTxt6" autocomplete="off" autofocus="off">
                            <div class="errorTxt6"></div>
                        </div>

                        <div class=" form-group col-sm-6 col-md-4">
                            <label for="">Contact Person Phone <span class="text-danger">*</span></label>
                            <input type="text" name="contact_person_phone" disabled class="form-control" placeholder="Enter Contact Person Phone" value="{{$details->contact_person_phone}}" data-error=".errorTxt7" autocomplete="off" autofocus="off">
                            <div class="errorTxt7"></div>
                        </div>

                        <div class=" form-group col-sm-6 col-md-4">
                            <label for="">Billing Email <span class="text-danger">*</span></label>
                            <input type="email" name="billing_mail" disabled class="form-control" placeholder="Enter Billing Email" value="{{$details->billing_email}}" data-error=".errorTxt8" autocomplete="off" autofocus="off" onchange="return TimeCalculation();">
                            <div class="errorTxt8"></div>
                        </div>

                        <div class=" form-group col-sm-6 col-md-4">
                            <label>Billing Mobile <span class="text-danger">*</span></label>
                            <input type="text" name="billing_mobile" disabled class="form-control" placeholder="Enter Billing Mobile" value="{{$details->billing_mobile}}" autocomplete="off" autofocus="off" data-error=".errorTxt9">
                            <div class="errorTxt9"></div>
                        </div>
                         <div class=" form-group col-sm-6 col-md-4" >
                            <label class="shift_name">Operator</label>
                            <input type="text" name="operator" disabled class="form-control" placeholder="Enter Operator" value="{{$details->operator}}" autocomplete="off" autofocus="off" data-error=".errorOperator">
                            <div class="errorOperator text-danger"></div>
                        </div>
                        <div class=" form-group col-sm-6 col-md-4">
                            <label class="shift_name">Operation Executive</label>
                            <select name="operation_executive" disabled class="form-control" autocomplete="off" autofocus="off"  data-error=".errorOperationExecutive">
                                <option value=""> Select Operation Executive</option>
                                @foreach($employees as $employee)
                                    <option value="{{$employee->Name}}" {{(($employee->Name == $details->operation_executive))?'selected':''}}>{{$employee->Name}}</option>
                                @endforeach
                            </select>
                            <div class="errorOperationExecutive text-danger"></div>
                        </div>
                        <div class=" form-group col-sm-6 col-md-4">
                            <label>Sales Person <span class="text-danger">*</span></label>
                            <select name="sales_person" class="form-control" disabled autocomplete="off" autofocus="off" data-error=".errorTxt10">
                                <option value=""> Select Employee</option>
                                @foreach($employees as $employee)
                                <option value="{{$employee->Name}}" {{$employee->Name == $details->sales_person ? 'selected'  : ''}} > {{$employee->Name}} </option>    
                                @endforeach
                            </select>
                            <div class="errorTxt10"></div>
                        </div>

{{--                        <div class=" form-group col-sm-6 col-md-4">--}}
{{--                            <label>Status<span class="text-danger">*</span></label>--}}
{{--                            <input type="text" name="status" disabled class="form-control" placeholder="Enter Status" value="{{$details->status}}" data-error=".errorTxt11" autocomplete="off" autofocus="off">--}}
{{--                            <div class="errorTxt11"></div>--}}
{{--                        </div>--}}
                        <div class=" form-group col-sm-6 col-md-4">
                            <label for="">Create Date <span class="text-danger">*</span></label>
                            <input type="text" class="datepicker form-control" disabled name="create_date" value="{{$details->create_date}}" placeholder="Enter Create Date" data-error=".errorTxt12" autocomplete="off" autofocus="off">
                            <div class="errorTxt12"></div>
                        </div>

                        <div class=" form-group col-sm-6 col-md-4">
                            <label>Shipping Address <span class="text-danger">*</span></label>
                            <input type="text" name="shipping_adress" disabled class="form-control" value="{{$details->shipping_address}}" placeholder="Enter Shipping Address" autocomplete="off" autofocus="off" data-error=".errorTxt13">
                            <div class="errorTxt13"></div>
                        </div>

                        <div class="form-group col-sm-6 col-md-4">
                            <label>Shipping State <span class="text-danger">*</span></label>
                            <select name="shipping_state" id="shipping_state"  disabled class="form-control" autocomplete="off" autofocus="off" data-error=".errorTxt14">
                                <option>Select State</option>
                                @foreach($states as $state)
                                    <option value="{{$state->id}}" @if($details->shipping_state == $state->id) selected @endif>{{$state->state_name}}</option>
                                @endforeach
                            </select>
                            <div class="errorTxt14"></div>
                        </div>
{{--                        <div class=" form-group col-sm-6 col-md-4">--}}
{{--                            <label>Shipping City <span class="text-danger">*</span></label>--}}
{{--                            <select name="shipping_city" id="shipping_city" disabled class="form-control" autocomplete="off" autofocus="off" data-error=".errorTxt15">--}}
{{--                                @foreach($cities as $city)--}}
{{--                                    <option value="{{$state->id}}" @if($details->shipping_state == $state->id) selected @endif>{{$state->state_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                            <div class="errorTxt15"></div>--}}
{{--                        </div>--}}

                        <div class=" form-group col-sm-6 col-md-4">
                            <label>Credit Limit <span class="text-danger">*</span></label>
                            <input type="text" name="credit_limit" disabled class="form-control" value="{{$details->credit_limit}}" placeholder="Enter Credit Limit" data-error=".errorTxt16" autocomplete="off" autofocus="off">
                            <div class="errorTxt16"></div>
                        </div>

                        <div class=" form-group col-sm-6 col-md-4">
                            <label>Gst Registration Type <span class="text-danger">*</span></label>
                            <select name="gst_reges_type" disabled id="reges_type" class="form-control" data-error=".errorTxt17" autocomplete="off" autofocus="off">
                                <option value="Yes" @if($details->gst_reges_type == 'Yes') selected @endif>Yes</option>
                                <option value="No" @if($details->gst_reges_type == 'No') selected @endif>No</option>
                            </select>
                            <div class="errorTxt17"></div>
                        </div>

                        <div class=" form-group col-sm-6 col-md-4" id="gst_in" @if(empty($details->gstin)) style="display: none;" @endif>
                            <label>GSTIN <span class="text-danger">*</span></label>
                            <input type="text" name="gstin" disabled class="form-control" placeholder="Enter GSTIN Number" value="{{$details->gstin}}" data-error=".errorTxt18" autocomplete="off" autofocus="off">
                            <div class="errorTxt18"></div>
                        </div>

                        <div class=" form-group col-sm-6 col-md-4">
                            <label>Branch <span class="text-danger">*</span></label>
                            <select name="branch" id=""  disabled class="form-control" data-error=".errorTxt19" autocomplete="off" autofocus="off">
                                <option value=""> Select Branch</option>
                                @foreach($branchs as $branch)
                                    <option value="{{$branch->id}}" @if($details->branch_name == $branch->id) selected @endif>{{$branch->Name}}</option>
                                @endforeach
                            </select>
                            <div class="errorTxt19"></div>
                        </div>

                        <div class=" form-group col-sm-6 col-md-4">
                            <label>Payment Mode <span class="text-danger">*</span></label>
                            <select name="payment_mode" disabled class="form-control" id="" data-error=".errorTxt20" autocomplete="off" autofocus="off">
                                <option value="Cash" {{($details->payment_mode == 'Cash')?'selected':'' }}>Cash</option>
                                <option value="Online" {{($details->payment_mode == 'Online')?'selected':''}}>Online</option>
                            </select>
                            <div class="errorTxt20"></div>
                        </div>

                        <div class=" form-group col-sm-6 col-md-4">
                            <label class="shift_name">Contract Start Date  <span class="text-danger">*</span> </label>
                            <input type="text" name="con_start_date" class="form-control datepicker" value="{{$details->con_start_date}}" disabled>
                            <div class="errorTxt22 text-danger"></div>
                        </div>
                        <div class=" form-group col-sm-6 col-md-4">
                            <label class="shift_name">Contract End Date  <span class="text-danger">*</span> </label>
                            <input type="text" name="con_end_date" class="form-control datepicker" value="{{$details->con_end_date}}" disabled>
                            <div class="errorTxt23 text-danger"></div>
                        </div>
                        <div class=" form-group col-sm-6 col-md-4">
                            <label>Is Active <span class="text-danger">*</span></label>
                            <select name="is_active"  disabled class="form-control" autocomplete="off" autofocus="off" data-error=".errorTxt21">
                                <option value="0" @if($details->gst_reges_type == '0') selected @endif>Active</option>
                                <option value="1" @if($details->gst_reges_type == '1') selected @endif>InActive</option>
                            </select>
                            <div class="errorTxt21"></div>
                        </div>

                    </div>
                    @can('Access Customer Contract')
                        <div class="col-sm-12 mt-4">
                        <label>Contract </label>
                    <div class="row">

                            @if(!empty($contracts))
                                @foreach($contracts as $contract)
                                    <div class="col-sm-6 col-md-2 center-align">
                                        @can('Delete Customer Contract')
                                         <a href="" class="button pull-right" data-id="{{$contract->id}}"><span class="fa fa-close fa-lg "></span></a>
                                        @endcan
                                        @if(strtolower($contract->type) == 'pdf' )
                                            <a href="{{asset($contract->path."/".$contract->contract)}}" target="_blank">
                                                <img height="100" width="90" src="{{asset('public/uploads/pdf-icon.png')}}"><br>
                                                {{$contract->contract}}
                                            </a>
                                        @endif
                                        @if(strtolower($contract->type) == 'doc' || strtolower($contract->type) == 'docx')
                                            <a href="{{asset($contract->path."/".$contract->contract)}}" target="_blank">
                                                <img height="110" width="120" src="{{asset('public/uploads/word-logo.png')}}"><br>
                                                {{$contract->contract}}
                                            </a>
                                        @endif
                                        @if(in_array(strtolower($contract->type), ['png','jpg','jpeg']))
                                            <a href="{{asset($contract->path."/".$contract->contract)}}" target="_blank">
                                                <img height="120" width="125" src="{{asset($contract->path."/".$contract->contract)}}"><br>
                                                {{$contract->contract}}
                                            </a>
                                        @endif
                                        <br>

                                    </div>
                                @endforeach
                            @endif
                        </div>
                            <hr style="border: 1px solid grey;">
{{--                            <div class="col-sm-6 ">--}}
                            @can('Edit Customer Contract')
                                <form method="post" action="{{route('customer.edit-contract')}}" enctype="multipart/form-data">
                                    {{--                                                                    <div class="row">--}}
                                    <input type="hidden" name="customer_id" value="{{$details->id}}">
                                    <div class="form-group">
                                        <label><strong>Upload Contract </strong></label>
                                        <input type="file" name="contract[]" id="audit_file" required multiple class="form-control-file" accept=".jpg, .jpeg, .png,.doc,.docx,application/pdf">
                                        <p class="text-danger">Max File Size:<strong> 3MB</strong><br>Supported Format: <strong>.jpg, .jpeg, .png, .pdf, .doc, .docx</strong></p>
                                    </div>
                                    {{--                                                                    </div>--}}
                                    <input type="submit" class="btn btn-success rounded" value="Upload">
                                </form>
                            @endcan
{{--                            </div>--}}
                    </div>
                @endcan

                    <div class="row mt-4 pull-right">
                        <div class="col-sm-12 ">
                            <button type="reset" class="btn btn-secondary  mb-1">
                                <i class="fa fa-arrow-circle-left"></i>
                                <a href="{{url()->previous()}}" class="text-white">Cancel</a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
            @endsection

            {{-- vendor script --}}
            @section('vendor-script')
                <script src="{{asset('js/ajax/jquery.min.js')}}"></script>
                <script src="{{asset('js/ajax/angular.min.js')}}"></script>
                <script src="{{asset('js/materialize.js')}}"></script>
                <script src="{{asset('js/ajax/jquery.validate.min.js')}}"></script>
                <script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
                <script src="{{asset('js/scripts/form-select2.js')}}"></script>

            @endsection


            {{-- page script --}}
            @section('page-script')
                <script src="{{asset('js/hvl/customermaster/create.js')}}"></script>
                <script>
                    $(document).ready(function () {
                        // $('.select').multiselect({
                        //     includeSelectAllOption: false,
                        //     maxHeight: 450,
                        //     buttonWidth: '400px',
                        //     nonSelectedText: 'Employees'
                        // });

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
                    var uploadaudit = document.getElementById("audit_file");

                    uploadaudit.onchange = function() {
                        if(this.files[0].size > 3145728){
                            alert("File is too big!");
                            this.value = "";
                        };
                    };
                    $(document).ready(function () {
                        $(document).on('click', '.button', function (e) {
                            e.preventDefault();
                            var id = $(this).data("id");

                            swal({
                                    title: "Are you sure? ",
                                    text: "You will not be able to recover this record!",
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonClass: "btn-danger",
                                    confirmButtonText: "Yes, delete it!",
                                    closeOnConfirm: false
                                },
                                function (isConfirm) {
                                    if (isConfirm) {
                                        $.ajax({
                                            url: "{{route('customer.contract_delete')}}",
                                            type: "get",
                                            data: {
                                                "id": id
                                            },
                                            success: function (result) {
                                                swal({
                                                    title: "Record has been deleted!",
                                                    type: "success",
                                                }, function () {
                                                    location.reload();
                                                });
                                            }
                                        });
                                    }
                                });
                        });
                    });
                </script>

@endsection
