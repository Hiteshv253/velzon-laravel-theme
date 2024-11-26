@extends('layouts.master')

@section('css')
    <style>
        input[type=text]:not(.browser-default) {
            box-sizing: border-box;
        }

        .file-field input[type=file] {
            position: initial;
        }

        [type='checkbox']:not(:checked), [type='checkbox']:checked {
            opacity: 1;
            position: relative;
            pointer-events: auto;
        }

        .form-wrap.form-builder .frmb > li:first-child:hover {
            box-shadow: none;
        }

        .form-wrap.form-builder .frmb > li:first-child {
            opacity: 1;
            position: relative !important;
        }

        .form-wrap.form-builder .frmb > li:first-child > .field-actions:first-child {
            display: none !important;
        }
    </style>
@endsection
@section('content')
    <div class="pt-1 pb-0" id="breadcrumbs-wrapper">
        <!-- Search for small screen-->
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l6">
                    <h5 class="breadcrumbs-title"><span>Edit Module</span></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col s8 offset-l2">
        <div class="container">
            <div class="section">
                <div class="input-field">
                    <label for="name">Name</label>
                    <input id="module-name" type="text" name="name" autocomplete="off" value="{{ $module_ori->name }}"
                           disabled>
                </div>
                <div class="card">
                    <div class="card-content">
                        <div id="fb-editor"></div>
                    </div>
                </div>
                <button id="saveFormData" class="mb-6 btn waves-effect waves-light gradient-45deg-light-blue-cyan"
                        type="submit">Save
                    <i class="material-icons right">send</i>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>

    <script>
        jQuery(function ($) {
            var options = {
                controlPosition: 'left',
                disabledActionButtons: ['data', 'save', 'clear'],
                editOnAdd: true,
                typeUserDisabledAttrs: {
                    'checkbox-group': [
                        'toggle', 'inline', 'other'
                    ],
                    'date': [
                        'value'
                    ],
                    'file': [
                        'multiple', 'subtype'
                    ],

                    'radio-group': [
                        'inline', 'other'
                    ],
                    'textarea': ['subtype'],
                    'section': ['required', 'placeholder']
                },
                disableFields: ['button', 'hidden', 'autocomplete', 'header', 'paragraph'],
                disabledAttrs: ['access', 'className', 'name', 'value', 'description'],
                disabledSubtypes: {
                    'text': ['color'],
                    'textarea': ['quill', 'tinymce'],
                    'paragraph': ['output', 'canvas', 'address'],
                    'file': ['fineuploader']
                },

                templates: {
                    lookup: function (fieldData) {
                        return {
                            field: '<input type="text" id="' + fieldData.name + '" class="form-control">',
                        }
                    },
                    multiLookup: function (fieldData) {
                        return {
                            field: '<input type="text" id="' + fieldData.name + '" class="form-control">',
                        }
                    },
                    section: function (fieldData) {
                        return {
                            field: '<h4 type="text" id="' + fieldData.name + '" class="card-title"></h4>',
                        }
                    },
                },

                fields: [
                    {
                        label: 'Lookup',
                        attrs: {
                            type: 'lookup',
                        },
                        icon: '&',
                    },
                    {
                        label: 'Multi-select Lookup',
                        attrs: {
                            type: 'multiLookup',
                        },
                        icon: '*',
                    },
                    {
                        label: 'Section',
                        attrs: {
                            type: 'section',
                        },
                        icon: '@',
                    },


                ],

                typeUserAttrs: {
                    lookup: {
                        module: {
                            label: 'Module',
                            options: {
                                @foreach($modules as $module)
                                '{{ $module->name }}': '{{ $module->name }}',
                                @endforeach
                            },
                        }
                    },
                    multiLookup: {
                        module: {
                            label: 'Module',
                            options: {
                                @foreach($modules as $module)
                                '{{ $module->name }}': '{{ $module->name }}',
                                @endforeach
                            },
                        },
                        fieldName: {
                            label: 'Field Label',
                            value: '',
                            placeholder: 'Field Label in Related Module',
                            required: true
                        }
                    }

                },
                defaultFields: {!! $module_ori->form !!}
            };
            var fbEditor = $(document.getElementById('fb-editor'));
            var formBuilder = $(fbEditor).formBuilder(options);
            document.getElementById('saveFormData').addEventListener('click', function (e) {
                e.preventDefault();

                var module_name = $('#module-name').val();
                if (module_name) {

                    $.ajax({

                        url: "{{ route('module.update', $module_ori->id) }}",
                        method: 'put',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "name": module_name,
                            "form_data": formBuilder.actions.getData('json', true),
                        },
                        success: function (result) {
                            console.log(result);
                            swal({
                                title: "Module " + module_name + " Updated!",
                                text: result.message,
                                icon: 'success',
                                dangerMode: true,
                                buttons: {
                                    cancel: 'Go To Manage Module',
                                    delete: 'Go to ' + module_name
                                }
                            }).then(function (willDelete) {
                                if (willDelete) {
                                    window.location = '/modules/module/' + module_name.replace(' ', '_')
                                } else {
                                    window.location = '/module';
                                }
                            });
                        },
                        error: function (result) {
                            swal({
                                title: result.responseJSON.message,
                                icon: 'error'
                            })
                        }
                    });
                } else {
                    swal({
                        title: 'You must enter module name!',
                        icon: 'error'
                    })
                }
            });
        });

        $('.form-wrap.form-builder .frmb>li:first-child').draggable({disabled: true});
        $('.form-field').draggable({disabled: true});
    </script>
@endsection
