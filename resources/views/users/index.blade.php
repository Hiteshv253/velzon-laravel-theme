@extends('layouts.master')
@section('title')
@lang('translation.customers')
@endsection
@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1')
Ecommerce
@endslot
@slot('title')
Userss
@endslot
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card" id="customerList">
            <div class="card-header border-bottom-dashed">

                <div class="row g-4 align-items-center">
                    <div class="col-sm">
                        <div>
                            <h5 class="card-title mb-0">Users List</h5>
                        </div>
                    </div>
                    
                    <div class="col-sm-auto">
                        <div class="d-flex gap-1 flex-wrap">
                            <a type="button" class="btn btn-success add-btn" href="{{ route('users.create') }}"
                                id="create-btn">
                                <i class="ri-add-line align-bottom me-1"></i> Create User
                            </a>
                            <button type="button" class="btn btn-info">
                                <i class="ri-file-download-line align-bottom me-1"></i> Import
                            </button>
                            <div class="dropdown">
                                <a href="#" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-expanded="false">
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
@if (session('success'))
                    <div class="card-header">
                
<div class="alert alert-success alert-dismissible fade show material-shadow" role="alert">
    <strong> {!! \Session::get('success') !!} </strong> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
                    </div>
@endif
            <div class="card-body border-bottom-dashed border-bottom">
                <form>
                    <div class="row g-3">
                        <div class="col-xl-8">
                            <div class="search-box">
                                <input type="text" class="form-control search"
                                    placeholder="Search for user, email, phone, status or something...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-xl-4">
                            <div class="row g-3">

                                <!--end col-->
                                <div class="col-sm-6">
                                    <div>
                                        <select class="form-control" data-plugin="choices" data-choices
                                            data-choices-search-false name="choices-single-default" id="idStatus">
                                            <option value="">Status</option>
                                            <option value="all" selected>All</option>
                                            <option value="Active">Active</option>
                                            <option value="Block">Block</option>
                                        </select>
                                    </div>
                                </div>
                                <!--end col-->

                                <div class="col-sm-6">
                                    <div>
                                        <button type="button" class="btn btn-primary w-100" onclick="SearchData();"> <i
                                                class="ri-equalizer-fill me-2 align-bottom"></i>Filters</button>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                        </div>
                    </div>
                    <!--end row-->
                </form>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active All py-3" data-bs-toggle="tab" id="All" href="#home1" role="tab"
                            aria-selected="true">
                            <i class="ri-store-2-fill me-1 align-bottom"></i> All Users (<?php echo count($users);?>)
                        </a>
                    </li>
                </ul>
                <div>
                    <div class="table-responsive table-card mb-1">
                        <table class="table align-middle" id="customerTable">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll"
                                                value="option">
                                        </div>
                                    </th>

                                    <th class="sort" data-sort="id">Id</th>
                                    <th class="sort" data-sort="user_name">Users</th>
                                    <th class="sort" data-sort="email">Email</th>
                                    <th class="sort" data-sort="date">Joining Date</th>
                                    <th class="sort" data-sort="status">Status</th>
                                    <th class="sort" data-sort="action">Action</th>
                                </tr>
                            </thead>
                            <tbody class="list form-check-all">
                                @foreach($users as $key => $user)
                                    <tr>
                                        <th scope="row">
                                            <div class="form-check">
                                                <input class="form-check-input selected_id" type="checkbox"
                                                    name="selected_row" id="selected_id" data-id="{{ $user->id }}"
                                                    value="{{ $user->id }}">
                                            </div>
                                        </th>
                                        <td class="id"><a href="javascript:void(0);"
                                                class="fw-medium link-primary">{{ $user->id }}</a></td>
                                        <td class="user_name">{{ $user->name }}</td>
                                        <td class="email">{{ $user->email }}</td>

                                        <td class="date">{{ $user->created_at }}</td>
                                        <td class="status">
                                            <?php  echo ($user->is_active == '0') ? '<span class="badge bg-success-subtle text-success text-uppercase">Active</span>' : '<span class="badge bg-danger-subtle text-danger text-uppercase">Block</span>'; ?>
                                        </td>
                                        <td>
                                            <ul class="list-inline hstack gap-2 mb-0">
                                                  <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                    <a href="{{ route('users.edit', $user->id) }}" class="text-primary d-inline-block">
                                                        <i class="ri-pencil-fill fs-16"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                    data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                                    <a class="text-danger d-inline-block remove-item-btn"
                                                        onClick="delete_single();" data-bs-toggle="modal"
                                                        href="#deleteRecordModal">
                                                        <i class="ri-delete-bin-5-fill fs-16"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="noresult" style="display: none">
                            <div class="text-center">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                    colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                </lord-icon>
                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                <p class="text-muted mb-0">We've searched more than 150+ customer We
                                    did not find any
                                    customer for you search.</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <div class="pagination-wrap hstack gap-2">
                            <a class="page-item pagination-prev disabled" href="#">
                                Previous
                            </a>
                            <ul class="pagination listjs-pagination mb-0"></ul>
                            <a class="page-item pagination-next" href="#">
                                Next
                            </a>
                        </div>
                    </div>
                </div>


                <!-- Modal -->
                <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="btn-close deleteRecord-close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mt-2 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                        colors="primary:#f7b84b,secondary:#f06548"
                                        style="width:100px;height:100px"></lord-icon>
                                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                        <h4>Are you sure ?</h4>
                                        <p class="text-muted mx-4 mb-0">Are you sure you want to
                                            remove this record ?</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                    <button type="button" class="btn w-sm btn-light"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn w-sm btn-danger " id="delete-record">Yes,
                                        Delete It!</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end modal -->
            </div>
        </div>

    </div>
    <!--end col-->
</div>
<!--end row-->
@endsection
@section('script')
<script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/list.pagination.js/list.pagination.min.js') }}"></script>

<!--ecommerce-customer init js -->

<script src="{{ URL::asset('build/js/project/user/user.init.js') }}"></script>
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script>
    // Delete Multiple Records
    function delete_single() {
        removeBtns = document.getElementsByClassName("remove-item-btn"),
            Array.from(removeBtns).forEach(function (btn) {
                btn.addEventListener("click", function (e) {
                    e.target.closest("tr").children[1].innerText;
                    itemId = e.target.closest("tr").children[1].innerText;
                    $("#delete-record").click(function () {
                        $.ajax({
                            url: "users/delete/" + itemId,
                            type: 'POST',
                            data: {
                                "_token": token,
                                ids: itemId,
                            }
                        });
                        $('.btn-close').click()
                        Toastify({ text: "Your data has been deleted.", duration: 3000 }).showToast();
                        window.location.reload(1);
                    });
                });
            });

    }
    function deleteMultiple() {

        var checkbox_array = [];
        //            var token = $("meta[name='csrf-token']").attr("content");
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
                    }).done(function () {
                        document.getElementById("checkAll").checked = false;
                        setTimeout(function () {
                            $("#overlay").fadeOut(300);
                        }, 500);
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
