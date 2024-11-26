@if(isset($module_name))
    @php $tableName = $module_name; @endphp
@elseif($table_name)
    @php $tableName = $table_name; @endphp
@endif
@if($tableName === 'employees')
    <div class="input-field col s12">
        <input type="email" class="validate" placeholder="Enter email"
               @if(isset($table_data)) value="{{ $table_data->email }}" @endif name="email" required>
        <label for="Email">Email</label>
    </div>
@endif
@if($tableName === 'departments' || $tableName === 'teams' || $tableName === 'designations')
    @php $employees = \App\CrudModel\Employee::all(); @endphp
    @if(isset($table_data))
        @if($tableName === 'departments')
            @php $table = \App\CrudModel\Department::find($table_data->id); @endphp
        @elseif($tableName === 'teams')
            @php $table = \App\CrudModel\Team::find($table_data->id); @endphp
        @elseif($tableName === 'designations')
            @php $table = \App\CrudModel\Designation::find($table_data->id); @endphp
        @endif
        @php $employeesIn = $table->employees->pluck('id')->toArray(); @endphp
    @endif
    <label for="Employees">Employees</label>
    <div class="input-field col s12">
        <select name="employees[]"
                class="select2 browser-default" multiple="multiple">
            <optgroup label="Employees">
                @foreach($employees as $employee)
                    <option
                        value="{{ $employee->id }}"
                        @if(isset($employeesIn)) @if(in_array($employee->id, $employeesIn)) selected @endif @endif
                    >{{ $employee->Name }}</option>
                @endforeach
            </optgroup>
        </select>
    </div>
@endif

@foreach($module_form as $forms)
    @foreach(json_decode($forms->form) as $form)

        <div class="col s12">
            <div class="row">
                @php $field_name = str_replace(' ', '_', $form->label) @endphp
                @if($form->type === 'text')
                    <div class="input-field col s12">

                        @if($form->subtype === 'text')
                            <input type="text"
                                   name="{{ str_replace(' ', '_', $form->label) }}"
                                   class=" @if($form->required === true) validate @endif @if(isset($form->maxlength)) maxlength @endif"
                                   @if($form->required === true) required @endif
                                   @if(isset($form->placeholder)) placeholder="{{ $form->placeholder }}" @endif
                                   @if(isset($form->maxlength)) data-length="{{ $form->maxlength }}" @endif
                                   @if(isset($table_data)) value="{{ $table_data->$field_name }}" @endif
                            >
                        @elseif($form->subtype === 'email')
                            <input type="email"
                                   name="{{ str_replace(' ', '_', $form->label) }}"
                                   class=" @if($form->required === true) validate @endif @if(isset($form->maxlength)) maxlength @endif"
                                   @if($form->required === true) required @endif
                                   @if(isset($form->placeholder)) placeholder="{{ $form->placeholder }}" @endif
                                   @if(isset($form->maxlength)) data-length="{{ $form->maxlength }}" @endif
                                   @if(isset($table_data)) value="{{ $table_data->$field_name }}" @endif
                            >
                        @elseif($form->subtype === 'password')
                            <input type="password"
                                   name="{{ str_replace(' ', '_', $form->label) }}"
                                   class=" @if($form->required === true) validate @endif @if(isset($form->maxlength)) maxlength @endif"
                                   @if($form->required === true) required @endif
                                   @if(isset($form->placeholder)) placeholder="{{ $form->placeholder }}" @endif
                                   @if(isset($form->maxlength)) data-length="{{ $form->maxlength }}" @endif
                                   @if(isset($table_data)) value="{{ $table_data->$field_name }}" @endif
                            >
                        @else
                            <input type="text"
                                   name="{{ str_replace(' ', '_', $form->label) }}"
                                   class=" @if($form->required === true) validate @endif @if(isset($form->maxlength)) maxlength @endif"
                                   @if($form->required === true) required @endif
                                   @if(isset($form->placeholder)) placeholder="{{ $form->placeholder }}" @endif
                                   @if(isset($form->maxlength)) data-length="{{ $form->maxlength }}" @endif
                                   @if(isset($table_data)) value="{{ $table_data->$field_name }}" @endif
                            >
                        @endif

                        <label for="{{$form->label}}">{{$form->label}}</label>
                    </div>
                @elseif($form->type === 'select')
                    <label for="{{ $form->label }}">{{ $form->label }}</label>
                    <div class=" input-field col s12">
                        @if($form->multiple === false)
                            <select name="{{ str_replace(' ', '_', $form->label) }}"
                                    class="select2 browser-default">
                                @foreach($form->values as $option)
                                    <option
                                        value="{{ $option->label }}"
                                        @if(isset($option->selected)) selected @endif
                                        @if(isset($table_data)) @if($table_data->$field_name === $option->label) selected @endif @endif
                                    >{{ $option->label }}</option>
                                @endforeach

                            </select>
                        @else
                            <select name="{{ str_replace(' ', '_', $form->label) }}[]"
                                    class="select2 browser-default" multiple="multiple">
                                <optgroup label="{{$form->label}}">
                                    @foreach($form->values as $option)
                                        <option
                                            value="{{ $option->label }}"
                                            @if(isset($option->selected)) selected @endif
                                            @if(isset($table_data))
                                            @php $values = explode(',', $table_data->$field_name) @endphp
                                            @foreach($values as $value)
                                            @if($option->label === $value) selected @endif
                                            @endforeach
                                            @endif
                                        >{{ $option->label }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        @endif
                    </div>
                @elseif($form->type === 'number')
                    <div class=" input-field col s12">
                        <label for="{{$form->label}}">{{$form->label}}</label>
                        <input type="number"
                               name="{{ str_replace(' ', '_', $form->label) }}"
                               class=" @if($form->required === true) validate @endif"
                               @if($form->required === true) required @endif
                               @if(isset($form->placeholder)) placeholder="{{ $form->placeholder }}" @endif
                               @if(isset($form->min)) min="{{ $form->min }}" @endif
                               @if(isset($form->max)) max="{{ $form->max }}" @endif
                               @if(isset($form->step)) step="{{ $form->step }}" @endif
                               @if(isset($table_data)) value="{{ $table_data->$field_name }}" @endif
                        >
                    </div>
                @elseif($form->type === 'textarea')
                    <div class=" input-field col s12">
                            <textarea name="{{ str_replace(' ', '_', $form->label) }}"
                                      class="materialize-textarea @if(isset($form->maxlength)) maxlength @endif"
                                      @if(isset($form->maxlength)) data-length="{{ $form->maxlength }}" @endif
                                      @if(isset($form->rows)) rows="{{ $form->rows }}" @endif
                                      >@if(isset($table_data)){{ $table_data->$field_name }}@endif</textarea>
                        <label for="{{ $form->label }}" class="">{{ $form->label }}</label>
                    </div>
                @elseif($form->type === 'checkbox-group')
                    <div class="col s12">
                        <label for="{{ $form->label }}">{{ $form->label }}</label>
                        @foreach($form->values as $option)
                            <p>
                                <label>
                                    <input type="checkbox" name="{{ str_replace(' ', '_', $form->label) }}[]"
                                           value="{{ $option->label }}" @if(isset($option->selected)) checked @endif
                                           @if(isset($option->required)) class="validate" required @endif
                                           @if(isset($table_data))
                                           @php $values = explode(',', $table_data->$field_name) @endphp
                                           @foreach($values as $value)
                                           @if($option->label === $value) checked @endif
                                        @endforeach
                                        @endif
                                    />
                                    <span>{{ $option->label }}</span>
                                </label>
                            </p>
                            <div class="input-field">
                            </div>
                        @endforeach
                    </div>
                @elseif($form->type === 'radio-group')
                    <div class="col s12">
                        <p>{{ $form->label }} </p>
                        @foreach($form->values as $option)
                            <p>
                                <label>
                                    <input name="{{ str_replace(' ', '_', $form->label) }}" type="radio"
                                           value="{{ $option->label }}"
                                           @if(isset($option->selected)) checked @endif
                                           @if(isset($table_data)) @if($table_data->$field_name === $option->label) checked @endif @endif/>
                                    <span>{{ $option->label }}</span>
                                </label>
                            </p>
                        @endforeach

                        <div class="input-field">
                            <small class="errorTxt8"></small>
                        </div>
                    </div>
                @elseif($form->type === 'date')
                    <div class=" input-field col s12">
                        <label for="birthdate">{{ $form->label }}</label>
                        <input type="text" class="dynamic_datepicker"
                               name="{{ str_replace(' ', '_', $form->label) }}"
                               @if(isset($table_data)) value="{{ $table_data->$field_name }}" @endif>
                    </div>
                @elseif($form->type === 'section')
                    <div class="card-title mt-6">

                        <div class="row">
                            <div class="col s12">
                                <h4 class="card-title">
                                    {{ $form->label }}
                                </h4>
                            </div>
                        </div>
                    </div>
                @elseif($form->type === 'lookup')
                    <label for="{{ $form->label }}">{{ $form->label }}</label>
                    <div class="input-field col s12">
                        @php $table_datas = DB::table(str_replace(' ', '_', $form->module))->get(); @endphp
                        <select name="{{ str_replace(' ', '_', $form->label) }}"
                                class="select2 browser-default">
                            @foreach($table_datas as $data)
                                <option value="{{ $data->Name }}"
                                        @if(isset($table_data)) @if($table_data->$field_name === $data->Name) selected @endif @endif>
                                    {{ $data->Name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @elseif($form->type === 'multiLookup')
                    <label for="{{ $form->label }}">{{ str_replace('_', ' ', $form->label) }}</label>
                    <div class="input-field col s12">
                        @php $table_datas = DB::table(str_replace(' ', '_', $form->module))->get(); @endphp
                        <i class="material-icons prefix">phone</i>
                        <select name="{{ str_replace(' ', '_', $form->label) }}[]"
                                class="select2 browser-default" multiple="multiple">
                            <optgroup label="{{$form->label}}">
                                @foreach($table_datas as $data)
                                    <option value="{{ $data->Name }}"
                                            @if(isset($table_data))
                                            @php $values = explode(',', $table_data->$field_name) @endphp
                                            @foreach($values as $value)
                                            @if($data->Name === $value) selected @endif
                                        @endforeach
                                        @endif>
                                        {{ $data->Name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                @elseif($form->type === 'file')
                    <div class="file-field input-field">
                        <div class="btn">
                            <span>{{ $form->label }}</span>
                            <input type="file" name="{{ str_replace(' ', '_', $form->label) }}">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@endforeach
