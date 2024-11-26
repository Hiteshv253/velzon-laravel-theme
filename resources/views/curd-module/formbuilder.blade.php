@extends('layouts.master')
@section('title')
@lang('translation.wizard')
@endsection
@section('content')
@include('components.breadcrumb')
@section('css')
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"
    type="text/css" />
@endsection


<style>
    input[type=text]:not(.browser-default) {
        box-sizing: border-box;







    }

    .file-field input[type=file] {
        position: initial;







    }

    [type='checkbox']:not(:checked),
    [type='checkbox']:checked {
        opacity: 1;
        position: relative;
        pointer-events: auto;







    }

    .form-wrap.form-builder .frmb>li:first-child:hover {
        box-shadow: none;







    }

    .form-wrap.form-builder .frmb>li:first-child {
        opacity: 1;
        position: relative !important;







    }

    .form-wrap.form-builder .frmb>li:first-child>.field-actions:first-child {
        display: none !important;







    }
</style>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Create New Module#</h4>
            </div><!-- end card header -->


            <div class="card-body">
                <div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="name">Module Name</label>
                                <input type="text" class="form-control" id="module-name" name="name"
                                    placeholder="Enter user name" required>
                                <div class="invalid-feedback">Please enter a module name</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h6 class="fw-semibold">Select Action</h6>
                            <select class="form-select mb-3" aria-label="Default select example" name="is_active"
                                id="is_active">
                                <option selected>Select </option>
                                <option value="0">Active</option>
                                <option value="1">Block</option>
                            </select>
                        </div>
                    </div>
                    <div class=" ">
                        <div class=" ">
                            <div class=" ">

                                <div class="card">
                                    <div class="card-content">
                                        <div id="fb-editor"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="d-flex align-items-start gap-3 mt-4">
                <button onClick="backbtn()" type="button" class="btn btn-link text-decoration-none btn-label previestab"
                    data-previous="pills-gen-info-tab"><i
                        class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Back to
                    General</button>
                <button type="submit" id="saveFormData" class="btn btn-success btn-label right ms-auto nexttab nexttab"
                    data-nexttab="pills-success-tab"><i
                        class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>Submit</button>
            </div>

            </div>  
            <!-- end col -->
        </div><!-- end row -->
    </div>
</div>

<!-- end row -->
@endsection
@section('script')
 <script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>

<script>
    function setOptionValue(evt) {
        evt.target.nextSibling.value = evt.target.value;
    }

    function applyOptionChanges(option) {
        option.removeEventListener("input", setOptionValue, false);
        option.addEventListener("input", setOptionValue, false);
        option.nextSibling.style.display = "none";
        option.placeholder = "Label / Value";
    }

    function selectOptions(fld) {
        const optionLabelInputs = fld.querySelectorAll(".option-label");
        for (i = 0; i < optionLabelInputs.length; i++) {
            applyOptionChanges(optionLabelInputs[i]);
        }
    }

    function createObserver(fld) {
        const callback = function (mutationsList) {
            for (var mutation of mutationsList) {
                selectOptions(fld);
            }
        };
        const observer = new MutationObserver(callback);
        observer.observe(fld.querySelector(".sortable-options"), { childList: true });
        return observer
    }

    function onAddOptionInput(fld) {
        selectOptions(fld);
        const observer = createObserver(fld);
        // console.log(observer)
    }

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
        defaultFields: [{
            className: "form-control first-module-name-class",
            label: "Name",
            placeholder: "Enter name",
            name: "name",
            required: true,
            type: "text",
            id: "first_module_name_id"
        }],
        typeUserEvents: {
        "checkbox-group": {
            onadd: onAddOptionInput
        },
        "radio-group": {
            onadd: onAddOptionInput
        },
        select: {
            onadd: onAddOptionInput
        }
    }
};
    var fbEditor = $(document.getElementById('fb-editor'));
    var formBuilder = $(fbEditor).formBuilder(options);
    document.getElementById('saveFormData').addEventListener('click', function (e) {
        e.preventDefault();

        var module_name = $('#module-name').val();
        if (module_name) {

            $.ajax({

                url: "{{ route('module.store') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "name": module_name,
                    "is_active": $('#is_active').val(),
                    "form_data": formBuilder.actions.getData('json', true),
                },
                success: function (result) {
                    console.log(result);
                    // swal("Module "+ module_name, "Created successfully!", "success");
                    Swal.fire({
                        title: "Module " + module_name,
                        text: result.message,
                        icon: 'success',
                        dangerMode: true,
                        buttons: {
                            cancel: 'Create New Module',
                            delete: 'Go to ' + module_name
                        }
                    }).then(function (willDelete) {
                        if (willDelete) {
                            window.location = '/modules/module/' + module_name.replace(' ', '_')
                        } else {
                            window.location.reload()
                        }
                    });
                },
                error: function (result) {
                    Swal.fire({
                        title: result.responseJSON.message,
                        icon: 'error'
                    })
                }
            });
        } else {
            Swal.fire({
                title: 'You must enter module name!',
                icon: 'error'
            })
        }
    });
});

    $('.form-wrap.form-builder .frmb>li:first-child').draggable({ disabled: true });
    $('.form-field').draggable({ disabled: true });
</script>
@endsection