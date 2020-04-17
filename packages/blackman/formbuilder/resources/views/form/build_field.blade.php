@extends('formbuilder::layouts.base')
@push('head-scripts')@endpush
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{strtoupper($form->title)}}</div>

                    <div class="card-body" id="contentpage">
                        <p>{!! $form->description !!}</p>
                        <a href="{{route('forms')}}" class="btn btn-light mb-3"><i class="fa fa-arrow-left"></i> Back to Forms</a>
                        <a href="" onclick="location.reload()" class="btn btn-primary mb-3 ml-3">
                            <i class="fa fa-plus"></i> Add New</a>
                        @if($form->fields->count() > 0)
                        <a href="{{route('form.preview',[$form->id])}}" target="_blank" class="btn btn-outline-secondary ml-3 mb-3">
                            <i class="fa fa-eye"></i> Preview</a>
                        @endif
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <h5>Enter the Field details</h5>
                        <div class="mb-5" id="theFieldForm">
                            <form action="{{route('form.builder.save',[$form->id])}}" method="post">
                                @csrf
                                <input type="hidden" name="form_id" value="{{$form->id}}">
                                <div class="accordion" id="accordionExample">
                                    <div class="card mb-1" style="border-bottom: 1px solid rgb(223, 223, 223);">

                                        <div class="card-header" style="background-color: #fff; padding: 20px; border-bottom: 0;" id="item">

                                            <div class="row">
                                                <div class="form-group col-md-1">
                                                    <label for="order">Order</label>
                                                    <br>
                                                    <input type="number" min="0" required class="form-control" value="{{old('order')}}" name="order" id="order">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="label">Label Title</label>
                                                    <br>
                                                    <input type="text" required value="{{old('label')}}" class="form-control" name="label" id="label" placeholder="Eg. Student Number">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="input_type">Input Type</label>
                                                    <br>
                                                    <select class="form-control select" style="width: 100%;" name="input_type" onchange="select_option()" required id="input_type">
                                                        <option> </option>
                                                        @foreach($fields as $key=>$field)
                                                            <option {{(old('input_type')==$key)?'selected': ''}} value="{{$key}}">{{$field}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="name">Name</label>
                                                    <br>
                                                    <input type="text" required value="{{old('name')}}" class="form-control" name="name" id="name" placeholder="Eg. age">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="required">Required? <span class="text-mute"></span></label>
                                                    <br>
                                                    <select name="required" style="width: 100%;" class="form-control select" id="required">
                                                        <option {{(old('required')==0)?'selected': ''}} value="0">No</option>
                                                        <option {{(old('required')==1)?'selected': ''}} value="1">Yes</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="autocomplete">Autocomplete? <span class="text-mute"></span></label>
                                                    <br>
                                                    <select name="autocomplete" style="width: 100%;" onchange="hasAutoOption()" class="form-control" id="autocomplete">
                                                        <option {{(old('autocomplete')==0)?'selected': ''}} value="0">No</option>
                                                        <option {{(old('autocomplete')==1)?'selected': ''}} value="1">Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <hr>
                                            {{--SHOWS ONLY WHEN AUTOCOMPLETE IS YES--}}
                                            <div id="show_autoOptions">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="has_auto_options">Has Auto-complete options? <span class="text-mute"></span></label>
                                                        <br>
                                                        <select name="has_auto_options" style="width: 100%;" onchange="hasAnOption()" class="form-control" id="has_auto_options">
                                                            <option {{(old('has_auto_options')==0)?'selected': ''}} value="0">No</option>
                                                            <option {{(old('has_auto_options')==1)?'selected': ''}} value="1">Yes, Single Option</option>
                                                            <option {{(old('has_auto_options')==2)?'selected': ''}} value="2">Yes, Multiple Options</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6" id="the_opts">
                                                        <label for="auto_option">Select Option <span class="text-mute"></span></label>
                                                        <br>
                                                        <select name="auto_option" style="width: 100%;" class="form-control select2" id="auto_option">
                                                            <option value="0"></option>
                                                            @foreach($lookups as $lookup)
                                                                <option {{(old('auto_option')==$lookup->code)?'selected': ''}} value="{{$lookup->code}}">{{$lookup->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            {{--SHOWS ONLY WHEN SELECT OR RADIO OR CHECKBOX INPUT TYPE IS SELECTED--}}
                                            <div class="row" id="show_hasChild" style="display: none;">
                                                <div class="form-group col-md-2">
                                                    <label for="is_multiple">Multiple Select? <span class="text-mute"></span></label>
                                                    <br>
                                                    <select name="is_multiple" style="width: 100%;" class="form-control select" id="is_multiple">
                                                        <option {{(old('is_multiple')==0)?'selected': ''}} value="0">No</option>
                                                        <option {{(old('is_multiple')==1)?'selected': ''}} value="1">Yes</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="required">Has no sub form? <span class="text-mute"></span></label>
                                                    <br>
                                                    <select name="hasChild" style="width: 100%;" onchange="isChecked()" class="form-control select" id="hasChild">
                                                        <option {{(old('required')==0)?'selected': ''}} value="0">No</option>
                                                        <option {{(old('required')==1)?'selected': ''}} value="1">Yes</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3" id="show_type" style="display: none">
                                                    <label for="required" style="margin-top: 1.1rem;"></label>
                                                    <br>
                                                    <select readonly="readonly" name="showBy" style="width: 100%;" class="form-control select" id="showBy">
                                                        <option selected value="0">Not Applicable</option>
                                                        <option value="2">Show As Options</option>
    {{--                                                    <option {{(old('required')==0)?'selected': ''}} disabled value="1">Show All Forms</option>--}}
                                                    </select>
                                                </div>
                                            </div>


                                            {{--SHOWS ONLY WHEN BUTTON INPUT TYPE IS SELECTED--}}
                                            <div class="row" id="show_button_dropdown" style="display: none;">
                                                <div class="form-group col-md-2">
                                                    <label for="is_dropdown_button">Is Dropdown Button? <span class="text-mute"></span></label>
                                                    <br>
                                                    <select name="is_dropdown_button" style="width: 100%;" class="form-control select" onchange="isDropdown()" id="is_dropdown_button">
                                                        <option {{(old('is_dropdown_button')==0)?'selected': ''}} value="0">No</option>
                                                        <option {{(old('is_dropdown_button')==1)?'selected': ''}} value="1">Yes</option>
                                                    </select>

                                                </div>

                                                <div class="form-group col-md-6" id="show_button_url">
                                                    <label for="button_url">Button URL? <span class="text-mute"></span></label>
                                                    <br>
                                                    <input type="text" class="form-control" name="button_url" id="button_url" placeholder="https://www.vimye.com">
                                                </div>

                                                <div class="form-group col-md-12 repeater-default" id="show_button_dropdown_options" style="display: none;">
                                                    <div data-repeater-list="button_dropdown_options">
                                                        <h6>Add Options</h6>
                                                        <div data-repeater-item>
                                                            <div class="row">
                                                                <div class="form-group mb-1 col-md-4">
                                                                    <input type="text" class="form-control" name="opt_name" id="opt_name" placeholder="Display Name">
                                                                </div>
                                                                <div class="form-group mb-1 col-md-4">
                                                                    <select name="opt_value" style="width: 100%;" class="form-control select2" id="opt_value">
                                                                        <option value="0">--- Choose Option Value  ---</option>
                                                                        @foreach($lookups as $lookup)
                                                                            <option {{(old('auto_option')==$lookup->code)?'selected': ''}} value="{{$lookup->code}}">{{$lookup->name}}</option>
                                                                        @endforeach
                                                                    </select>

                                                                </div>
                                                                <div class="form-group col-sm-12 col-md-1 text-center mb-1">
                                                                    <button type="button" class="btn btn-danger remove-btn" data-repeater-delete> <i class="ft-x"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <span data-repeater-create class="btn btn-outline-info repeater-add-btn">
                                                            <i class="fa fa-plus"></i> Add an option
                                                        </span>
                                                    </div>
                                                </div>

                                            </div>

                                            {{--SHOWS ONLY WHEN SELECT OR RADIO OR CHECKBOX INPUT TYPE IS SELECTED AND HAS NO SUB FORMS--}}
                                            <div class="repeater-default" style="display: none;" id="show_options">
                                                <div data-repeater-list="options">
                                                    <h6>Add Options</h6>
                                                    <div data-repeater-item>
                                                        <div class="row">
                                                            <div class="form-group mb-1 col-md-4">
                                                                <input type="text" class="form-control" name="opt_name" id="opt_name" placeholder="Display Name">
                                                            </div>
                                                            <div class="form-group mb-1 col-md-3">
                                                                <input type="text" class="form-control" name="opt_value" id="opt_value" placeholder="Value">
                                                            </div>
                                                            <div class="form-group col-sm-12 col-md-1 text-center mb-1">
                                                                <button type="button" class="btn btn-danger remove-btn" data-repeater-delete> <i class="ft-x"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <span data-repeater-create class="btn btn-outline-info repeater-add-btn">
                                                        <i class="fa fa-plus"></i> Add an option
                                                    </span>
                                                </div>
                                            </div>

                                            {{--SHOWS ONLY WHEN SELECT OR RADIO OR CHECKBOX INPUT TYPE IS SELECTED AND HAS SUB FORMS--}}
                                            <div class="repeater-default" style="display: none;" id="show_forms">
                                                <div data-repeater-list="forms">
                                                    <h6>Select Forms</h6>
                                                    <div data-repeater-item>
                                                        <div class="row">
                                                            <div class="form-group mb-1 col-md-4">
                                                                <input type="text" class="form-control" name="opt_name" id="opt_name" placeholder="Display Name">
                                                            </div>
                                                            <div class="form-group mb-1 col-md-3">
                                                                <input type="text" class="form-control" name="opt_value" id="opt_value" placeholder="Value">
                                                            </div>
                                                            <div class="form-group mb-1 col-md-4">
                                                                <select name="c_form" id="c_form" styl="display:none;" class="pickForm form-control">
                                                                    <option value="0">-- Choose Form --</option>
                                                                    @foreach($forms as $theForm)
                                                                        <option value="{{$theForm->id}}">{{$theForm->title}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-sm-12 col-md-1 text-center mb-1">
                                                                <button type="button" class="btn btn-danger remove-btn" data-repeater-delete> <i class="ft-x"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <span data-repeater-create class="btn btn-outline-info repeater-add-btn">
                                                        <i class="fa fa-plus"></i> Add an option
                                                    </span>
                                                </div>
                                            </div>

                                            {{--SHOWS ONLY FOR A FILE INPUT TYPE--}}
                                            <div class="row">

                                                <div class="form-group col-md-6 file_options">
                                                    <label for="file_type">Allowed file types</label>
                                                    <br>
                                                    <input type="text" placeholder="pdf,xlsx,docx,txt" class="form-control"
                                                           value="{{old('file_type')}}" name="file_type" id="file_type">
                                                </div>

                                                <div class="form-group col-md-2 file_options">
                                                    <label for="file_size">Max File Size (Mb)</label>
                                                    <br>
                                                    <input type="number" class="form-control" value="{{old('file_size')}}" name="file_size" id="file_size">
                                                </div>
                                            </div>

                                            {{--SHOWS ONLY FOR A DESCRIPTION INPUT TYPE--}}
                                            <div class="row">
                                                <div class="form-group col-md-12 description_option" style="display: none;">
                                                    <label for="description">Write Description</label>
                                                    <br>
                                                    <textarea name="description"  id="editor1" cols="30" rows="7"
                                                              class="textarea form-control{{ $errors->has('description') ? ' is-invalid' : '' }}">{!! old('description') !!}</textarea>
                                                </div>
                                            </div>

                                            <a class="dp text-right btn btn-secondary" href="#" type="button" data-toggle="collapse" data-target="#thebody" aria-expanded="true" aria-controls="thebody">
                                                <i class="fa fa-expand" aria-hidden="true"></i> More Attributes
                                            </a>

                                        </div>

                                        <div id="thebody" class="collapse bb" aria-labelledby="item" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <label for="placeholder">Placeholder</label>
                                                        <br>
                                                        <input type="text" placeholder="Enter placeholder" class="form-control" name="placeholder" id="placeholder">
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="default_value">Default Value</label>
                                                        <br>
                                                        <input type="text" value="{{old('default_value')}}" class="form-control" name="default_value" id="default_value">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="class">Class <small>(separate option with a space)</small></label>
                                                        <br>
                                                        <input type="text" value="{{old('class')}}" placeholder="text-center col-md-2 card" class="form-control" name="class" id="class">
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="disabled">Disabled? <span class="text-mute"></span></label>
                                                        <br>
                                                        <select name="disabled" style="width: 100%;" class="form-control select2" id="disabled">
                                                            <option {{(old('disabled')==1)?'selected': ''}} value="1">Yes</option>
                                                            <option {{(old('disabled')==0)?'selected': ''}} value="0">No</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="inline_css">Inline CSS</label>
                                                        <br>
                                                        <input type="text" placeholder="font-size:13px; text-align:center" class="form-control"
                                                               value="{{old('inline_css')}}" name="inline_css" id="inline_css">
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="min">Min</label>
                                                        <br>
                                                        <input type="number" class="form-control" value="{{old('min')}}" name="min" id="min">
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="max">Max</label>
                                                        <br>
                                                        <input type="number" class="form-control" value="{{old('max')}}" name="max" id="max">
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="step">Step</label>
                                                        <br>
                                                        <input type="number" step="0.001" class="form-control" value="{{(old('step'))?old('step'):1}}" name="step" id="step">
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="column_size">Column Size <span class="text-mute"></span></label>
                                                        <br>
                                                        <select name="column_size" style="width: 100%;" class="form-control select" id="column_size">
                                                            @for($a=1; $a <= 12; $a++)
                                                                <option
                                                                        @if(old('column_size')==$a || $a == 6)
                                                                        selected
                                                                        @endif
                                                                        value="{{$a}}">{{$a}}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success square">Submit Fields</button>

                            </form>
                        </div>


                        @isset($form_fields)
                            {{--@php--}}
                                {{--$form_fields = $form->fields->orderBy('order','asc');--}}
                            {{--@endphp--}}
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th style="width:40px">Order</th>
                                <th>Field</th>
                                <th style="width: 200px;">Action</th>
                            </tr>
                            @foreach($form_fields as $form_field)
                                <tr>
                                    <td>{{ucwords($form_field->order)}}</td>
                                    <td>{{ucwords($form_field->label)}}</td>
                                    <td>
                                        <a href="#contentpage" class="btn btn-outline-secondary mr-1" style="text-align: right;" type="button" onclick="edit({{$form_field->id}})">
                                            <i class="fa fa-edit" aria-hidden="true"></i> Edit
                                        </a>
                                        <button class="btn btn-danger mr-1" style="text-align: right;" type="button" onclick="destroy({{$form_field->id}})">
                                            <i class="fa fa-trash" aria-hidden="true"></i> Remove
                                        </button>
                                        <form id="delete-field-{{$form_field->id}}" action="{{ route('form_field.delete',[$form_field->id]) }}" method="POST" style="display: none;">
                                            @csrf {{method_field('DELETE')}}
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')

    <script src="{{asset('form-builder/plugins/ckeditor/ckeditor.js')}}" type="text/javascript"></script>
    <script src="{{asset('form-builder/plugins/wysihtml5/bootstrap3-wysihtml5.all.min.js')}}" type="text/javascript"></script>

    <script>
        $('document').ready(function(){
            CKEDITOR.replace('editor1');
            isChecked();
            select_option();
            hasAnOption();
            hasAutoOption();
            isDropdown();
        });

        function hasAutoOption() {
            let opt_val = $("select#autocomplete>option:selected").val();
            if (opt_val == 1) {
                $("#show_autoOptions").show();
                hasAnOption();
            }else{
                $('#show_autoOptions').hide();
                hasAnOption()
            }
        }

        function isDropdown() {
            var opt_val = $("select#is_dropdown_button>option:selected").val();
            if (opt_val == 1) {
                $("#show_button_dropdown_options").show();
                $("#show_button_url").hide();
            }else{
                $('#show_button_dropdown_options').hide();
                $('#show_button_url').show();
            }
        }

        function hasAnOption() {
            var opt_val = $("select#has_auto_options>option:selected").val();
            if (opt_val >= 1) {
                $("#the_opts").show();
            }else{
                $('#the_opts').hide();
            }
        }

        function isChecked() {
            var opt_val = $("select#hasChild>option:selected").val();
            if (opt_val == 1) {
                $("#show_options").hide();
                $('#show_type').show();
                $('#show_forms').show();
            }else{
                // $('#show_forms').hide().find('input, textarea, button, select').prop("disabled", true);
                $('#show_forms').hide();
                $('#show_type').hide();
                $('#show_options').show();
            }
        }

        function select_option() {
            var option = $( "select#input_type>option:selected" ).val();
            if (option === 'file'){
                $( ".file_options" ).show();
                $( "#show_options" ).hide();
                $( "#show_hasChild" ).hide();
                $( "#show_forms" ).hide();
                $( ".description_option" ).hide();
                $( "#show_button_dropdown" ).hide();

            }else if(option === 'select' || option === 'checkbox' || option === 'radio'){
                $( ".file_options" ).hide();
                $( "#show_options" ).show();
                $( "#show_hasChild" ).show();
                $( "#show_forms" ).hide();
                $( ".description_option" ).hide();
                $( "#show_button_dropdown" ).hide();

            }else if(option === 'wysiwyg'){
                $( ".file_options" ).hide();
                $( "#show_options" ).hide();
                $( "#show_forms" ).hide();
                $( "#show_hasChild" ).hide();
                $( ".description_option" ).show();
                $( "#show_button_dropdown" ).hide();

            } else if(option === 'button'){
                $( ".file_options" ).hide();
                $( "#show_options" ).hide();
                $( "#show_forms" ).hide();
                $( "#show_hasChild" ).hide();
                $( ".description_option" ).hide();
                $( "#show_button_dropdown" ).show();
            } else{
                $( ".file_options" ).hide();
                $( "#show_hasChild" ).hide();
                $( "#show_forms" ).hide();
                $( "#show_options" ).hide();
                $( ".description_option" ).hide();
                $( "#show_button_dropdown" ).hide();
            }
        }

        function destroy(key) {
            if (confirm("Data will be lost after Deletion. Delete?")){
                $('form#delete-field-'+key).submit();
            }
        }

        function edit(key) {
            var path = "{{route('form_field.edit')}}";
            $.ajaxSetup(    {
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                }
            });
            $.ajax({
                url: path,
                type: 'GET',
                data: {field_id:key},
                // beforeSend: function(){
                //     $('#theFieldForm').block({
                //         message: '<div class="ft-loader icon-spin font-large-1"></div>',
                //         // timeout: 2000, //unblock after 2 seconds
                //         overlayCSS: {
                //             backgroundColor: '#ccc',
                //             opacity: 0.8,
                //             cursor: 'wait'
                //         },
                //         css: {
                //             border: 0,
                //             padding: 0,
                //             backgroundColor: 'transparent'
                //         }
                //     });
                // },
                success: function(data){
                    $('#theFieldForm').empty().html(data.theView);
                },
                complete:function(){
                    {{--$.getScript("{{asset('form-builder/plugins/repeater/jquery.repeater.min.js')}}");--}}
                    {{--$.getScript("{{asset('form-builder/plugins/repeater/form-repeater.js')}}");--}}
                    isChecked();
                    select_option();
                    hasAnOption();
                    hasAutoOption();
                    CKEDITOR.replace('editor1');
                },
                error: function (data) {
                    console.log(data.field)
                }
            });
        }
    </script>

    <script src="{{ asset('form-builder/plugins/repeater/jquery.js') }}"></script>
    <script src="{{ asset('form-builder/plugins/repeater/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('form-builder/plugins/repeater/form-repeater.js') }}"></script>{{--<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="--}}
            {{--crossorigin="anonymous"></script>--}}
{{--    <script src="{{ asset('form-builder/plugins/repeater/repeater.js') }}"></script>--}}
    {{--<script>--}}
        {{--$("#repeater").createRepeater({--}}
            {{--showFirstItemToDefault: true,--}}
        {{--});--}}
    {{--</script>--}}
@endpush
