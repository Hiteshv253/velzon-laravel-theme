@extends('layouts.master')

{{-- page title --}}
@section('title','Customer Management | HVL')

@section('vendor-style')
@endsection
@section('content')

    <section>
        <div class="container-fluid">
            <header>
                <div class="row">
                    <div class="col-md-7">
                        <h2 class="h3 display">Customer Report</h2>
                    </div>
                    <div class="col-md-5">
                                                    <a class="btn btn-primary rounded-pill pull-right mr-2 " data-toggle="modal" data-target="#modal_download">
                                                        <span class="fa fa-download fa-lg"></span> Export
                                                    </a>

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

                    <div class="table-responsive">
                        <table id="page-length-option" class="table table-striped table-hover multiselect">
                            <thead>
                            <tr>

                                <th width="2%">ID</th>
                                {{--                                                <th width="7%">Employee Name</th>--}}
                                <th width="20%">Customer Code</th>
                                <th width="20%">Customer Name</th>
                                <th width="7%">Customer Alias</th>
                                <th width="5%">Billing Mail</th>
                                {{--                                                <th width="5%">Contact Person </th>--}}
                                <th width="5%">Billing Phone</th>
                                <th width="5%">Create Date</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($customerDetails as $key => $Detailes)

                                <tr>

                                    <td>
                                        <center>{{$loop->iteration}}</center>
                                    </td>
                                    {{--                                        <td>{{$Detailes->employee_name}}</td>--}}
                                    <td>{{$Detailes[0]->customer_code}}</td>
                                    <td>{{$Detailes[0]->customer_name}}</td>
                                    <td>{{$Detailes[0]->customer_alias}}</td>
                                    <td>{{$Detailes[0]->billing_email}}</td>
                                    {{--                                                    <td>{{$Detailes->contact_person}}</td>--}}
                                    <td>{{$Detailes[0]->billing_mobile}}</td>
                                    <td>{{$Detailes[0]->create_date}}</td>

                                </tr>
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


@section('page-script')
    <script>

        $(document).ready(function () {
            $('#page-length-option').DataTable({
                "scrollX": true,
                "fixedHeader": true,
                "lengthMenu": [[10, 25, 50,100, -1], [10, 25, 50,100, "All"]]
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
    <script>

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
        var pdfBase64 = pdf.output('datauristring');
        var data = pdfBase64.match(/base64,(.+)$/);
        var base64String = data[1];

        var input = $("<input>")
            .attr("type", "hidden")
            .attr("name", "pdffile").val(base64String);
        $('#mail_form').append(input);

        var csv = [];
        var rows = document.querySelectorAll("table tr");

        for (var i = 0; i < rows.length; i++) {
            var row = [],
                cols = rows[i].querySelectorAll("td, th");

            for (var j = 0; j < cols.length; j++)
                if (j !== 0) {
                    if (j !== cols.length - 0) {
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
                        if (j !== cols.length - 0) {
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
                        if (j !== total - 0) {
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


