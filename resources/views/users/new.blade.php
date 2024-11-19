@extends('layouts.master')
@section('title')
@lang('translation.wizard')
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1')
Forms
@endslot
@slot('title')
User Management
@endslot
@endcomponent
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Create User</h4>
            </div><!-- end card header -->
            <div class="card-body">
                <form action="{{ route('users.store') }}" class="form-steps" autocomplete="off" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div id="custom-progress-bar" class="progress-nav mb-4">
                        <div class="progress" style="height: 1px;">
                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <ul class="nav nav-pills progress-bar-tab custom-nav" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill active" data-progressbar="custom-progress-bar"
                                        id="pills-gen-info-tab" data-bs-toggle="pill" data-bs-target="#pills-gen-info"
                                        type="button" role="tab" aria-controls="pills-gen-info"
                                        aria-selected="true">1</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill" data-progressbar="custom-progress-bar"
                                        id="pills-info-desc-tab" data-bs-toggle="pill" data-bs-target="#pills-info-desc"
                                        type="button" role="tab" aria-controls="pills-info-desc"
                                        aria-selected="false">2</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill" data-progressbar="custom-progress-bar"
                                        id="pills-success-tab" data-bs-toggle="pill" data-bs-target="#pills-success"
                                        type="button" role="tab" aria-controls="pills-success"
                                        aria-selected="false">3</button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pills-gen-info" role="tabpanel"
                             aria-labelledby="pills-gen-info-tab">
                            <div>
                                <div class="mb-4">
                                    <div>
                                        <h5 class="mb-1">General Information</h5>
                                        <p class="text-muted">Fill all Information as below</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="name">Full Name</label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   placeholder="Enter user name" required>
                                            <div class="invalid-feedback">Please enter a user name</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                   placeholder="Enter email" required>
                                            <div class="invalid-feedback">Please enter an email address</div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-lg-6">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                               placeholder="Enter Password" required>
                                        <div class="invalid-feedback">Please enter a password</div>
                                    </div>
                                    <div class="col-lg-6">
                                        <h6 class="fw-semibold">Select Roles</h6>
                                        <select class="js-example-basic-multiple" name="roles[]" multiple="multiple">
                                            @foreach($roles as $role)
                                            @if($role->name !== 'Admin')
                                            <option value="{{ $role->name }}"
                                                    @if(isset($user)) @if($user->hasRole($role->name)) selected @endif @endif>
                                                {{ $role->name }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3 mt-4">
                                <button type="button" class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                        data-nexttab="pills-info-desc-tab"><i
                                        class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Go to more
                                    info</button>
                            </div>
                        </div>
                        <!-- end tab pane -->

                        <div class="tab-pane fade" id="pills-info-desc" role="tabpanel"
                             aria-labelledby="pills-info-desc-tab">
                            <div>
                                <div class="text-center">
                                    <div class="profile-user position-relative d-inline-block mx-auto mb-2">
                                        <img src="{{ URL::asset('build/images/users/user-dummy-img.jpg') }}"
                                             class="rounded-circle avatar-lg img-thumbnail user-profile-image"
                                             alt="user-profile-image">
                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                            <input id="profile-img-file-input" type="file"
                                                   class="profile-img-file-input" accept="image/png, image/jpeg">
                                            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle bg-light text-body">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <h5 class="fs-14">Add Image</h5>

                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3 mt-4">
                                <button type="button" class="btn btn-link text-decoration-none btn-label previestab"
                                        data-previous="pills-gen-info-tab"><i
                                        class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Back to
                                    General</button>
                                <button type="submit" class="btn btn-success btn-label right ms-auto nexttab nexttab"
                                        data-nexttab="pills-success-tab"><i
                                        class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Submit</button>
                            </div>
                        </div>
                        <!-- end tab pane -->

                        <div class="tab-pane fade" id="pills-success" role="tabpanel"
                             aria-labelledby="pills-success-tab">
                            <div>
                                <div class="text-center">

                                    <div class="mb-4">
                                        <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop"
                                                   colors="primary:#0ab39c,secondary:#405189"
                                                   style="width:120px;height:120px"></lord-icon>
                                    </div>
                                    <h5>Well Done !</h5>
                                    <p class="text-muted">You have Successfully Signed Up</p>
                                </div>
                            </div>
                        </div>
                        <!-- end tab pane -->
                    </div>
                    <!-- end tab content -->
                </form>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->

    <!-- end col -->
</div><!-- end row -->


<!-- end row -->
@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="{{ URL::asset('build/js/pages/form-wizard.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>
@endsection
