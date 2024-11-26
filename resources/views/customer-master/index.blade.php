@extends('layouts.master')
@section('title','Customer Management | HVL')
@section('vendor-style')
@endsection
@php 
$isOperators =false;
$isCustomerAdmin = false;
@endphp
@role('Operators')
@php
$isOperators = true;
@endphp
@endrole
@role('customers_admin')
@php
$isCustomerAdmin = true;
@endphp
@endrole
@section('content')




    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="orderList">
                <div class="card-header border-0">
                    <div class="row align-items-center gy-3">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">User History</h5>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                <a type="button" class="btn btn-success add-btn"  href="{{ route('users.create') }}" id="create-btn">
                                    <i class="ri-add-line align-bottom me-1"></i> Create User
                                </a>
                                <button type="button" class="btn btn-info">
                                    <i class="ri-file-download-line align-bottom me-1"></i> Import
                                </button>
                                <div class="dropdown">
                                    <a href="#" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Export
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">PDF</a>
                                        <a class="dropdown-item" href="#">Excel</a>
                                    </div>
                                </div>

                                <button class="btn btn-soft-danger" id="remove-actions1" onClick="deleteMultiple()">
                                    <i class="ri-delete-bin-2-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border border-dashed border-end-0 border-start-0" >
                    <form>
                        <div class="row g-3">
                            <div class="col-xxl-5 col-sm-6">
                                <div class="search-box">
                                    <input type="text" class="form-control search"
                                       placeholder="Search for User Name, Email ID or something...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>

                        </div>
                    <!--end row-->
                    </form>
                </div>
                <div class="card-body pt-0">
                    <div>
                        <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active All py-3" data-bs-toggle="tab" id="All" href="#home1"
                               role="tab" aria-selected="true">
                                    <i class="ri-store-2-fill me-1 align-bottom"></i> All Customer (<?php echo  count($customerDetails);?>)
                                </a>
                            </li>
                        </ul>

                        <div class="table-responsive table-card mb-1">
                            <table class="table table-nowrap align-middle" id="orderTable">
                                <thead class="text-muted table-light">
                                    <tr class="text-uppercase">
                                        <th scope="col" style="width: 25px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAll"
                                                   value="option">
                                            </div>
                                        </th>
                                        <th width="2%">ID</th>
                     @if(!$isCustomerAdmin)
                                            <th width="10%">Action</th>
                                        @endif
                                        <th width="5%">Activity</th>
                                        <th>Audit</th>
                    @if(!$isCustomerAdmin) <th width="2%">Customer Code</th> @endif
                                        <th width="10%">Customer Name</th>
                                        <th width="5%">Customer Alias</th>
                                        <th width="2%">Billing Address</th>
                                        <th width="2%">Billing State</th>
                                        <th width="2%">Contact Person</th>
                                        <th width="2%">Contact Phone</th>
                                        <th width="5%">Billing Mail</th>
                                        <th width="5%">Billing Phone</th>
                    @if(!$isCustomerAdmin)<th width="2%">Sales Person</th>@endif
                                        <th width="2%">Creation Date</th>
                                        <th width="2%">Shipping Address</th>
                                        <th width="2%">Shipping State</th>
                                        <th width="2%">Credit Limit</th>
                                        <th width="2%">GSTIN</th>
                                        <th width="2%">Payment Mode</th>
                                        <th width="2%">Branch</th>
                                        <th width="2%">Contract Start Date</th>
                                        <th width="2%">Contract End Date</th>
                                    @if(!$isOperators) @if(!$isCustomerAdmin)<th width="2%">Value</th>@endif @endif
                    @if(!$isCustomerAdmin) <th width="2%">Reference</th> @endif
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="list form-check-all">
                                @foreach($customerDetails as $key => $detaile)
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check"> 
                                                <input class="form-check-input" type="checkbox" name="selected_row" data-id="{{ $detaile->id }}"
                                                   value="{{ $detaile->id }}">
                                            </div>
                                        </th>
                                        <td><center>{{$loop->iteration}}</center></td>
                        @if(!$isCustomerAdmin)
                                            <td width="10%">
                                                @can('Edit Customer')
                                                    <a href="{{ route('customer.edit', $detaile->id) }}" class="p-2"> <span class="fa fa-edit"></span> </a>
                                                @endcan
                                                <a href="{{ route('customer.services.history', $detaile->id) }}" class="p-2"> <span class="fa fa-history"></span> </a>
                            @can('Delete Customer')
                                                    <a href="" class="button" data-id="{{$detaile->id}}"><span class="fa fa-trash"></span></a>
                                                @endcan
                            @if($detaile->contract == 0)
                                                    @can('Access Customer Contract')
                                                        <a class="p-2" data-toggle="modal" data-id="{{$detaile->id}}" data-target="#modal{{$detaile->id}}"><span class="fa fa-paperclip fa-lg"></span></a>
                                                        <div id="modal{{$detaile->id}}" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade text-left">
                                                            <div role="document" class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4>Upload Contract</h4>
                                                                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                                                    </div>
                                                                    <div class="modal-body p-4">
                                                                        <form method="post" action="{{route('customer.contract')}}" enctype="multipart/form-data">
                                                    <!--<div class="row">-->
                                                                            <input type="hidden" name="customer_id" value="{{$detaile->id}}">
                                                                            <div class="form-group">
                                                                                <label>Contract File</label>
                                                                                <input type="file" name="contract[]" id="audit_file" required multiple class="form-control-file" accept=".jpg, .jpeg, .png,.doc,.docx,application/pdf">
                                                                                <p class="text-danger">Max File Size:<strong> 3MB</strong><br>Supported Format: <strong>.jpg, .jpeg, .png, .pdf, .doc, .docx</strong></p>
                                                                            </div>
                                                    <!--</div>-->
                                                                            <input type="submit" class="btn btn-success rounded" value="Upload">
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endcan
                                                @endif
                            @can('Read Customer')
                                                    <a href="{{ route('customer.view', $detaile->id) }}" class="p-2" data-position="top" data-tooltip="Edit"><span class="fa fa-eye"></span></a>
                                                @endcan
                                            </td>
                                        @endif                    
                                        <td><a href="{{ route('customer.view-activity', $detaile->id) }}" class="p-1 btn btn-primary"> View </a></td>
                                        <td><a href="{{ route('admin.customer.audit_list', $detaile->id) }}" class="p-1 btn btn-primary"> Audit </a></td>
                        @if(!$isCustomerAdmin)<td>{{$detaile->customer_code}}</td>@endif
                                        <td>{{$detaile->customer_name}}</td>
                                        <td><details><summary>{{substr($detaile->customer_alias, 0, 10)}}..</summary><p>{{$detaile->customer_alias}}</p></details></td>
                                        <td><details><summary>{{substr($detaile->billing_address, 0, 10)}}..</summary><p>{{$detaile->billing_address}}</p></details></td>
                                        <td>{{$detaile->billing_state_name}}</td>
                                        <td>{{$detaile->contact_person}}</td>
                                        <td>{{$detaile->contact_person_phone}}</td>
                                        <td>{{$detaile->billing_email}}</td>
                                        <td>{{$detaile->billing_mobile}}</td>
                        @if(!$isCustomerAdmin)<td>{{$detaile->sales_person}}</td>@endif
                                        <td>{{$detaile->create_date}}</td>
                                        <td><details><summary>{{substr($detaile->shipping_address, 0, 10)}}..</summary><p>{{$detaile->shipping_address}}</p></details></td>
                                        <td>{{$detaile->shipping_state_name}}</td>
                                        <td>{{$detaile->credit_limit}}</td>
                                        <td>{{$detaile->gstin}}</td>
                                        <td>{{$detaile->payment_mode}}</td>
                                        <td>{{$detaile->customer_branch_name}}</td>
                                        <td>{{$detaile->con_start_date}}</td>
                                        <td>{{$detaile->con_end_date}}</td>
                                    @if(!$isOperators) @if(!$isCustomerAdmin)<td>{{$detaile->cust_value}}</td>@endif @endif
                        @if(!$isCustomerAdmin)<td>{{$detaile->reference}}</td>@endif
                                    <td>@if($detaile->is_active==0)<span class="">Active</span>@elseif($detaile->is_active==1)<span class="">Inactive</span>@else{{$detaile->status}}@endif </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>







    @include('customer-master._popup')

<!--end row-->
@endsection
@section('script')
 
<script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/list.pagination.js/list.pagination.min.js') }}"></script>

<!--ecommerce-customer init js -->
<script src="{{ URL::asset('build/js/pages/ecommerce-order.init.js') }}"></script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script>
// Delete Multiple Records
function deleteMultiple() {

            var checkbox_array = [];
            var token = $("meta[name='csrf-token']").attr("content");
            $.each($("input[name='selected_row']:checked"), function () {
                checkbox_array.push($(this).data("id"));
            });
            // console.log(checkbox_array);
            if (typeof checkbox_array !== 'undefined' && checkbox_array.length > 0) {

                 Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover these record!",
                    icon: 'warning',
                    showCancelButton: true,
                    dangerMode: true,
                    
                    showCloseButton: true
                     
                }).then(function (result) {
                     if (result.value) {
                      $("#overlay").fadeIn(300);　
                        $.ajax({
                                    url: '{{route('users.multi_delete')}}',
                                type: 'POST',
                                data: {
                                    "_token": token,
                                    ids: checkbox_array,
                                }
                                }).done(function() {
                                     document.getElementById("checkAll").checked = false;
                                     setTimeout(function(){
                                        $("#overlay").fadeOut(300);
                                     },500);   
                                   Toastify({ text: "Your data has been deleted.", duration: 3000 }).showToast();
                                    window.location.reload(1);
                                });
                                            
                    } else {
                        document.getElementById("checkAll").checked = false;
//                        document.getElementsByClass("form-check-input").checked = false;
                            Toastify({ text: "Your Record is safe", duration: 30000 }).showToast();
                    }
                });
            } else {
                 Swal.fire({
                    title: "0 Row selected!",
                    text: "Select any record from the list",
                    icon: 'warning',
                });
            }

       }
</script>
@endsection
