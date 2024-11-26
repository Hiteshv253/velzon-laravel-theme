@if(count($save_filters) !== 0)
    <div class="card">
        <span class="card-title pt-2 pb-2 pl-2 pr-2">Saved Filters</span>
        @foreach($save_filters as $index => $filter)
            <div class="card-content pt-2 pb-2 pl-2 pr-2">
                <a href="{{ $filter->link }}" class="pl-3"
                   style="width: 200px; display: inline-block;">{{ $filter->name }}</a>
                <div class="dropdown right">
                    <a class="dropdown-trigger" href="#" data-target="{{ $index }}">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul id="{{ $index }}" class="dropdown-content" style="">
                        <li tabindex="0">
                            <a class="modal-trigger rename_savefilter" href="#save_filter_update_modal"
                               data-filtername="{{ $filter->name }}" data-filterid="{{ $filter->id }}">
                                <span class="menu-item">Rename</span>
                            </a>
                        </li>
                        <li tabindex="0">
                            <form action="{{ route('savefilter.destroy') }}" method="post">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="id" value="{{ $filter->id }}">
                                <button type="submit" class="menu-item border-none"
                                        style="padding: 14px 24px; width: 100%;">Delete
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@endif

<div class="card">
    <div class="card-content pt-2 pb-2 pl-2 pr-2">
        <span class="card-title">Filter {{ $table_name }} By </span>
        <hr style="border-top: dotted 1px;">
        <form action="" method="get" id="filter_form_id">
            <input type="hidden" name="filter" value="filter">

            @if($table_name === 'employees')
                <ul class='collapsible '>
                    <li @if(request()->input('condition_email')) class="active" @endif>
                        <div class="collapsible-header pt-3 pb-3"><i
                                class="material-icons">arrow_drop_down</i> Email
                        </div>
                        <div class='collapsible-body pt-7 pb-7 pl-7 pr-7'>
                            <select name="condition_email"
                                    class="browser-default string-select-class"
                                    onchange="
                                                            if (this.value === 'isEmpty_email')
                                                            {this.form['email'].style.display='none'
                                                            this.form['email'].value=''
                                                            this.form['email'].style.visibility='hidden'}
                                                            else if (this.value ==='isNotEmpty_email')
                                                            {this.form['email'].style.display='none'
                                                            this.form['email'].value=''
                                                            this.form['email'].style.visibility='hidden'}
                                                            else {this.form['email'].style.display='block'
                                                            this.form['email'].style.visibility='visible'}"
                            >
                                <option selected disabled="disabled">Select</option>
                                <option value="is_email"
                                        @if(request()->input('condition_email') === 'is_email') selected @endif>
                                    Is
                                </option>
                                <option value="isNot_email"
                                        @if(request()->input('condition_email') === 'isNot_email') selected @endif>
                                    Isn't
                                </option>
                                <option value="contains_email"
                                        @if(request()->input('condition_email') === 'contains_email') selected @endif>
                                    Contains
                                </option>
                                <option value="notContains_email"
                                        @if(request()->input('condition_email') === 'notContains_email') selected @endif>
                                    Doesn't Contains
                                </option>
                                <option value="startWith_email" class="empty"
                                        @if(request()->input('condition_email') === 'startWith_email') selected @endif>
                                    Start with
                                </option>
                                <option value="endWith_email" class="empty"
                                        @if(request()->input('condition_email') === 'endWith_email') selected @endif>
                                    End with
                                </option>
                                <option value="isEmpty_email" class="empty"
                                        @if(request()->input('condition_email') === 'isEmpty_email') selected @endif>
                                    Is Empty
                                </option>
                                <option value="isNotEmpty_email" class="notEmpty"
                                        @if(request()->input('condition_email') === 'isNotEmpty_email') selected @endif>
                                    Is Not Empty
                                </option>
                            </select>
                            <input type="text" name="email"
                                   placeholder="Enter email"
                                   value="@if(app('request')->input('email')){{app('request')->input('email')}}@endif"
                                   @if(request()->input('email')) style="display: block"
                                   @else style="display: none" @endif
                            >

                        </div>
                    </li>
                </ul>
            @endif

            @foreach ($filter_columns as $forms)
                @foreach(json_decode($forms->form) as $column)
                    @php $u_column = str_replace(' ', '_', $column->label) @endphp
                    @if($column->type !== 'file')
                        @if($column->type !== 'section')
                            @if($column->type === 'date')
                                <ul class='collapsible '>
                                    <li @if(request()->input('date_'.$u_column) !== null) class="active" @endif>
                                        <div class="collapsible-header pt-3 pb-3"><i
                                                class="material-icons">arrow_drop_down</i> {{ $column->label }}
                                        </div>
                                        <div class='collapsible-body pt-7 pb-7 pl-7 pr-7'>
                                            <select name="date_{{$u_column}}" class="browser-default"
                                                    onchange="
                                                        if (this.value === 'on_{{$u_column}}')
                                                        {this.form['{{$u_column}}'].style.visibility='hidden'
                                                        this.form['{{$u_column}}'].style.display='none'
                                                        this.form['{{$u_column}}'].value=''
                                                        this.form['dwm_{{$u_column}}'].style.display='none'
                                                        this.form['dwm_{{$u_column}}'].selectedIndex = 0
                                                        this.form['generalDate_{{$u_column}}'].style.visibility='visible'
                                                        this.form['generalDate_{{$u_column}}'].style.display='block'
                                                        this.form['generalDate_{{$u_column}}'].value=''
                                                        this.form['betweenStart_{{ $u_column }}'].style.visibility='hidden'
                                                        this.form['betweenStart_{{ $u_column }}'].style.display='none'
                                                        this.form['betweenStart_{{ $u_column }}'].value=''
                                                        this.form['betweenEnd_{{ $u_column }}'].style.visibility='hidden'
                                                        this.form['betweenEnd_{{ $u_column }}'].style.display='none'
                                                        this.form['betweenEnd_{{ $u_column }}'].value=''
                                                        }else if(this.value === 'before_{{$u_column}}'){
                                                        this.form['{{$u_column}}'].style.visibility='hidden'
                                                        this.form['{{$u_column}}'].style.display='none'
                                                        this.form['{{$u_column}}'].value=''
                                                        this.form['dwm_{{$u_column}}'].style.display='none'
                                                        this.form['dwm_{{$u_column}}'].selectedIndex= 0
                                                        this.form['generalDate_{{$u_column}}'].style.visibility='visible'
                                                        this.form['generalDate_{{$u_column}}'].style.display='block'
                                                        this.form['betweenStart_{{ $u_column }}'].style.visibility='hidden'
                                                        this.form['betweenStart_{{ $u_column }}'].style.display='none'
                                                        this.form['betweenStart_{{ $u_column }}'].value=''
                                                        this.form['betweenEnd_{{ $u_column }}'].style.visibility='hidden'
                                                        this.form['betweenEnd_{{ $u_column }}'].style.display='none'
                                                        this.form['betweenEnd_{{ $u_column }}'].value=''
                                                        }else if(this.value === 'after_{{$u_column}}'){
                                                        this.form['{{$u_column}}'].style.visibility='hidden'
                                                        this.form['{{$u_column}}'].style.display='none'
                                                        this.form['{{$u_column}}'].value=''
                                                        this.form['dwm_{{$u_column}}'].style.display='none'
                                                        this.form['dwm_{{$u_column}}'].selectedIndex= 0
                                                        this.form['generalDate_{{$u_column}}'].style.visibility='visible'
                                                        this.form['generalDate_{{$u_column}}'].style.display='block'
                                                        this.form['betweenStart_{{ $u_column }}'].style.visibility='hidden'
                                                        this.form['betweenStart_{{ $u_column }}'].style.display='none'
                                                        this.form['betweenStart_{{ $u_column }}'].value=''
                                                        this.form['betweenEnd_{{ $u_column }}'].style.visibility='hidden'
                                                        this.form['betweenEnd_{{ $u_column }}'].style.display='none'
                                                        this.form['betweenEnd_{{ $u_column }}'].value=''
                                                        }else if(this.value === 'between_{{$u_column}}'){
                                                        this.form['{{$u_column}}'].style.visibility='hidden'
                                                        this.form['{{$u_column}}'].style.display='none'
                                                        this.form['{{$u_column}}'].value=''
                                                        this.form['dwm_{{$u_column}}'].style.display='none'
                                                        this.form['dwm_{{$u_column}}'].selectedIndex=0
                                                        this.form['generalDate_{{$u_column}}'].style.visibility='hidden'
                                                        this.form['generalDate_{{$u_column}}'].style.display='none'
                                                        this.form['generalDate_{{$u_column}}'].value=''
                                                        this.form['betweenStart_{{ $u_column }}'].style.visibility='visible'
                                                        this.form['betweenStart_{{ $u_column }}'].style.display='block'
                                                        this.form['betweenEnd_{{ $u_column }}'].style.visibility='visible'
                                                        this.form['betweenEnd_{{ $u_column }}'].style.display='block'
                                                        }else if(this.value === 'today_{{$u_column}}' || this.value === 'yesterday_{{$u_column}}' || this.value === 'thisWeek_{{$u_column}}' || this.value === 'thisMonth_{{$u_column}}' || this.value === 'thisYear_{{$u_column}}' || this.value === 'lastWeek_{{$u_column}}' || this.value === 'lastMonth_{{$u_column}}' || this.value === 'dateIsEmpty_{{$u_column}}' || this.value === 'dateIsNotEmpty_{{$u_column}}'){
                                                        this.form['{{$u_column}}'].style.visibility='hidden'
                                                        this.form['{{$u_column}}'].style.display='none'
                                                        this.form['{{$u_column}}'].value=''
                                                        this.form['dwm_{{$u_column}}'].style.display='none'
                                                        this.form['dwm_{{$u_column}}'].selectedIndex = 0
                                                        this.form['generalDate_{{$u_column}}'].style.visibility='hidden'
                                                        this.form['generalDate_{{$u_column}}'].style.display='none'
                                                        this.form['generalDate_{{$u_column}}'].value=''
                                                        this.form['betweenStart_{{ $u_column }}'].style.visibility='hidden'
                                                        this.form['betweenStart_{{ $u_column }}'].style.display='none'
                                                        this.form['betweenStart_{{ $u_column }}'].value=''
                                                        this.form['betweenEnd_{{ $u_column }}'].style.visibility='hidden'
                                                        this.form['betweenEnd_{{ $u_column }}'].style.display='none'
                                                        this.form['betweenEnd_{{ $u_column }}'].value=''
                                                        }
                                                        else {
                                                        this.form['{{$u_column}}'].style.visibility='visible'
                                                        this.form['{{$u_column}}'].style.display='block'
                                                        this.form['dwm_{{$u_column}}'].style.display='block'
                                                        this.form['generalDate_{{$u_column}}'].style.visibility='hidden'
                                                        this.form['generalDate_{{$u_column}}'].style.display='none'
                                                        this.form['generalDate_{{$u_column}}'].value=''
                                                        this.form['betweenStart_{{ $u_column }}'].style.visibility='hidden'
                                                        this.form['betweenStart_{{ $u_column }}'].style.display='none'
                                                        this.form['betweenStart_{{ $u_column }}'].value=''
                                                        this.form['betweenEnd_{{ $u_column }}'].style.visibility='hidden'
                                                        this.form['betweenEnd_{{ $u_column }}'].style.display='none'
                                                        this.form['betweenEnd_{{ $u_column }}'].value=''}"
                                            >
                                                <option selected disabled="disabled">Select</option>
                                                <option value="inTheLast_{{$u_column}}"
                                                        @if(request()->input('date_'.$u_column) === 'inTheLast_'.$u_column) selected @endif>
                                                    In the last
                                                </option>
                                                <option value="dueIn_{{$u_column}}"
                                                        @if(request()->input('date_'.$u_column) === 'dueIn_'.$u_column) selected @endif>
                                                    Due in
                                                </option>
                                                <option value="on_{{$u_column}}"
                                                        @if(request()->input('date_'.$u_column) === 'on_'.$u_column) selected @endif>
                                                    On
                                                </option>
                                                <option value="before_{{$u_column}}"
                                                        @if(request()->input('date_'.$u_column) === 'before_'.$u_column) selected @endif>
                                                    Before
                                                </option>
                                                <option value="after_{{$u_column}}"
                                                        @if(request()->input('date_'.$u_column) === 'after_'.$u_column) selected @endif>
                                                    After
                                                </option>
                                                <option value="between_{{$u_column}}"
                                                        @if(request()->input('date_'.$u_column) === 'between_'.$u_column) selected @endif>
                                                    Between
                                                </option>
                                                <option value="today_{{$u_column}}"
                                                        @if(request()->input('date_'.$u_column) === 'today_'.$u_column) selected @endif>
                                                    Today
                                                </option>
                                                <option value="yesterday_{{$u_column}}"
                                                        @if(request()->input('date_'.$u_column) === 'yesterday_'.$u_column) selected @endif>
                                                    Yesterday
                                                </option>
                                                <option value="thisWeek_{{$u_column}}"
                                                        @if(request()->input('date_'.$u_column) === 'thisWeek_'.$u_column) selected @endif>
                                                    This week
                                                </option>
                                                <option value="thisMonth_{{$u_column}}"
                                                        @if(request()->input('date_'.$u_column) === 'thisMonth_'.$u_column) selected @endif>
                                                    This month
                                                </option>
                                                <option value="thisYear_{{$u_column}}"
                                                        @if(request()->input('date_'.$u_column) === 'thisYear_'.$u_column) selected @endif>
                                                    This year
                                                </option>
                                                <option value="lastWeek_{{$u_column}}"
                                                        @if(request()->input('date_'.$u_column) === 'lastWeek_'.$u_column) selected @endif>
                                                    Last week
                                                </option>
                                                <option value="lastMonth_{{$u_column}}"
                                                        @if(request()->input('date_'.$u_column) === 'lastMonth_'.$u_column) selected @endif>
                                                    Last month
                                                </option>
                                                <option value="dateIsEmpty_{{$u_column}}"
                                                        @if(request()->input('date_'.$u_column) === 'dateIsEmpty_'.$u_column) selected @endif>
                                                    Is empty
                                                </option>
                                                <option value="dateIsNotEmpty_{{$u_column}}"
                                                        @if(request()->input('date_'.$u_column) === 'dateIsNotEmpty_'.$u_column) selected @endif>
                                                    Is not empty
                                                </option>
                                            </select>

                                            <input type="number" name="{{$u_column}}"
                                                   placeholder="Enter {{$column->label}}"
                                                   value="@if(app('request')->input($u_column)){{app('request')->input($u_column)}}@endif"
                                                   @if(app('request')->input($u_column)) style="visibility: visible;"
                                                   @else style="visibility: hidden; display: none;" @endif
                                            >
                                            <select name="dwm_{{$u_column}}" class="browser-default dwm_{{$u_column}}"
                                                    @if(app('request')->input('dwm_'.$u_column)) style="display: block"
                                                    @else style="display: none" @endif
                                            >
                                                <option selected disabled="disabled">Select</option>
                                                <option value="days_{{$u_column}}"
                                                        @if(request()->input('dwm_'.$u_column) === 'days_'.$u_column) selected @endif>
                                                    days
                                                </option>
                                                <option value="weeks_{{$u_column}}"
                                                        @if(request()->input('dwm_'.$u_column) === 'weeks_'.$u_column) selected @endif>
                                                    weeks
                                                </option>
                                                <option value="months_{{$u_column}}"
                                                        @if(request()->input('dwm_'.$u_column) === 'months_'.$u_column) selected @endif>
                                                    months
                                                </option>
                                            </select>
                                            <input
                                                @if(app('request')->input('generalDate_'.$u_column)) style="visibility: visible"
                                                @else style="visibility: hidden; display: none" @endif type="date"
                                                name="generalDate_{{$u_column}}"
                                                value="@if(app('request')->input('generalDate_'.$u_column)){{app('request')->input('generalDate_'.$u_column)}}@endif">
                                            <input type="date" name="betweenStart_{{ $u_column }}"
                                                   placeholder="Start date"
                                                   @if(app('request')->input('betweenStart_'.$u_column)) style="visibility: visible"
                                                   @else style="visibility: hidden; display: none" @endif
                                                   value="@if(app('request')->input('betweenStart_'.$u_column)){{app('request')->input('betweenStart_'.$u_column)}}@endif">
                                            <input type="date" name="betweenEnd_{{ $u_column }}" placeholder="End date"
                                                   @if(app('request')->input('betweenEnd_'.$u_column)) style="visibility: visible"
                                                   @else style="visibility: hidden; display: none" @endif
                                                   value="@if(app('request')->input('betweenEnd_'.$u_column)){{app('request')->input('betweenEnd_'.$u_column)}}@endif">
                                        </div>
                                    </li>
                                </ul>
                            @elseif($column->type === 'number')
                                <ul class='collapsible '>
                                    <li @if(request()->input('number_'.$u_column) !== null) class="active" @endif>
                                        <div class="collapsible-header pt-3 pb-3"><i
                                                class="material-icons">arrow_drop_down</i> {{ $column->label }}
                                        </div>
                                        <div class='collapsible-body pt-7 pb-7 pl-7 pr-7'>
                                            <select name="number_{{$u_column}}"
                                                    class="browser-default string-select-class"
                                                    onchange="if(this.value === 'numberBetween_{{$u_column}}' || this.value === 'numberNotBetween_{{$u_column}}' ){
                                                        this.form['numberBetweenStart_{{$u_column}}'].style.visibility='visible'
                                                        this.form['numberBetweenStart_{{$u_column}}'].style.display='block'
                                                        this.form['numberBetweenEnd_{{$u_column}}'].style.visibility='visible'
                                                        this.form['numberBetweenEnd_{{$u_column}}'].style.display='block'
                                                        this.form['generalNumberValue_{{$u_column}}'].style.visibility='hidden'
                                                        this.form['generalNumberValue_{{$u_column}}'].style.display='none'
                                                        this.form['generalNumberValue_{{$u_column}}'].value=''
                                                        }else if(this.value === 'numberEmpty_{{$u_column}}' || this.value === 'numberNotEmpty_{{$u_column}}'){
                                                        this.form['generalNumberValue_{{$u_column}}'].style.visibility='hidden'
                                                        this.form['generalNumberValue_{{$u_column}}'].style.display='none'
                                                        this.form['generalNumberValue_{{$u_column}}'].value=''
                                                        this.form['numberBetweenStart_{{$u_column}}'].style.visibility='hidden'
                                                        this.form['numberBetweenStart_{{$u_column}}'].style.display='none'
                                                        this.form['numberBetweenStart_{{$u_column}}'].value=''
                                                        this.form['numberBetweenEnd_{{$u_column}}'].style.visibility='hidden'
                                                        this.form['numberBetweenEnd_{{$u_column}}'].style.display='none'
                                                        this.form['numberBetweenEnd_{{$u_column}}'].value=''
                                                        }else{
                                                        this.form['generalNumberValue_{{$u_column}}'].style.visibility='visible'
                                                        this.form['generalNumberValue_{{$u_column}}'].style.display='block'
                                                        this.form['numberBetweenStart_{{$u_column}}'].style.visibility='hidden'
                                                        this.form['numberBetweenStart_{{$u_column}}'].style.display='none'
                                                        this.form['numberBetweenStart_{{$u_column}}'].value=''
                                                        this.form['numberBetweenEnd_{{$u_column}}'].style.visibility='hidden'
                                                        this.form['numberBetweenEnd_{{$u_column}}'].style.display='none'
                                                        this.form['numberBetweenEnd_{{$u_column}}'].value=''
                                                        }"
                                            >
                                                <option selected disabled="disabled">Select</option>
                                                <option value="numberEqual_{{$u_column}}"
                                                        @if(request()->input('number_'.$u_column) === 'numberEqual_'.$u_column) selected @endif>
                                                    =
                                                </option>
                                                <option value="numberNotEqual_{{$u_column}}"
                                                        @if(request()->input('number_'.$u_column) === 'numberNotEqual_'.$u_column) selected @endif>
                                                    !=
                                                </option>
                                                <option value="numberLess_{{$u_column}}"
                                                        @if(request()->input('number_'.$u_column) === 'numberLess_'.$u_column) selected @endif>
                                                    <
                                                </option>
                                                <option value="numberLessEqual_{{$u_column}}"
                                                        @if(request()->input('number_'.$u_column) === 'numberLessEqual_'.$u_column) selected @endif>
                                                    <=
                                                </option>
                                                <option value="numberGrater_{{$u_column}}"
                                                        @if(request()->input('number_'.$u_column) === 'numberGrater_'.$u_column) selected @endif>
                                                    >
                                                </option>
                                                <option value="numberGraterEqual_{{$u_column}}"
                                                        @if(request()->input('number_'.$u_column) === 'numberGraterEqual_'.$u_column) selected @endif>
                                                    >=
                                                </option>
                                                <option value="numberBetween_{{$u_column}}"
                                                        @if(request()->input('number_'.$u_column) === 'numberBetween_'.$u_column) selected @endif>
                                                    Between
                                                </option>
                                                <option value="numberNotBetween_{{$u_column}}"
                                                        @if(request()->input('number_'.$u_column) === 'numberNotBetween_'.$u_column) selected @endif>
                                                    Not Between
                                                </option>
                                                <option value="numberEmpty_{{$u_column}}"
                                                        @if(request()->input('number_'.$u_column) === 'numberEmpty_'.$u_column) selected @endif>
                                                    Empty
                                                </option>
                                                <option value="numberNotEmpty_{{$u_column}}"
                                                        @if(request()->input('number_'.$u_column) === 'numberNotEmpty_'.$u_column) selected @endif>
                                                    Not Empty
                                                </option>
                                            </select>
                                            <input type="number" name="generalNumberValue_{{$u_column}}"
                                                   placeholder="Enter number"
                                                   @if(app('request')->input('generalNumberValue_'.$u_column)) style="visibility: visible"
                                                   @else style="visibility: hidden; display: none" @endif
                                                   value="@if(app('request')->input('number_'.$u_column)){{app('request')->input('generalNumberValue_'.$u_column)}}@endif">
                                            <input type="number" name="numberBetweenStart_{{$u_column}}"
                                                   placeholder="Enter start number"
                                                   @if(app('request')->input('numberBetweenStart_'.$u_column)) style="visibility: visible"
                                                   @else style="visibility: hidden; display: none" @endif
                                                   value="@if(app('request')->input('number_'.$u_column)){{app('request')->input('numberBetweenStart_'.$u_column)}}@endif">
                                            <input type="number" name="numberBetweenEnd_{{$u_column}}"
                                                   placeholder="Enter end number"
                                                   @if(app('request')->input('numberBetweenEnd_'.$u_column)) style="visibility: visible"
                                                   @else style="visibility: hidden; display: none" @endif
                                                   value="@if(app('request')->input('number_'.$u_column)){{app('request')->input('numberBetweenEnd_'.$u_column)}}@endif">
                                        </div>
                                    </li>
                                </ul>
                            @else

                                <ul class='collapsible '>
                                    <li @if(request()->input('condition_'.$u_column)) class="active" @endif>
                                        <div class="collapsible-header pt-3 pb-3"><i
                                                class="material-icons">arrow_drop_down</i> {{ $column->label }}
                                        </div>
                                        <div class='collapsible-body pt-7 pb-7 pl-7 pr-7'>
                                            <select name="condition_{{$u_column}}"
                                                    class="browser-default string-select-class"
                                                    onchange="
                                                        if (this.value=='isEmpty_{{$u_column}}')
                                                        {this.form['{{$u_column}}'].style.display='none'
                                                        this.form['{{$u_column}}'].value=''
                                                        this.form['{{$u_column}}'].style.visibility='hidden'}
                                                        else if (this.value=='isNotEmpty_{{$u_column}}')
                                                        {this.form['{{$u_column}}'].style.display='none'
                                                        this.form['{{$u_column}}'].value=''
                                                        this.form['{{$u_column}}'].style.visibility='hidden'}
                                                        else {this.form['{{$u_column}}'].style.display='block'
                                                        this.form['{{$u_column}}'].style.visibility='visible'}"
                                            >
                                                <option selected disabled="disabled">Select</option>
                                                <option value="is_{{$u_column}}"
                                                        @if(request()->input('condition_'.$u_column) === 'is_'.$u_column) selected @endif>
                                                    Is
                                                </option>
                                                <option value="isNot_{{$u_column}}"
                                                        @if(request()->input('condition_'.$u_column) === 'isNot_'.$u_column) selected @endif>
                                                    Isn't
                                                </option>
                                                <option value="contains_{{$u_column}}"
                                                        @if(request()->input('condition_'.$u_column) === 'contains_'.$u_column) selected @endif>
                                                    Contains
                                                </option>
                                                <option value="notContains_{{$u_column}}"
                                                        @if(request()->input('condition_'.$u_column) === 'notContains_'.$u_column) selected @endif>
                                                    Doesn't Contains
                                                </option>
                                                <option value="startWith_{{$u_column}}" class="empty"
                                                        @if(request()->input('condition_'.$u_column) === 'startWith_'.$u_column) selected @endif>
                                                    Start with
                                                </option>
                                                <option value="endWith_{{$u_column}}" class="empty"
                                                        @if(request()->input('condition_'.$u_column) === 'endWith_'.$u_column) selected @endif>
                                                    End with
                                                </option>
                                                <option value="isEmpty_{{$u_column}}" class="empty"
                                                        @if(request()->input('condition_'.$u_column) === 'isEmpty_'.$u_column) selected @endif>
                                                    Is Empty
                                                </option>
                                                <option value="isNotEmpty_{{$u_column}}" class="notEmpty"
                                                        @if(request()->input('condition_'.$u_column) === 'isNotEmpty_'.$u_column) selected @endif>
                                                    Is Not Empty
                                                </option>
                                            </select>
                                            <input type="text" name="{{$u_column}}"
                                                   placeholder="Enter {{$column->label}}"
                                                   value="@if(app('request')->input($u_column)){{app('request')->input($u_column)}}@endif"
                                                   @if(request()->input($u_column)) style="display: block"
                                                   @else style="display: none" @endif
                                            >

                                        </div>
                                    </li>
                                </ul>
                            @endif
                        @endif
                    @endif
                @endforeach
            @endforeach
            <hr style="border-top: dotted 1px;">
            <div class="row" id="filter" @if(app('request')->input('filter') === 'filter') style="display: none"
                 @else style="display: block" @endif>
                <div class="col s6">
                    <a href="#!" id="reset" class="waves-effect waves-light red  btn">
                        Reset
                    </a>
                </div>
                <div class="col s6">
                    <button type="submit" class="waves-effect waves-light  btn">Filter</button>
                </div>
            </div>
            <div class="row" id="save-filter" @if(app('request')->input('filter') === 'filter') style="display: block"
                 @else style="display: none" @endif>
                <div class="col s6">
                    <a href="#!" class="black-text btn waves-effect white darken-text-2" id="savefilter-cancel">
                        Cancel
                    </a>
                </div>
                <div class="col s6">
                    <a type="submit" class="waves-effect waves-light green btn modal-trigger"
                       href="#save_filter_modal">
                        Save
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="save_filter_modal" class="modal" style="width: 40%">
    <div class="modal-content">
        <h5>Save Filter</h5>
        <form action="{{ route('savefilter.store') }}" method="post">
            @csrf
            <input type="hidden" name="module_name" value="{{ str_replace(' ', '_', $table_name) }}">
            <input type="hidden" name="link" value="{{ url()->full() }}">
            <div class="row mt-5">
                <div class="input-field col s12">
                    <input id="filter_name" type="text" class="validate" name="name" required>
                    <label for="filter_name">Filter Name</label>
                </div>
                <div class="input-field col s12">
                    <button class="btn cyan waves-effect waves-light right" type="submit">Save
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>

<div id="save_filter_update_modal" class="modal" style="width: 40%">
    <div class="modal-content">
        <h5>Rename Filter</h5>
        <form action="{{ route('savefilter.update') }}" method="post">
            @csrf
            <input id="update_filter_id" type="hidden" name="id">
            <div class="row mt-5">
                <label for="filter_name">Filter Name</label>
                <div class="input-field col s12">
                    <input id="update_filter_name" type="text" class="validate" name="name" required>
                </div>
                <div class="input-field col s12">
                    <button class="btn cyan waves-effect waves-light right" type="submit">Save
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
