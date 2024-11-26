@extends('layouts.master')
@section('title','Customer Activity History | HVL')

@section('vendor-style')
@endsection
@php
$isOperators =false;
@endphp
@role('Operators')
@php
$isOperators = true;
@endphp
@endrole


@section('content')
<section>
    <div class="container-fluid">
        <header>
            <div class="row">
                <div class="col-md-7">
                    <h2 class="h3 display">Customer Activity History</h2>
                </div>
            </div>
        </header>
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
                                <th>Subject</th>
                                <th>Frequency</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total Activities</th>
                                <th>Completed Activities</th>
                                @if(!$isOperators)
                                <th>Total Service Value</th>
                                <th>Per Service Value</th>
                                <th>Completed Service Value</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($result as $row_data)
                            <tr>
                                <td> 
                                    <a href="{{ route('customer.services.batchupdate', [$row_data['batch'],$customer_id,str_replace('/', '-', $row_data['subject']),$row_data['frequency'],$row_data['total_activities']]) }}"
                                       class="p-2"
                                       data-position="top"
                                       data-tooltip="update batch amount">
                                        {{(isset($row_data['subject']))?$row_data['subject']:''}}
                                    </a>

                                </td>
                                <td>{{(isset($row_data['frequency']))?$row_data['frequency']:''}}</td>
                                <td>{{(isset($row_data['start_date']))?$row_data['start_date']:''}}</td>
                                <td>{{(isset($row_data['end_date']))?$row_data['end_date']:''}}</td>
                                <td>{{(isset($row_data['total_activities']))?$row_data['total_activities']:0}}</td>
                                <td>{{(isset($row_data['completed_activities']))?$row_data['completed_activities']:0}}</td>
                                @if(!$isOperators)
                                <td>{{(isset($row_data['total_service_value']))?$row_data['total_service_value']:0}}</td>
                                <td>@if(isset($row_data['completed_activities']) && $row_data['completed_activities'] != 0 && $row_data['completed_activities'] != null)
                                    {{ ($row_data['completed_service_value'] / $row_data['completed_activities'])}}
                                    @endif
                                </td>
                                <td>{{(isset($row_data['completed_service_value']))?$row_data['completed_service_value']:0}}</td>
                                @endif
                            </tr>
                            @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>                            
</section>
@endsection

@section('page-script')
@endsection
