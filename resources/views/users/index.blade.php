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
Datatables
@endslot
@endcomponent


@if (session('success'))
<div class="alert alert-danger" role="alert">
    {!! \Session::get('success') !!}
</div>
@endif



<!--end row-->


<!--end row-->


<!--end row-->


<!--end row-->



<!--end row-->


<!--end row-->


<!--end row-->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Buttons Datatables</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped data-table" id="example">

                    <thead>

                        <tr>
                            <th scope="col" style="width: 10px;">
                                <div class="form-check">
                                    <input class="form-check-input fs-15" type="checkbox" id="checkAll"
                                           value="option">
                                </div>
                            </th>
                            <th>No</th>

                            <th>Name</th>

                            <th>Email</th>

                            <th width="100px">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    </tbody>

                </table>

            </div>
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

        {data: 'id', name: 'id'},
        {data: 'id', name: 'id'},

        {data: 'name', name: 'name'},

        {data: 'email', name: 'email'},

        {data: 'action', name: 'action', orderable: false, searchable: false},
    ]
});

</script>
@endsection
