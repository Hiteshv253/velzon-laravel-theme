@extends('layouts.master')

@section('content')
    <div class="pt-1 pb-0" id="breadcrumbs-wrapper">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title">
                        @php $table_name = str_replace('_', ' ', $table_name) @endphp
                        <span>Edit {{ \Illuminate\Support\Str::singular($table_name) }}</span></h5>
                </div>

            </div>
        </div>
    </div>
    <div class="col s12 col s12 m6 l6 offset-l3">
        <div class="container">
            @if ($errors->any())
                <div class="card-alert card gradient-45deg-red-pink">
                    <div class="card-content white-text">
                        @foreach ($errors->all() as $error)
                            <p>
                                {{ $error }}
                            </p>
                        @endforeach
                    </div>
                    <button type="button" class="close white-text" data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            @endif
            <div class="section">

                <div id="basic-form" class="card card card-default scrollspy">

                    <div class="card-content">
                        <form action="
                        @if($table_name === 'employees')
                        {{ route('employees.update') }}
                        @elseif($table_name === 'departments')
                        {{ route('departments.update') }}
                        @elseif($table_name === 'teams')
                        {{ route('teams.update') }}
                        @elseif($table_name === 'designations')
                        {{ route('designations.update') }}
                        @else
                        {{ route('modules.module.update') }}
                        @endif
                            " method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="table_name" value="{{ str_replace(' ', '_', $table_name) }}">
                            @if(isset($table_data)) <input type="hidden" name="id" value="{{ $table_data->id }}"> @endif
                            <div class="card-title">
                                <div class="row">
                                    <div class="col s12 m6 l9">
                                        <h4 class="card-title">
                                            {{ \Illuminate\Support\Str::singular($table_name) }} Information
                                        </h4>
                                    </div>
                                    <div class="col s12 m6 l3">
                                        <button
                                            class="btn waves-effect waves-light gradient-45deg-light-blue-cyan"
                                            type="submit" name="action">Save
                                            <i class="material-icons right">send</i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                @include('module.form')
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Basic Select2 select
        $(".select2").select2({
            dropdownAutoWidth: true,
            width: '100%'
        });
        $(document).ready(function () {
            $('input.maxlength, textarea.maxlength').characterCounter();
        });
        $('.dynamic_datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });
    </script>
@endsection
