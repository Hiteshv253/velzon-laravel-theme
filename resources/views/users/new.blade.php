@extends('layouts.master')
@section('title')
@lang('translation.wizard')
@endsection
@section('content')
@include('components.breadcrumb')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"
    type="text/css" />
@endsection

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Create User</h4>
            </div><!-- end card header -->
            <div class="card-body">
                <form action="{{ route('users.store') }}" class="form-steps" autocomplete="off" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div>
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
                            <div class="col-lg-4">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Enter Password" required>
                                <div class="invalid-feedback">Please enter a password</div>
                            </div>
                            <div class="col-lg-4">
                                <h6 class="fw-semibold">Select Roles</h6>
                                <select class="js-example-basic-multiple" name="roles[]" multiple="multiple">
                                    @foreach($roles as $role)
                                        @if($role->name !== 'Admin')
                                            <option value="{{ $role->name }}" @if(isset($user)) @if($user->hasRole($role->name))
                                            selected @endif @endif>
                                                {{ $role->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                    <select class="form-select mb-3" aria-label="Default select example" name="is_active" id="is_active">
                                        <option selected>Select </option>
                                        <option value="0">Active</option>
                                        <option value="1">Block</option>
                                    </select>
                                </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mt-4">
                        <button onClick="backbtn()" type="button"
                            class="btn btn-link text-decoration-none btn-label previestab"
                            data-previous="pills-gen-info-tab"><i
                                class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Back to
                            General</button>
                        <button type="submit" class="btn btn-success btn-label right ms-auto nexttab nexttab"
                            data-nexttab="pills-success-tab"><i
                                class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Submit</button>
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


<script src="{{ URL::asset('build/js/pages/form-wizard.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>
@endsection
