
@extends('layouts.master')

{{-- page title --}}
@php $table_name = str_replace('_', ' ', $table); @endphp

@php $pagetitle = ($table_name == 'Rating')?'Geographical Segment':ucfirst($table_name); @endphp
@section('title', $pagetitle.' | HVL')

{{-- vendor styles --}}
@section('vendor-style')


@endsection

@section('content')
@php
$user = auth()->user();
@endphp
    <div class="breadcrumb-holder">
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{url('/')}}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    @if($table_name == 'LeadSource')
                        Lead Source
                    @elseif($table_name == 'LeadStatus')
                        Lead Status
                    @elseif($table_name == 'activitystatus')
                        Activity Status
                    @elseif($table_name == 'activitytype')
                        Activity Type
                    @elseif($table_name == 'Rating')
                        Geographical Segment
                    @else
                    {{ucfirst($table_name)}}
                        @endif
                </li>
            </ul>
        </div>
    </div>
    <section>
        <div class="container-fluid">
            <header>
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="h3 display">
                            @if($table_name == 'LeadSource')
                                Lead Source
                            @elseif($table_name == 'LeadStatus')
                                Lead Status
                            @elseif($table_name == 'activitystatus')
                                Activity Status
                            @elseif($table_name == 'activitytype')
                                Activity Type
                            @elseif($table_name == 'Rating')
                                Geographical Segment
                            @else
                            {{ucfirst($table_name) }}</h2>
                            @endif

                    </div>
                    <div class="col-md-8">
                        <a href="{{ url('/modules/module/' . $table . '/create') }}" class="btn btn-primary pull-right rounded-pill ">Add New</a>
                        {{--  @endcan --}}
                        @can('Delete '.$table_name )
                        <a id="mass_delete_id" class="btn btn-primary pull-right rounded-pill mr-2">
                            <i class="fa fa-trash"></i> Mass Delete
                        </a>
                        @endcan
                        <a class="btn btn-primary rounded-pill pull-right mr-2 " data-toggle="modal" data-target="#modal_download">
                            <span class="fa fa-download fa-lg"></span> Download
                        </a>
                            @if($table_name == 'employees')
                                @can('Access Employee Bulkupload')
                                <a href="{{ route('admin.employeemaster_bulkupload.index') }}" class="btn btn-primary pull-right rounded-pill mr-2">
                                <span class="fa fa-upload fa-lg"></span> Bulk Upload
                                </a>
                                @endcan
                            @endif
                    </div>
                </div>
            </header>

            <div class="card">
                <div class="card-body p-4">
                   
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table id="page-length-option" class="table table-striped table-hover multiselect nowrap">
                <thead>
                <tr>
                    <th width="2%">
                        <label>
                            <input type="checkbox" class="select-all"/>
                            <span></span>
                        </label>
                    </th>
                    <th class="sorting_disabled" width="5%">Action</th>
                    @foreach ($filter_columns as $forms)
                        @foreach(json_decode($forms->form) as $column)
                            @if($column->type !== 'file')
                                @if($column->type !== 'section')
                                    @if($column->label == 'Select Department')
                                        <th width="10%">Departments</th>
                                    @elseif($column->label == 'Select Designation')
                                        <th width="10%">Designation</th>
                                    @elseif($column->label == 'Select Team')
                                        <th width="10%">Team</th>
                                    @elseif($column->label == 'select city')
                                        <th width="10%">City</th>
                                    @elseif($column->label == 'zone')
                                       <th width="10%">Zone</th>
                                    @else
                                    <th width="10%">{{ str_replace('_', ' ', $column->label) }}</th>
                                    @endif
                                @endif
                            @endif
                        @endforeach
                    @endforeach
                    @if($module_name == 'employees')
                        <th width="10%">Created Date</td>
                        <th width="10%">Updated Date</td>
                            <?php if(Auth::id()=='122'){
                                echo '<th>Create By</th>';
                            }?>
                            
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $key => $row)
                    <tr>
                        <td width="2%">
                            <label>
                                <input type="checkbox" data-id="{{ $row->id }}"
                                       name="selected_row"/>
                                <span></span>
                            </label>
                        </td>
                        <td width="10%" >
                        @foreach($columns as $column)
                            @if($column === 'id')
                                @can('Edit '.$table_name)
                                    <a href="{{ route('modules.module.edit', [$table, $row->$column]) }}"
                                        class="p-2">
                                        <span class="fa fa-edit"></span>
                                    </a>
                                @endcan
                                @can('Delete '.$table_name)
                                    @if($table_name == 'employees')
                                        @if($row->$column != 121)
                                            <a href="" class="button" data-id="{{ $row->$column }}"  data-name="{{ $table }}">
                                                <span class="fa fa-trash"></span>
                                            </a>
                                        @endif
                                    @else
                                        <a href="" class="button" data-id="{{ $row->$column }}"  data-name="{{ $table }}">
                                            <span class="fa fa-trash"></span>
                                        </a>
                                    @endif
                                @endcan
                            @endif
                        @endforeach
                        </td>
                        @foreach ($filter_columns as $forms)
                            @foreach(json_decode($forms->form) as $column)
                                @if($column->type !== 'file')
                                    @if($column->type !== 'section')
                                        @php $label = str_replace(' ', '_', $column->label) @endphp
                                        @if($column->type === 'lookup')
                                            @php $tableDatas = DB::table($column->module)->where('id', $row->$label)->first(); @endphp
                                            <td width="10%">
                                                @if(!empty($tableDatas))
                                                    {{ $tableDatas->Name }}
                                                @endif
                                            </td>
                                        @else
                                            <td width="10%">
                                                @if( $row->$label == 'email')
                                                    Email ID
                                                @else
                                                {{$row->$label}}
                                                    @endif
                                            </td>
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                        @if($module_name == 'employees')
                        <td width="10%">{{$row->created_at}}</td>
                        <td width="10%">{{$row->updated_at}}</td>
                        <?php if(Auth::id()=='122'){
                                echo '<td>'.app('App\Http\Controllers\Controller')->getConditionDynamicNameTable('employees', 'user_id', $row->is_create_by, 'name').'</td>'; 
                            } ?>
                        @endif
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



        <div id="modal_download" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Download Report</h4>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body p-4 row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body text-center ">
                                    <button class="btn btn-success " onclick="exportTableToCSV('Report.csv')">
                                        <span class="fa fa-file-excel-o fa-3x text-green"></span>
                                        CSV
                                    </button>
                                </div>
                            </div>
                        </div>
                         <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <button class="btn btn-primary center" data-toggle="modal" data-target="#email_div">
                                        <span class="fa fa-envelope fa-3x text-danger"></span>
                                        Email
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div id="email_div" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Send Mail</h4>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body p-4 row">
                        <div class="col-sm-12">
                            <form action="{{ route('mail.sendcsv') }}" method="post" id="mail_form" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-sm-12 col-md-6">
                                        <label for="">To</label>
                                        <input type="email" class="form-control"  name="to" required>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-6">
                                        <label for="">CC</label>
                                        <input type="email" class="form-control"  name="cc">
                                    </div>

                                    <div class="form-group col-sm-12 col-md-6">
                                        <label for="">BCC</label>
                                        <input type="email" class="form-control"  name="bcc">
                                    </div>

                                    <div class="form-group col-sm-12 col-md-6">
                                        <label for="">Subject</label>
                                        <input type="text"  class="form-control"  name="subject" required>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-6">
                                        <label for="">Body</label>
                                        <textarea class="form-control" name="body"></textarea>
                                    </div>

                                    <div class="col-sm-12">

                                        <input type="submit" class="btn btn-success rounded" value="Send">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>

@endsection

{{-- vendor script --}}
@section('vendor-script')


@endsection

{{-- page script --}}
@section('page-script')

<script>
    $(document).ready(function() {

        $('#page-length-option').DataTable({
            "scrollX": true,
        });
    });
                    $(document).ready(function () {
//                       $('#theme-cutomizer-out').hide();
//                         $('.modal').modal();

                        // mass delete
                        $('#mass_delete_id').click(function () {
                            var checkbox_array = [];
                            var token = $("meta[name='csrf-token']").attr("content");
                            $.each($("input[name='selected_row']:checked"), function () {
                                checkbox_array.push($(this).data("id"));
                            });
                            // console.log(checkbox_array);
                            if (typeof checkbox_array !== 'undefined' && checkbox_array.length > 0) {

                                swal({
                                        title: "Are you sure, you want to delete " + name + "? ",
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
                                            url: 'massdestroy',
                                            type: 'POST',
                                            data: {
                                                "_token": token,
                                                ids: checkbox_array,
                                                table: '{{$table}}'
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
                            } else {
                                swal({
                                    title: "Please Select Atleast One Record",
                                    type: 'warning',

                                });
                            }

                        });


                        $(document).on('click', '.button', function (e) {
                            e.preventDefault();
                            var id = $(this).data("id");
                            var name = $(this).data("name");
                       
                            var token = $("meta[name='csrf-token']").attr("content");

                            swal({
                                    title: "Are you sure, you want to delete? ",
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
                                        url: name + "/" + id,
                                        type: 'DELETE',
                                        data: {
                                            "_token": token,
                                            "name":name,
                                            "id":id
                                        },
                                        success: function (result) {
                                            if(result === 'error')
                                            {
                                                swal({
                                                    title: "Employee cannot be deleted as customer is assigned to this employee",
                                                    type: "warning",
                                                })
                                            }
                                            else if(result === 'zone_error')
                                            {
                                                swal({
                                                    title: "Zone cannot be deleted as branch is assigned to this zone",
                                                    type: "warning",
                                                })
                                            }
                                            else{
                                                swal({
                                                    title: "Record has been deleted!",
                                                    type: "success",
                                                }, function () {
                                                    location.reload();
                                                });
                                            }
                                        }
                                    });
                                }
                            });
                        });


                        var checkbox = $('.multiselect tbody tr td input');
                        var selectAll = $('.multiselect .select-all');

                        checkbox.on('click', function () {
                            $(this).parent().parent().parent().toggleClass('selected');
                        });

                        checkbox.on('click', function () {
                            if ($(this).attr("checked")) {
                                $(this).attr('checked', false);
                            } else {
                                $(this).attr('checked', true);
                            }
                        });


                        // Select Every Row

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


                        $("#reset").click(function () {
                            window.location.href = window.location.href.split('?')[0];
                        });
                        $('#filter_form_id').submit(function () {
                            $(this)
                                .find('input[name]')
                                .filter(function () {
                                        return !this.value;
                                    }).prop('name', '');
                        });



                        $('#savefilter-cancel').on('click', function () {
                            $('#save-filter').hide();
                            $('#filter').show();
                        });

                        $('.rename_savefilter').on('click', function () {
                            var filter_name = $(this).data('filtername');
                            var filter_id = $(this).data('filterid');
                            $("#update_filter_name").val(filter_name);
                            $("#update_filter_id").val(filter_id);
                        });

                    });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
<script>
    var csv = [];
    var rows = document.querySelectorAll("table tr");

    for (var i = 0; i < rows.length; i++) {
        var row = [],
            cols = rows[i].querySelectorAll("td, th");

        for (var j = 0; j < cols.length; j++)
            if (j !== 0) {
                if (j !== cols.length - 1) {
                    row.push(cols[j].innerText);
                }
            }

        csv.push(row.join(","));
    }
    var csvFile;

    csvFile = new Blob([csv.join("\n")], {
        type: "text/csv"
    });
    var reader = new FileReader();
    reader.readAsDataURL(csvFile);
    reader.onloadend = function () {

        var base64data = reader.result;
        var data = base64data.match(/base64,(.+)$/);
        var base64String = data[1];

        var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "csvfile").val(base64String);
        $('#mail_form').append(input);
    };

    function downloadCSV(csv, filename) {
        var csvFile;
        var downloadLink;

        // CSV file
        csvFile = new Blob([csv], {
            type: "text/csv"
        });

        // Download link
        downloadLink = document.createElement("a");

        // File name
        downloadLink.download = filename;

        // Create a link to the file
        downloadLink.href = window.URL.createObjectURL(csvFile);

        // Hide download link
        downloadLink.style.display = "none";

        // Add the link to DOM
        document.body.appendChild(downloadLink);

        // Click download link
        downloadLink.click();
    }

    function exportTableToCSV(filename) {
        var csv = [];
        var rows = document.querySelectorAll("table tr");

        for (var i = 1; i < rows.length; i++) {
            var row = [],
                cols = rows[i].querySelectorAll("td, th");

            for (var j = 0; j < cols.length; j++)
                if (j !== 0) {
                    if (j !== cols.length - 1) {
                        row.push(cols[j].innerText);
                    }
                }

            csv.push(row.join(","));
        }

        // Download CSV file
        downloadCSV(csv.join("\n"), filename);
    }

    function exportTableToPDF() {
        var pdf = new jsPDF('p', 'pt', 'letter');

        pdf.cellInitialize();
        pdf.setFontSize(10);
        $.each($('table tr'), function (i, row) {
            var total = $(row).find("td, th").length;
            $.each($(row).find("td, th"), function (j, cell) {
                if (j !== 0) {
                    if (j !== total - 1) {
                        var txt = $(cell).text().trim() || " ";
                        var txtWidth = pdf.splitTextToSize(txt, 100);
                        var width = 100;
                        pdf.cell(30, 30, width, 30, txtWidth, i);
                    }
                }
            });
        });

        pdf.save('sample-file.pdf');
    }

</script>
@endsection
