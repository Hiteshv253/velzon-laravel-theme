@extends('layouts.master')
@section('title')
    @lang('translation.wizard')
@endsection
@section('content')
    @include('components.breadcrumb')
    @section('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    @endsection

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Update User</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" class="form-steps" autocomplete="off" method="POST" enctype="multipart/form-data">

                        @csrf
                        <div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="name">Full Name</label>
                                        <input id="name" disabled name="name" type="text" class="validate form-control"
                                        @if(isset($user)) value="{{ $user->name }}" @endif required>
                                        <div class="invalid-feedback">Please enter a user name</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="email">Email</label>
                                        <input id="email" disabled name="email" type="email" class="validate form-control"
                                        @if(isset($user)) value="{{ $user->email }}" @endif required>
                                        <div class="invalid-feedback">Please enter an email address</div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-lg-4">
                                    <label class="form-label" for="password">Password</label>
                                    <input id="password" type="password" name="password" class="validate form-control" minlength="8" data-error=".errorTxt1" value="">

                                    <div class="invalid-feedback">Please enter a password</div>
                                </div>
                                
                                <div class="col-lg-4">
                                    <h6 class="fw-semibold">Select Roles</h6>
                                    <select class="js-example-basic-multiple" name="roles[]" id="roles" multiple="multiple">
                                        @foreach($roles as $role)
                                        @if($role->name !== 'Admin')
                                                <option value="{{ $role->name }}"
                                                {{ (isset($user))?($user->hasRole($role->name))?'selected':'':'' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <h6 class="fw-semibold">Select Action</h6>
                                    <select class="form-select mb-3" aria-label="Default select example" name="is_active" id="is_active">
                                <option value="0"  @if($user->is_active == '0') selected @endif >Active</option>
                                <option value="1"  @if($user->is_active == '1') selected @endif >Block</option>
                                        
                                    </select>
                                </div>
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
