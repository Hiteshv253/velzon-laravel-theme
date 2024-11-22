@extends('layouts.master')
@section('title')
    @lang('translation.datatables')
@endsection
@section('css')
<!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
      type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
    @slot('li_1')
Tables
@endslot
    @slot('title')
User Managment


@endslot
@endcomponent


    @if (session('success'))
        <div class="alert alert-danger" role="alert">
            {!! \Session::get('success') !!}
        </div>
    @endif

    <div class="row mb-3 pb-1">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                <div class="flex-grow-1">
                     <p class="text-muted mb-0">
                            

                    </p>
                </div>
                <div class="mt-3 mt-lg-0">
                    <form action="javascript:void(0);">
                        <div class="row g-3 mb-0 align-items-center">

                        <!--end col-->
                            <div class="col-sm-auto">
                                <a type="button" class="btn btn-soft-success" href="{{url('/users/create')}}">
                                    <i class="ri-add-circle-line align-middle me-1"></i>
                                Add User
                                </a>
                            </div>
                        <!--end col-->
                            <div class="col-sm-auto">
                                <button type="button" class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                            <div class="col-auto">

                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Dropdown link
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>

                            </div>

                        <!--end col-->
                        </div>
                    <!--end row-->
                    </form>
                </div>
            </div><!-- end card header -->
        </div>
    <!--end col-->
    </div>



    <div class="row">
        <div class="col-lg-12">
            <div class="card">


            <!--//-->
                <div class="card-body">

                    <table class="table table-borderless table-nowrap table-centered align-middle mb-0 data-table" id="datatable">
                        <thead class="table-light text-muted">
                            <tr>

                                <th scope="col">Action</th>
                                <th><input type="checkbox" class="checkAll" onclick="CheckAll(this);"></th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
            <!--//-->


            </div>
        </div>
    </div>

<!--end row-->



<!--end row-->
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

<script type="text/javascript">

var checkbox = $('.multiselect tbody tr td input');
var selectAll = $('.multiselect .select-all');
checkbox.on('click', function () {
// console.log($(this).attr("checked"));
$(this).parent().parent().parent().toggleClass('selected');
});
checkbox.on('click', function () {
// console.log($(this).attr("checked"));
if ($(this).attr("checked")) {
    $(this).attr('checked', false);
} else {
    $(this).attr('checked', true);
}
});
selectAll.on('click', function () {
$(this).toggleClass('clicked');
if (selectAll.hasClass('clicked')) {
    $('.multiselect tbody tr').addClass('selected');
} else {
    $('.multiselect tbody tr').removeClass('selected');
}

if ($('.multiselect tbody tr').hasClass('selected')) {
    checkbox.prop('checked', true);
} else {
    checkbox.prop('checked', false);
}
});

var table = $('.data-table').DataTable({
processing: true,
serverSide: true,
responsive: true,

lengthMenu: [
    [10, 25, 50, -1],
    [10, 25, 50, 'All']
],
layout: {
    topStart: {
        buttons: [
            {
                extend: 'collection',
                text: 'Export',
                buttons: ['copy', 'pdf', 'excel', 'print']
            },
            'colvis',
            'pageLength',
        ]
    }
},

ajax: "{{ route('users.index__') }}",
columns: [

    {data: 'action', name: 'action', orderable: false, searchable: false},
    {data: 'id', name: 'id'},
    {data: 'name', name: 'name'},
    {data: 'email', name: 'email'},

]
});
</script>

@endsection
