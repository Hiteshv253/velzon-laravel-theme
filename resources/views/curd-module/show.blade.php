@extends('layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/page-users.css') }}">
@endsection

@section('content')
    <div class="col s12">
        <div class="container">
            <!-- users view start -->
            <div class="section users-view">
                <!-- users view media object start -->
                <div class="card-panel">
                    <div class="row">
                        <div class="col s12 m7">
                            <div class="display-flex media">
                                {{--                                <a href="#" class="avatar">--}}
                                {{--                                    <img src="../../../app-assets/images/avatar/avatar-15.png" alt="users view avatar" class="z-depth-4 circle" height="64" width="64">--}}
                                {{--                                </a>--}}
                                <div class="media-body">
                                    <h5 class="media-heading">
                                        {{--                                        <a href="#" class="tooltipped" data-position="bottom"--}}
                                        {{--                                           data-tooltip="Back"><span class="material-icons">arrow_back</span></a>--}}
                                        <span class="users-view-username grey-text">{{ $table_data->Name }}</span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        @can('Edit '.str_replace('_', ' ', $table_name))
                            <div
                                class="col s12 m5 quick-action-btns display-flex justify-content-end align-items-center pt-2">
                                <a href="{{ route('modules.module.edit', [$table_name, $table_data->id]) }}"
                                   class="btn-small gradient-45deg-light-blue-cyan">Edit</a>
                            </div>
                        @endcan
                    </div>
                </div>
                <!-- users view media object ends -->

                <!-- users view card details start -->
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col s12">
                                <h6 class="mb-2 mt-2">{{ $table_name }} Information</h6>

                                @if($table_name === 'employees')
                                    @php $employees = \App\Employee::find($table_data->id) @endphp
                                    <table class="striped">
                                        <tbody>
                                        <tr>
                                            <td>Email</td>
                                            <td class="users-view-username">{{ $table_data->email }}</td>
                                        </tr>
                                        <tr>
                                            <td>Departments</td>
                                            <td>
                                                @if(isset($employees->departments))
                                                    @foreach($employees->departments as $dep)
                                                        <a href="{{ route('modules.module.show', ['departments', $dep->id]) }}"
                                                           class="collection-item">
                                                            <span class="new badge gradient-45deg-light-blue-cyan"
                                                                  data-badge-caption="{{ $dep->Name }}"></span>
                                                        </a>
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Teams</td>
                                            <td>
                                                @if(isset($employees->teams))
                                                    @foreach($employees->teams as $team)
                                                        <a href="{{ route('modules.module.show', ['teams', $team->id]) }}"
                                                           class="collection-item">
                                                            <span class="new badge gradient-45deg-light-blue-cyan"
                                                                  data-badge-caption="{{ $team->Name }}"></span>
                                                        </a>
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Designations</td>
                                            <td>
                                                @if(isset($employees->designations))
                                                    @foreach($employees->designations as $des)
                                                        <a href="{{ route('modules.module.show', ['designations', $des->id]) }}"
                                                           class="collection-item">
                                                            <span class="new badge gradient-45deg-light-blue-cyan"
                                                                  data-badge-caption="{{ $des->Name }}"></span>
                                                        </a>
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                @endif

                                @if($table_name === 'departments')
                                    @php $department = \App\Department::find($table_data->id) @endphp
                                    <table class="striped">
                                        <tbody>
                                        <tr>
                                            <td>Employees</td>
                                            <td class="users-view-username">
                                                @foreach($department->employees as $emp)
                                                    <a href="{{ route('modules.module.show', ['employees', $emp->id]) }}"
                                                       class="collection-item">
                                                            <span class="new badge gradient-45deg-light-blue-cyan"
                                                                  data-badge-caption="{{ $emp->Name }}"></span>
                                                    </a>
                                                @endforeach
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                @endif

                                @if($table_name === 'teams')
                                    @php $team = \App\Team::find($table_data->id) @endphp
                                    <table class="striped">
                                        <tbody>
                                        <tr>
                                            <td>Employees</td>
                                            <td class="users-view-username">
                                                @foreach($team->employees as $emp)
                                                    <a href="{{ route('modules.module.show', ['employees', $emp->id]) }}"
                                                       class="collection-item">
                                                            <span class="new badge gradient-45deg-light-blue-cyan"
                                                                  data-badge-caption="{{ $emp->Name }}"></span>
                                                    </a>
                                                @endforeach
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                @endif

                                @if($table_name === 'designations')
                                    @php $designation = \App\Designation::find($table_data->id) @endphp
                                    <table class="striped">
                                        <tbody>
                                        <tr>
                                            <td>Employees</td>
                                            <td class="users-view-username">
                                                @foreach($designation->employees as $emp)
                                                    <a href="{{ route('modules.module.show', ['employees', $emp->id]) }}"
                                                       class="collection-item">
                                                            <span class="new badge gradient-45deg-light-blue-cyan"
                                                                  data-badge-caption="{{ $emp->Name }}"></span>
                                                    </a>
                                                @endforeach
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                @endif

                                @foreach($fields as $field)
                                    @foreach(json_decode($field->form) as $item)
                                        @if ($item->type !== 'section')
                                            @php $f = str_replace(' ', '_', $item->label); @endphp
                                            <table class="striped">
                                                <tbody>
                                                <tr>
                                                    <td>{{ $item->label }}</td>
                                                    <td class="users-view-username">{{ $table_data->$f }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        @else
                                            <h6 class="mb-2 mt-2">{{ $item->label }}</h6>
                                        @endif
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                        <!-- </div> -->
                    </div>
                </div>
                <!-- users view card details ends -->

            </div>
            <!-- users view ends -->

        </div>
        <div class="content-overlay"></div>
    </div>
@endsection
