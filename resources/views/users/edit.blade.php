@extends('app.layout')

{{-- page title --}}
@section('title','User Management | HVL')

@section('vendor-style')

@endsection
@section('content')
    <div class="breadcrumb-holder">
        <div class="container-fluid">
            <ul class="breadcrumb">
                {{--                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>--}}
                <li class="breadcrumb-item active"><a href="{{url('/users')}}">User Management </a></li>
                <li class="breadcrumb-item ">Update User</li>
            </ul>
        </div>
    </div>
    <section>
        <div class="container-fluid">
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
                <div class="card-body p-4">
                    <header>
                        <div class="row">
                            <div class="col-md-7">
                                <h2 class="h3 display"> Update User</h2>
                            </div>
                        </div>
                    </header>
                    <form id="loginform" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="form-group col-sm-6 col-md-4">
                                <label for="first_name">Full Name <span class="text-danger">*</span></label>
                                <input id="first_name" disabled name="name" type="text" class="validate form-control"
                                        @if(isset($user)) value="{{ $user->name }}" @endif required>
                            </div>
                            <div class="form-group col-sm-6 col-md-4">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input id="email"disabled name="email" type="email" class="validate form-control"
                                        @if(isset($user)) value="{{ $user->email }}" @endif required>
                            </div>
                            <div class="form-group col-sm-6 col-md-4">
                                <!--<i toggle="#password" class="material-icons prefix pt-2 toggle-password">lock_outline</i>-->
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                <input id="password" type="password" name="password" class="validate form-control" minlength="8" data-error=".errorTxt1" value="">
                                <div class="errorTxt1"></div>
                            </div>
                            <div class="form-group col-sm-6 col-md-4">
                                <label>Roles <span class="text-danger">*</span></label>
                                <select class="select " multiple="multiple" name="roles[]">

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
                        </div>

                        <div class="row mt-4 pull-right">
                            <div class="col-sm-12 ">
                                <button class="btn btn-primary mr-2" type="submit" name="action">
                                    <i class="fa fa-arrow-circle-up"></i>
                                    Update
                                </button>
                                <button type="reset" class="btn btn-secondary  mb-1">
                                    <i class="fa fa-arrow-circle-left"></i>
                                    <a href="{{url('users/')}}" class="text-white">Cancel</a>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>




@endsection

@section('page-script')
<script src="{{asset('js/user/_from.js')}}"></script>
<script>
    $(document).ready(function () {
        $('.select').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            nonSelectedText: 'Select Role',
            maxHeight: 450
        });

        $(".toggle-password").click(function () {

        //$(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    $.validator.addMethod("regex", function (value, element, regexp) {
        var regexp = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/;
        var re = new RegExp(regexp);
        return this.optional(element) || re.test(value);
    }, "Wrong password. Please check your password (8-12 Chars, 1 Capital letter, 1 small letter, 1 number, 1 special char)");

    /*login validation start*/
    $("#loginform").validate({
        rules: {
            "email": {
                required: true,
                email: true,
            },
            "password": {
//                required: true,
                regex: true,
            }

        },
        messages: {
            email: {
                required: "Please Enter Email ID",
            },
            password: {
//                required: "Please Enter Password",
            },
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
            var placement = $(element).data('error');
            if (placement) {
                $(placement).append(error)
            } else {
                error.insertAfter(element);
            }
        }
    });

});
</script>
@endsection



